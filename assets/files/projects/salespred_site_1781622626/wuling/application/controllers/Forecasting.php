<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Forecasting — Controller Peramalan Tren Penjualan (SMA)
 * Metode: Simple Moving Average 3-bulanan
 *
 * Endpoint:
 *  GET  /forecasting           → halaman utama
 *  POST /forecasting/refresh   → AJAX update chart & tabel
 *  GET  /forecasting/export    → download CSV hasil prediksi
 *
 * @package    Wuling Management System
 * @subpackage Controllers
 */
class Forecasting extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_sales');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);

        // Guard: hanya user yang sudah login
        if ( ! $this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    /* ─────────────────────────────────────────────────────── */
    /*  INDEX — Halaman utama peramalan                        */
    /* ─────────────────────────────────────────────────────── */
    public function index() {
        // Parameter filter dari GET (dengan nilai default)
        $year            = $this->input->get('year')             ?: 'all';
        $model_filter    = $this->input->get('model')            ?: 'all';
        $n               = max(2, (int)($this->input->get('periods')          ?: 3));
        $forecast_months = max(1, (int)($this->input->get('forecast_months')  ?: 3));

        // Ambil data historis dari model
        $raw_data = $this->M_sales->get_filtered_history($year, $model_filter, 24);

        // Kalkulasi SMA & proyeksi
        $sma_data  = $this->M_sales->calculate_sma($raw_data, $n);
        $forecasts = $this->M_sales->generate_forecast($raw_data, $n, $forecast_months);
        $accuracy  = $this->M_sales->calculate_accuracy($sma_data);

        $data = [
            'title'            => 'Peramalan Tren Penjualan — SMA',
            'years'            => $this->M_sales->get_available_years(),
            'car_models'       => $this->M_sales->get_available_models(),
            'raw_data'         => $raw_data,
            'sma_data'         => $sma_data,
            'forecasts'        => $forecasts,
            'accuracy'         => $accuracy,
            // Filter aktif (untuk repopulate form)
            'sel_year'         => $year,
            'sel_model'        => $model_filter,
            'sel_n'            => $n,
            'sel_fm'           => $forecast_months,
        ];

        $this->load->view('forecasting/index', $data);
    }

    /* ─────────────────────────────────────────────────────── */
    /*  REFRESH — AJAX endpoint (JSON)                         */
    /* ─────────────────────────────────────────────────────── */
    public function refresh() {
        if ( ! $this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $year            = $this->input->post('year')             ?: 'all';
        $model_filter    = $this->input->post('model')            ?: 'all';
        $n               = max(2, (int)($this->input->post('periods')         ?: 3));
        $forecast_months = max(1, (int)($this->input->post('forecast_months') ?: 3));

        $raw_data  = $this->M_sales->get_filtered_history($year, $model_filter, 24);
        $sma_data  = $this->M_sales->calculate_sma($raw_data, $n);
        $forecasts = $this->M_sales->generate_forecast($raw_data, $n, $forecast_months);
        $accuracy  = $this->M_sales->calculate_accuracy($sma_data);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success'   => true,
                'sma_data'  => $sma_data,
                'forecasts' => $forecasts,
                'accuracy'  => $accuracy,
            ]));
    }

    /* ─────────────────────────────────────────────────────── */
    /*  EXPORT — Download CSV                                  */
    /* ─────────────────────────────────────────────────────── */
    public function export() {
        $year            = $this->input->get('year')             ?: 'all';
        $model_filter    = $this->input->get('model')            ?: 'all';
        $n               = max(2, (int)($this->input->get('periods')          ?: 3));
        $forecast_months = max(1, (int)($this->input->get('forecast_months')  ?: 3));

        $raw_data  = $this->M_sales->get_filtered_history($year, $model_filter, 24);
        $sma_data  = $this->M_sales->calculate_sma($raw_data, $n);
        $forecasts = $this->M_sales->generate_forecast($raw_data, $n, $forecast_months);

        if (empty($raw_data)) {
            $this->session->set_flashdata('error', 'Tidak ada data untuk diekspor.');
            redirect('forecasting');
            return;
        }

        $filename = 'SMA_Forecast_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $fp = fopen('php://output', 'w');
        // BOM untuk Excel UTF-8
        fputs($fp, "\xEF\xBB\xBF");

        fputcsv($fp, ['Periode', 'Aktual (Unit)', 'SMA-' . $n, 'Error (Aktual - SMA)']);

        foreach ($sma_data as $row) {
            fputcsv($fp, [
                $row['label'],
                $row['actual'],
                $row['sma'] ?? '-',
                $row['forecast_error'] ?? '-',
            ]);
        }

        // Baris kosong sebagai pemisah
        fputcsv($fp, []);
        fputcsv($fp, ['=== PROYEKSI ' . $forecast_months . ' BULAN KE DEPAN ===']);
        fputcsv($fp, ['Periode', 'Proyeksi (Unit)']);

        foreach ($forecasts as $row) {
            fputcsv($fp, [$row['label'], $row['forecast']]);
        }

        fclose($fp);
        exit;
    }
}

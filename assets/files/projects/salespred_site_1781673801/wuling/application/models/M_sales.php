<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * M_sales — Model Peramalan Tren Penjualan
 * Mendukung modul Forecasting dengan metode Simple Moving Average (SMA)
 *
 * @package    Wuling Management System
 * @subpackage Models
 */
class M_sales extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Ambil riwayat penjualan bulanan terurut kronologis (ASC)
     * untuk keperluan kalkulasi Moving Average.
     *
     * @param  int $limit  Jumlah bulan terakhir yang diambil (default 12)
     * @return array       Array asosiatif [{tahun, bulan_num, bulan, total_unit}]
     */
    public function get_monthly_sales_history($limit = 12) {
        // Mapping nama bulan ke angka agar ORDER BY kronologis akurat
        $this->db->select("
            tahun,
            CASE bulan
                WHEN 'January'   THEN 1
                WHEN 'February'  THEN 2
                WHEN 'March'     THEN 3
                WHEN 'April'     THEN 4
                WHEN 'May'       THEN 5
                WHEN 'June'      THEN 6
                WHEN 'July'      THEN 7
                WHEN 'August'    THEN 8
                WHEN 'September' THEN 9
                WHEN 'October'   THEN 10
                WHEN 'November'  THEN 11
                WHEN 'December'  THEN 12
            END AS bulan_num,
            bulan,
            COUNT(*) AS total_unit,
            SUM(price_net) AS total_revenue
        ", FALSE);

        $this->db->from('transactions');
        $this->db->group_by(['tahun', 'bulan']);
        $this->db->order_by('tahun', 'ASC');
        $this->db->order_by('bulan_num', 'ASC');

        if ($limit > 0) {
            // Ambil N baris terbaru secara kronologis terbalik, lalu balik urutan
            $this->db->limit($limit);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Hitung Simple Moving Average (SMA) atas data historis
     *
     * @param  array $data     Array riwayat penjualan dari get_monthly_sales_history()
     * @param  int   $n        Jumlah periode untuk rata-rata (default 3)
     * @return array           [{label, actual, sma}]
     */
    public function calculate_sma($data, $n = 3) {
        $result = [];
        $count  = count($data);

        for ($i = 0; $i < $count; $i++) {
            $label  = $data[$i]['bulan'] . ' ' . $data[$i]['tahun'];
            $actual = (int) $data[$i]['total_unit'];
            $sma    = null;

            if ($i >= $n - 1) {
                $sum = 0;
                for ($j = $i - $n + 1; $j <= $i; $j++) {
                    $sum += (int) $data[$j]['total_unit'];
                }
                $sma = round($sum / $n, 2);
            }

            $result[] = [
                'label'          => $label,
                'tahun'          => $data[$i]['tahun'],
                'bulan'          => $data[$i]['bulan'],
                'actual'         => $actual,
                'sma'            => $sma,
                'forecast_error' => ($sma !== null) ? ($actual - $sma) : null,
            ];
        }

        return $result;
    }

    /**
     * Proyeksi penjualan N bulan ke depan berdasarkan SMA dari data terakhir
     *
     * @param  array $data             Data historis mentah
     * @param  int   $n                Periode SMA
     * @param  int   $forecast_months  Jumlah bulan yang diproyeksi
     * @return array                   [{label, forecast}]
     */
    public function generate_forecast($data, $n = 3, $forecast_months = 3) {
        $count = count($data);
        if ($count < $n) {
            return [];
        }

        // Hitung rata-rata dari N data terakhir
        $last_slice = array_slice($data, -$n);
        $sum = array_sum(array_column($last_slice, 'total_unit'));
        $forecast_value = round($sum / $n, 0);

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March',
            4 => 'April',   5 => 'May',       6 => 'June',
            7 => 'July',    8 => 'August',    9 => 'September',
            10 => 'October', 11 => 'November', 12 => 'December',
        ];

        $last       = end($data);
        $last_month = (int) $last['bulan_num'];
        $last_year  = (int) $last['tahun'];

        $forecasts = [];
        for ($i = 1; $i <= $forecast_months; $i++) {
            $month_num = (($last_month - 1 + $i) % 12) + 1;
            $year_add  = (int) floor(($last_month - 1 + $i) / 12);
            $forecasts[] = [
                'label'    => $months[$month_num] . ' ' . ($last_year + $year_add),
                'forecast' => (int) $forecast_value,
            ];
        }

        return $forecasts;
    }

    /**
     * Hitung metrik akurasi: MAE, MSE, RMSE, MAPE
     *
     * @param  array $sma_data  Output dari calculate_sma()
     * @return array|null       Metrik akurasi atau null jika tidak ada data
     */
    public function calculate_accuracy($sma_data) {
        $valid = array_filter($sma_data, fn($r) => $r['sma'] !== null);

        if (empty($valid)) {
            return null;
        }

        $n   = count($valid);
        $mae = $mse = $mape_sum = 0;

        foreach ($valid as $row) {
            $err      = abs($row['forecast_error']);
            $mae     += $err;
            $mse     += pow($row['forecast_error'], 2);
            if ($row['actual'] != 0) {
                $mape_sum += ($err / $row['actual']) * 100;
            }
        }

        return [
            'mae'  => round($mae / $n, 2),
            'mse'  => round($mse / $n, 2),
            'rmse' => round(sqrt($mse / $n), 2),
            'mape' => round($mape_sum / $n, 2),
        ];
    }

    /**
     * Ambil daftar tahun yang tersedia di tabel transaksi
     *
     * @return array
     */
    public function get_available_years() {
        $this->db->select('DISTINCT(tahun) AS year', FALSE);
        $this->db->from('transactions');
        $this->db->order_by('tahun', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil daftar model kendaraan yang tersedia
     *
     * @return array
     */
    public function get_available_models() {
        $this->db->select('DISTINCT(model) AS model_name', FALSE);
        $this->db->from('transactions');
        $this->db->order_by('model', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil riwayat bulanan dengan filter opsional (tahun/model)
     * Digunakan oleh controller Forecasting untuk filter dinamis
     *
     * @param  int|null    $year   Filter tahun (null = semua tahun)
     * @param  string|null $model  Filter model kendaraan ('all' = semua)
     * @param  int         $limit  Limit baris
     * @return array
     */
    public function get_filtered_history($year = null, $model = null, $limit = 24) {
        $this->db->select("
            tahun,
            CASE bulan
                WHEN 'January'   THEN 1
                WHEN 'February'  THEN 2
                WHEN 'March'     THEN 3
                WHEN 'April'     THEN 4
                WHEN 'May'       THEN 5
                WHEN 'June'      THEN 6
                WHEN 'July'      THEN 7
                WHEN 'August'    THEN 8
                WHEN 'September' THEN 9
                WHEN 'October'   THEN 10
                WHEN 'November'  THEN 11
                WHEN 'December'  THEN 12
            END AS bulan_num,
            bulan,
            COUNT(*) AS total_unit,
            SUM(price_net) AS total_revenue
        ", FALSE);

        $this->db->from('transactions');

        if ($year && $year !== 'all') {
            $this->db->where('tahun', $year);
        }
        if ($model && $model !== 'all') {
            $this->db->where('model', $model);
        }

        $this->db->group_by(['tahun', 'bulan']);
        $this->db->order_by('tahun', 'ASC');
        $this->db->order_by('bulan_num', 'ASC');

        if ($limit > 0) {
            $this->db->limit($limit);
        }

        return $this->db->get()->result_array();
    }
}

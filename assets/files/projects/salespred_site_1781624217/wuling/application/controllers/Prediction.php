<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prediction extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Prediction_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    /**
     * Main prediction page
     */
    public function index() {
        $data['title'] = 'Sales Prediction - Moving Average';
        $data['years'] = $this->Prediction_model->get_available_years();
        $data['models'] = $this->Prediction_model->get_available_models();
        $data['sales_summary'] = $this->Prediction_model->get_sales_summary_by_model();
        
        // Default parameters
        $selected_year = $this->input->get('year') ?? 2021;
        $selected_model = $this->input->get('model') ?? 'all';
        $periods = $this->input->get('periods') ?? 3;
        $forecast_periods = $this->input->get('forecast_periods') ?? 3;
        
        $data['selected_year'] = $selected_year;
        $data['selected_model'] = $selected_model;
        $data['periods'] = $periods;
        $data['forecast_periods'] = $forecast_periods;
        
        // Get sales data
        $sales_data = $this->Prediction_model->get_monthly_sales_data($selected_year, $selected_model);
        $data['sales_data'] = $sales_data;
        
        // Calculate moving average if data exists
        if (!empty($sales_data)) {
            $moving_averages = $this->Prediction_model->calculate_moving_average($sales_data, $periods);
            $forecasts = $this->Prediction_model->generate_forecast($sales_data, $periods, $forecast_periods);
            $accuracy = $this->Prediction_model->calculate_accuracy($moving_averages);
            
            $data['moving_averages'] = $moving_averages;
            $data['forecasts'] = $forecasts;
            $data['accuracy'] = $accuracy;
        } else {
            $data['moving_averages'] = [];
            $data['forecasts'] = [];
            $data['accuracy'] = null;
        }
        
        $this->load->view('prediction/index', $data);
    }

    /**
     * AJAX endpoint for updating prediction
     */
    public function update_prediction() {
        $year = $this->input->post('year');
        $model = $this->input->post('model');
        $periods = $this->input->post('periods');
        $forecast_periods = $this->input->post('forecast_periods');
        
        // Get sales data
        $sales_data = $this->Prediction_model->get_monthly_sales_data($year, $model);
        
        $response = [
            'success' => false,
            'data' => []
        ];
        
        if (!empty($sales_data)) {
            $moving_averages = $this->Prediction_model->calculate_moving_average($sales_data, $periods);
            $forecasts = $this->Prediction_model->generate_forecast($sales_data, $periods, $forecast_periods);
            $accuracy = $this->Prediction_model->calculate_accuracy($moving_averages);
            
            $response['success'] = true;
            $response['data'] = [
                'sales_data' => $sales_data,
                'moving_averages' => $moving_averages,
                'forecasts' => $forecasts,
                'accuracy' => $accuracy
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * Export prediction results to Excel
     */
    public function export_excel() {
        $year = $this->input->get('year') ?? 2021;
        $model = $this->input->get('model') ?? 'all';
        $periods = $this->input->get('periods') ?? 3;
        $forecast_periods = $this->input->get('forecast_periods') ?? 3;
        
        $sales_data = $this->Prediction_model->get_monthly_sales_data($year, $model);
        
        if (empty($sales_data)) {
            show_404();
            return;
        }
        
        $moving_averages = $this->Prediction_model->calculate_moving_average($sales_data, $periods);
        $forecasts = $this->Prediction_model->generate_forecast($sales_data, $periods, $forecast_periods);
        $accuracy = $this->Prediction_model->calculate_accuracy($moving_averages);
        
        // Set headers for Excel download
        $filename = 'Sales_Prediction_' . $year . '_' . str_replace(' ', '_', $model) . '.xls';
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // Generate HTML Table for Excel
        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8"></head>';
        $html .= '<body>';
        
        // Title
        $html .= '<table border="0" cellpadding="3">';
        $html .= '<tr><th colspan="6" style="font-size: 18px; text-align: left;">LAPORAN PREDIKSI PENJUALAN WULING</th></tr>';
        $html .= '<tr><td colspan="2"><strong>Tahun Data</strong></td><td colspan="4">: ' . $year . '</td></tr>';
        $html .= '<tr><td colspan="2"><strong>Model Kendaraan</strong></td><td colspan="4">: ' . ($model == 'all' ? 'Semua Model' : $model) . '</td></tr>';
        $html .= '<tr><td colspan="2"><strong>Metode</strong></td><td colspan="4">: Moving Average (n=' . $periods . ')</td></tr>';
        $html .= '<tr><td colspan="6"></td></tr>';
        $html .= '</table>';

        // Data Table
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="background-color: #cf2127; color: white; font-weight: bold; width: 100px;">Tipe</th>';
        $html .= '<th style="background-color: #cf2127; color: white; font-weight: bold; width: 120px;">Periode</th>';
        $html .= '<th style="background-color: #cf2127; color: white; font-weight: bold; width: 120px;">Penjualan Aktual</th>';
        $html .= '<th style="background-color: #cf2127; color: white; font-weight: bold; width: 150px;">Moving Average</th>';
        $html .= '<th style="background-color: #cf2127; color: white; font-weight: bold; width: 120px;">Ramalan</th>';
        $html .= '<th style="background-color: #cf2127; color: white; font-weight: bold; width: 120px;">Error</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        // Write historical data with moving averages
        foreach ($moving_averages as $item) {
            $html .= '<tr>';
            $html .= '<td>Historis</td>';
            $html .= '<td>' . $item['period'] . '</td>';
            $html .= '<td style="text-align: right;">' . $item['actual_sales'] . '</td>';
            $html .= '<td style="text-align: right;">' . $item['moving_average'] . '</td>';
            $html .= '<td style="background-color: #f3f4f6;"></td>';
            $html .= '<td style="text-align: right; color: ' . ($item['forecast_error'] < 0 ? 'red' : 'green') . ';">' . $item['forecast_error'] . '</td>';
            $html .= '</tr>';
        }
        
        // Write forecast data
        foreach ($forecasts as $item) {
            $html .= '<tr>';
            $html .= '<td style="background-color: #fef3c7; font-weight: bold;">Ramalan</td>';
            $html .= '<td style="background-color: #fef3c7;">' . $item['period'] . '</td>';
            $html .= '<td style="background-color: #fef3c7;"></td>';
            $html .= '<td style="background-color: #fef3c7;"></td>';
            $html .= '<td style="background-color: #fef3c7; font-weight: bold; text-align: right; color: #d97706;">' . $item['forecast_sales'] . '</td>';
            $html .= '<td style="background-color: #fef3c7;"></td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        $html .= '<br><br>';
        
        // Accuracy Table
        if ($accuracy) {
            $html .= '<table border="1" cellpadding="5">';
            $html .= '<tr><th colspan="2" style="background-color: #0f172a; color: white; font-weight: bold; text-align: left;">METRIK AKURASI MODEL</th></tr>';
            $html .= '<tr><td style="width: 250px;">Mean Absolute Error (MAE)</td><td style="font-weight: bold; text-align: right; width: 100px;">' . $accuracy['mae'] . '</td></tr>';
            $html .= '<tr><td>Mean Squared Error (MSE)</td><td style="font-weight: bold; text-align: right;">' . $accuracy['mse'] . '</td></tr>';
            $html .= '<tr><td>Root Mean Squared Error (RMSE)</td><td style="font-weight: bold; text-align: right;">' . $accuracy['rmse'] . '</td></tr>';
            $html .= '<tr><td style="background-color: #fef3c7; font-weight: bold;">Weighted Abs Pct Error (WAPE)</td><td style="background-color: #fef3c7; font-weight: bold; text-align: right; color: #d97706;">' . $accuracy['wape'] . '%</td></tr>';
            $html .= '</table>';
        }

        $html .= '</body></html>';
        
        echo $html;
    }

    /**
     * API endpoint for chart data
     */
    public function get_chart_data() {
        $year = $this->input->get('year') ?? 2021;
        $model = $this->input->get('model') ?? 'all';
        $periods = $this->input->get('periods') ?? 3;
        $forecast_periods = $this->input->get('forecast_periods') ?? 3;
        
        $sales_data = $this->Prediction_model->get_monthly_sales_data($year, $model);
        
        $chart_data = [
            'labels' => [],
            'actual' => [],
            'moving_average' => [],
            'forecast' => []
        ];
        
        if (!empty($sales_data)) {
            // Add historical data
            foreach ($sales_data as $item) {
                $chart_data['labels'][] = $item['bulan'] . ' ' . $item['tahun'];
                $chart_data['actual'][] = (int)$item['total_sales'];
            }
            
            // Add moving averages
            $moving_averages = $this->Prediction_model->calculate_moving_average($sales_data, $periods);
            $ma_data = array_fill(0, $periods - 1, null);
            
            foreach ($moving_averages as $item) {
                $ma_data[] = $item['moving_average'];
            }
            $chart_data['moving_average'] = $ma_data;
            
            // Add forecasts
            $forecasts = $this->Prediction_model->generate_forecast($sales_data, $periods, $forecast_periods);
            $forecast_data = array_fill(0, count($sales_data), null);
            
            foreach ($forecasts as $item) {
                $chart_data['labels'][] = $item['period'];
                $chart_data['actual'][] = null;
                $chart_data['moving_average'][] = null;
                $forecast_data[] = $item['forecast_sales'];
            }
            $chart_data['forecast'] = $forecast_data;
        }
        
        header('Content-Type: application/json');
        echo json_encode($chart_data);
    }
}
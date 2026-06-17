<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prediksi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('prediction_model');
        $this->load->library('session');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index() {
        $data['title'] = 'Prediksi Penjualan';
        $this->load->view('prediksi', $data);
    }

    public function data() {
        // Get parameters from POST/GET
        $year = $this->input->post('year') ?: null;
        $model = $this->input->post('model') ?: 'all';
        
        // Get monthly sales data
        $sales_data = $this->prediction_model->get_monthly_sales_data($year, $model);
        
        // Return as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $sales_data
        ]);
    }

    public function calculate() {
        $method = $this->input->post('method') ?: 'ma';
        $periods = (int)($this->input->post('periods') ?: 3);
        $model_filter = $this->input->post('model') ?: 'all';
        $future_periods = (int)($this->input->post('future') ?: 3);
        $year = $this->input->post('year') ?: null;

        // Get data
        $data = $this->prediction_model->get_monthly_sales_data($year, $model_filter);

        $result = [];
        
        if ($method === 'ma') {
            // Moving Average
            $ma_result = $this->prediction_model->calculate_moving_average($data, $periods);
            $forecast = $this->prediction_model->generate_ma_forecast($data, $periods, $future_periods);
            
            $result = [
                'method' => 'Moving Average',
                'periods' => $periods,
                'historical' => $ma_result,
                'forecast' => $forecast
            ];
        } else {
            // Linear Regression
            $lr_result = $this->prediction_model->calculate_linear_regression($data);
            $forecast = $this->prediction_model->generate_lr_forecast($data, $future_periods);
            
            $result = [
                'method' => 'Linear Regression',
                'historical' => $lr_result['fitted'],
                'forecast' => $forecast,
                'coefficients' => ['a' => $lr_result['a'], 'b' => $lr_result['b']]
            ];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'result' => $result
        ]);
    }

    public function meta() {
        // Get metadata like available years, models, etc.
        $this->db->select('DISTINCT tahun');
        $this->db->from('transactions');
        $this->db->order_by('tahun', 'ASC');
        $years = $this->db->get()->result_array();

        $this->db->select('DISTINCT model');
        $this->db->from('transactions');
        $this->db->order_by('model', 'ASC');
        $models = $this->db->get()->result_array();

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'years' => array_column($years, 'tahun'),
            'models' => array_column($models, 'model')
        ]);
    }
}
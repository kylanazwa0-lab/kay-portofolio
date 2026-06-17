<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prediction_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get monthly sales data for prediction
     */
    public function get_monthly_sales_data($year = null, $model = null) {
        $this->db->select('tahun, bulan, COUNT(*) as total_sales, SUM(price_net) as total_revenue');
        $this->db->from('transactions');
        
        if ($year) {
            $this->db->where('tahun', $year);
        }
        
        if ($model && $model != 'all') {
            $this->db->where('model', $model);
        }
        
        $this->db->group_by(['tahun', 'bulan']);
        $this->db->order_by('tahun ASC, FIELD(bulan, "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December")');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get available years from transactions
     */
    public function get_available_years() {
        $this->db->select('DISTINCT(tahun) as year');
        $this->db->from('transactions');
        $this->db->order_by('tahun', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get available car models
     */
    public function get_available_models() {
        $this->db->select('DISTINCT(model) as model_name');
        $this->db->from('transactions');
        $this->db->order_by('model', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Calculate Moving Average
     */
    public function calculate_moving_average($data, $periods = 3) {
        $moving_averages = [];
        $data_count = count($data);
        
        if ($data_count < $periods) {
            return $moving_averages;
        }
        
        for ($i = $periods - 1; $i < $data_count; $i++) {
            $sum = 0;
            for ($j = $i - $periods + 1; $j <= $i; $j++) {
                $sum += $data[$j]['total_sales'];
            }
            
            $moving_average = $sum / $periods;
            
            $moving_averages[] = [
                'period' => $data[$i]['bulan'] . ' ' . $data[$i]['tahun'],
                'actual_sales' => $data[$i]['total_sales'],
                'moving_average' => round($moving_average, 2),
                'forecast_error' => $data[$i]['total_sales'] - round($moving_average, 2)
            ];
        }
        
        return $moving_averages;
    }

    /**
     * Generate forecast for next periods
     */
    public function generate_forecast($data, $periods = 3, $forecast_periods = 3) {
        if (count($data) < $periods) {
            return [];
        }
        
        $forecasts = [];
        $last_data = array_slice($data, -$periods);
        
        // Calculate the average of last n periods
        $sum = 0;
        foreach ($last_data as $item) {
            $sum += $item['total_sales'];
        }
        
        $forecast_value = $sum / $periods;
        
        // Generate months for forecast
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 
                  'July', 'August', 'September', 'October', 'November', 'December'];
        
        $last_month = end($data)['bulan'];
        $last_year = end($data)['tahun'];
        $last_month_index = array_search($last_month, $months);
        
        for ($i = 1; $i <= $forecast_periods; $i++) {
            $next_month_index = ($last_month_index + $i) % 12;
            $next_year = $last_year;
            
            if ($last_month_index + $i >= 12) {
                $next_year = $last_year + floor(($last_month_index + $i) / 12);
            }
            
            $forecasts[] = [
                'period' => $months[$next_month_index] . ' ' . $next_year,
                'forecast_sales' => (int) round($forecast_value),
                'forecast_type' => 'Moving Average (' . $periods . ' periods)'
            ];
        }
        
        return $forecasts;
    }

    /**
     * Calculate accuracy metrics
     */
    public function calculate_accuracy($moving_averages) {
        if (empty($moving_averages)) {
            return null;
        }
        
        $total_error = 0;
        $total_absolute_error = 0;
        $total_squared_error = 0;
        $total_actual = 0;
        $count = count($moving_averages);
        
        foreach ($moving_averages as $item) {
            $error = $item['forecast_error'];
            $total_error += $error;
            $total_absolute_error += abs($error);
            $total_squared_error += pow($error, 2);
            $total_actual += $item['actual_sales'];
        }
        
        $mae = $total_absolute_error / $count; // Mean Absolute Error
        $mse = $total_squared_error / $count; // Mean Squared Error
        $rmse = sqrt($mse); // Root Mean Squared Error
        $wape = ($total_absolute_error / $total_actual) * 100; // Weighted Absolute Percentage Error
        
        return [
            'mae' => round($mae, 2),
            'mse' => round($mse, 2),
            'rmse' => round($rmse, 2),
            'wape' => round($wape, 2)
        ];
    }

    /**
     * Get sales summary by model
     */
    public function get_sales_summary_by_model() {
        $this->db->select('model, COUNT(*) as total_sales, SUM(price_net) as total_revenue, AVG(price_net) as avg_price');
        $this->db->from('transactions');
        $this->db->group_by('model');
        $this->db->order_by('total_sales', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }
}
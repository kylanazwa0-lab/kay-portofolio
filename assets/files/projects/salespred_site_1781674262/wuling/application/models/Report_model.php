<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_transactions($month = null, $year = null) {
        $this->db->select('*');
        $this->db->from('transactions');
        
        if (!empty($month)) {
            $this->db->where('bulan', $month);
        }
        
        if (!empty($year)) {
            $this->db->where('tahun', $year);
        }
        
        $this->db->order_by('tahun', 'DESC');
        $this->db->order_by('bulan', 'DESC');
        $this->db->order_by('hari', 'DESC');
        
        return $this->db->get()->result();
    }

    public function get_summary($month = null, $year = null) {
        $this->db->select('
            COUNT(*) as total_transactions,
            SUM(price_list) as total_price_list,
            SUM(discount) as total_discount,
            SUM(price_net) as total_price_net,
            SUM(dp_amt) as total_dp,
            SUM(leasing_amt) as total_leasing
        ');
        $this->db->from('transactions');
        
        if (!empty($month)) {
            $this->db->where('bulan', $month);
        }
        
        if (!empty($year)) {
            $this->db->where('tahun', $year);
        }
        
        return $this->db->get()->row();
    }

    public function get_sales_summary($month = null, $year = null) {
        $this->db->select('
            sales_name,
            COUNT(*) as total_sales,
            SUM(price_net) as total_revenue
        ');
        $this->db->from('transactions');
        $this->db->where('sales_name IS NOT NULL');
        $this->db->where('sales_name !=', '');
        
        if (!empty($month)) {
            $this->db->where('bulan', $month);
        }
        
        if (!empty($year)) {
            $this->db->where('tahun', $year);
        }
        
        $this->db->group_by('sales_name');
        $this->db->order_by('total_revenue', 'DESC');
        
        return $this->db->get()->result();
    }

    public function get_model_summary($month = null, $year = null) {
        $this->db->select('
            model,
            COUNT(*) as total_sold,
            SUM(price_net) as total_revenue
        ');
        $this->db->from('transactions');
        
        if (!empty($month)) {
            $this->db->where('bulan', $month);
        }
        
        if (!empty($year)) {
            $this->db->where('tahun', $year);
        }
        
        $this->db->group_by('model');
        $this->db->order_by('total_sold', 'DESC');
        
        return $this->db->get()->result();
    }

    public function get_years() {
        $this->db->select('DISTINCT(tahun) as tahun');
        $this->db->from('transactions');
        $this->db->order_by('tahun', 'DESC');
        
        return $this->db->get()->result();
    }

    public function get_months() {
        return [
            'January' => 'January',
            'February' => 'February',
            'March' => 'March',
            'April' => 'April',
            'May' => 'May',
            'June' => 'June',
            'July' => 'July',
            'August' => 'August',
            'September' => 'September',
            'October' => 'October',
            'November' => 'November',
            'December' => 'December'
        ];
    }
}
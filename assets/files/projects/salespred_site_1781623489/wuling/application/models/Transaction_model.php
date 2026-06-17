<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_transactions() {
        $query = $this->db->get('transactions');
        return $query->result_array();
    }

    public function get_transaction_by_id($id) {
        $query = $this->db->get_where('transactions', array('id' => $id));
        return $query->row_array();
    }

    public function insert_transaction($data) {
        return $this->db->insert('transactions', $data);
    }

    public function update_transaction($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('transactions', $data);
    }

    public function delete_transaction($id) {
        $this->db->where('id', $id);
        return $this->db->delete('transactions');
    }

    // Method tambahan untuk import dan management
    public function get_all($limit = null, $offset = null)
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('transactions');
        return $query->result();
    }

    public function count_all_records()
    {
        return $this->db->count_all('transactions');
    }

    public function get_by_invoice($inv_no)
    {
        $query = $this->db->get_where('transactions', array('inv_no' => $inv_no));
        return $query->row_array();
    }

    public function insert_batch($data)
    {
        return $this->db->insert_batch('transactions', $data);
    }

    public function search($keyword, $limit = null, $offset = null)
    {
        $this->db->like('customer', $keyword);
        $this->db->or_like('inv_no', $keyword);
        $this->db->or_like('chassis', $keyword);
        $this->db->or_like('sales_name', $keyword);
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get('transactions');
        return $query->result();
    }

    public function count_search_results($keyword)
    {
        $this->db->like('customer', $keyword);
        $this->db->or_like('inv_no', $keyword);
        $this->db->or_like('chassis', $keyword);
        $this->db->or_like('sales_name', $keyword);
        
        return $this->db->count_all_results('transactions');
    }

    public function get_sales_summary($year = null)
    {
        $this->db->select('sales_name, COUNT(*) as total_transactions, SUM(price_net) as total_sales');
        $this->db->group_by('sales_name');
        
        if ($year) {
            $this->db->where('tahun', $year);
        }
        
        $query = $this->db->get('transactions');
        return $query->result();
    }

    public function get_monthly_sales($year = null)
    {
        $this->db->select('bulan, COUNT(*) as total_transactions, SUM(price_net) as total_sales');
        $this->db->group_by('bulan');
        $this->db->order_by('tahun, FIELD(bulan, "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December")');
        
        if ($year) {
            $this->db->where('tahun', $year);
        }
        
        $query = $this->db->get('transactions');
        return $query->result();
    }

    public function get_model_summary($year = null)
    {
        $this->db->select('model, COUNT(*) as total_sold, SUM(price_net) as total_revenue');
        $this->db->group_by('model');
        
        if ($year) {
            $this->db->where('tahun', $year);
        }
        
        $query = $this->db->get('transactions');
        return $query->result();
    }

    // New methods for getting users data
    public function get_sales_users() {
        $this->db->select('id, full_name, username');
        $this->db->where('role', 'admin_sales');
        $this->db->where('status', 'active');
        $this->db->order_by('full_name', 'ASC');
        $query = $this->db->get('users');
        return $query->result_array();
    }

    public function get_spv_users() {
        // SPV sekarang otomatis = operation_manager saja
        $this->db->select('id, full_name, username');
        $this->db->where('role', 'operation_manager'); // <— tadinya where_in([...])
        $this->db->where('status', 'active');
        $this->db->order_by('full_name', 'ASC');
        $query = $this->db->get('users');
        return $query->result_array();
    }
    
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('menu');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }
    
    /**
     * Default dashboard - redirect based on role
     */
    public function index() {
        $role = $this->session->userdata('role');
        
        switch ($role) {
            case 'administration_head':
                redirect('dashboard/admin');
                break;
            case 'admin_bpkb':
                redirect('dashboard/bpkb');
                break;
            case 'admin_sales':
                redirect('dashboard/sales');
                break;
            case 'operation_manager':
                redirect('dashboard/operations');
                break;
            case 'c_level':
                redirect('dashboard/executive');
                break;
            default:
                $this->load->view('dashboard/default');
        }
    }
    
    /**
     * Administration Head Dashboard
     */
    public function admin() {
        $this->_check_role('administration_head');
        $this->load->model('User_model');
        $this->load->model('Transaction_model');
        
        $total_users = $this->User_model->count_users();
        $total_transactions = $this->Transaction_model->count_all_records();
        
        // Coba hitung total revenue (jika ada data)
        $this->db->select_sum('price_net');
        $revenue_query = $this->db->get('transactions');
        $total_revenue = $revenue_query->row()->price_net ?? 0;

        // Ambil summary per sales untuk melihat top performer
        $sales_summary = $this->Transaction_model->get_sales_summary();
        usort($sales_summary, function($a, $b) {
            return $b->total_sales <=> $a->total_sales;
        });
        
        $data = array(
            'title' => 'Administration Dashboard',
            'role_name' => 'Administration Head',
            'total_users' => $total_users,
            'total_transactions' => $total_transactions,
            'total_revenue' => $total_revenue,
            'top_sales' => array_slice($sales_summary, 0, 5),
            'menu_items' => get_menu_items($this->session->userdata('role'))
        );
        
        $this->load->view('dashboard/admin', $data);
    }
    
    /**
     * Admin BPKB Dashboard
     */
    public function bpkb() {
        $this->_check_role('admin_bpkb');
        $this->load->model('Kendaraan_model');
        
        $total_kendaraan = $this->db->count_all('kendaraan');
        $categories_count = $this->db->query("SELECT COUNT(DISTINCT category) as cat_count FROM kendaraan")->row()->cat_count ?? 0;
        
        $data = array(
            'title' => 'BPKB Management Dashboard',
            'role_name' => 'Admin BPKB',
            'total_kendaraan' => $total_kendaraan,
            'categories_count' => $categories_count,
            'menu_items' => get_menu_items($this->session->userdata('role'))
        );
        
        $this->load->view('dashboard/bpkb', $data);
    }
    
    /**
     * Admin Sales Dashboard
     */
    public function sales() {
        $this->_check_role('admin_sales');
        $this->load->model('Transaction_model');
        
        $total_transactions = $this->Transaction_model->count_all_records();
        $total_revenue = $this->db->query("SELECT SUM(price_net) as total FROM transactions")->row()->total ?? 0;
        
        $data = array(
            'title' => 'Sales Management Dashboard',
            'role_name' => 'Admin Sales',
            'total_transactions' => $total_transactions,
            'total_revenue' => $total_revenue,
            'menu_items' => get_menu_items($this->session->userdata('role'))
        );
        
        $this->load->view('dashboard/sales', $data);
    }
    
    /**
     * Operation Manager Dashboard
     */
    public function operations() {
        $this->_check_role('operation_manager');
        $this->load->model('Transaction_model');
        $this->load->model('Kendaraan_model');
        
        $total_transactions = $this->Transaction_model->count_all_records();
        $total_kendaraan = $this->db->count_all('kendaraan');
        $total_revenue = $this->db->query("SELECT SUM(price_net) as total FROM transactions")->row()->total ?? 0;
        
        $data = array(
            'title' => 'Operations Management Dashboard',
            'role_name' => 'Operation Manager',
            'total_transactions' => $total_transactions,
            'total_kendaraan' => $total_kendaraan,
            'total_revenue' => $total_revenue,
            'menu_items' => get_menu_items($this->session->userdata('role'))
        );
        
        $this->load->view('dashboard/operations', $data);
    }
    
    /**
     * C-Level Executive Dashboard
     */
    public function executive() {
        $this->_check_role('c_level');
        $this->load->model('User_model');
        $this->load->model('Transaction_model');
        
        $total_users = $this->User_model->count_users();
        $total_transactions = $this->Transaction_model->count_all_records();
        $total_revenue = $this->db->query("SELECT SUM(price_net) as total FROM transactions")->row()->total ?? 0;
        
        // Ambil summary per sales untuk top performer
        $sales_summary = $this->Transaction_model->get_sales_summary();
        if(is_array($sales_summary)){
            usort($sales_summary, function($a, $b) {
                return $b->total_sales <=> $a->total_sales;
            });
        }
        
        $data = array(
            'title' => 'Executive Dashboard',
            'role_name' => 'C-Level Executive',
            'total_users' => $total_users,
            'total_transactions' => $total_transactions,
            'total_revenue' => $total_revenue,
            'top_sales' => is_array($sales_summary) ? array_slice($sales_summary, 0, 5) : [],
            'menu_items' => get_menu_items($this->session->userdata('role'))
        );
        
        $this->load->view('dashboard/executive', $data);
    }
    
    /**
     * Check user role access
     */
    private function _check_role($required_role) {
        $user_role = $this->session->userdata('role');
        
        if ($user_role !== $required_role) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman tersebut!');
            redirect('dashboard');
        }
    }
    
    /**
     * Get user profile
     */
    public function profile() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user($user_id);
        $data['title'] = 'User Profile';
        
        $this->load->view('dashboard/profile', $data);
    }
}
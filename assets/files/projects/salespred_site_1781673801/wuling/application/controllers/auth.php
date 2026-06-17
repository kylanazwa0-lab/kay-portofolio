<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }
    
    /**
     * Display login form
     */
    public function index() {
        // Redirect if already logged in
        if ($this->session->userdata('logged_in')) {
            redirect($this->_get_dashboard_url());
        }
        
        $this->load->view('auth/login');
    }
    
    /**
     * Process login
     */
    public function login_process() {
        // Set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            // Authenticate user
            $user = $this->User_model->authenticate($username, $password);
            
            if ($user) {
                // Remember Me logic
                if ($this->input->post('remember')) {
                    // Set cookie for 30 days
                    setcookie('remember_username', $username, time() + (86400 * 30), "/");
                    setcookie('remember_password', $password, time() + (86400 * 30), "/"); // Stores plain password as requested
                } else {
                    // Clear cookie
                    setcookie('remember_username', '', time() - 3600, "/");
                    setcookie('remember_password', '', time() - 3600, "/");
                }

                // Set session data
                $session_data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'full_name' => $user->full_name,
                    'role' => $user->role,
                    'logged_in' => TRUE
                );
                
                $this->session->set_userdata($session_data);
                
                // Set success message
                $this->session->set_flashdata('success', 'Login berhasil! Selamat datang, ' . $user->full_name);
                
                // Redirect to appropriate dashboard
                redirect($this->_get_dashboard_url($user->role));
                
            } else {
                // Set error message
                $this->session->set_flashdata('error', 'Username atau password salah!');
                redirect('auth');
            }
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        // Destroy session
        $this->session->sess_destroy();
        
        // Set logout message
        $this->session->set_flashdata('info', 'Anda telah berhasil logout.');
        
        redirect('auth');
    }
    
    /**
     * Get dashboard URL based on role
     */
    private function _get_dashboard_url($role = null) {
        if (!$role) {
            $role = $this->session->userdata('role');
        }
        
        switch ($role) {
            case 'administration_head':
                return 'dashboard/admin';
            case 'admin_bpkb':
                return 'dashboard/bpkb';
            case 'admin_sales':
                return 'dashboard/sales';
            case 'operation_manager':
                return 'dashboard/operations';
            case 'c_level':
                return 'dashboard/executive';
            default:
                return 'dashboard';
        }
    }
    
    /**
     * Check if user is logged in (for AJAX requests)
     */
    public function check_login() {
        if ($this->session->userdata('logged_in')) {
            echo json_encode(array('status' => 'logged_in', 'role' => $this->session->userdata('role')));
        } else {
            echo json_encode(array('status' => 'not_logged_in'));
        }
    }
}
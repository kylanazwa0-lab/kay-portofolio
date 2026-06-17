<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper(array('url', 'auth'));
        
        // Check if user is logged in and has admin access
        check_access(array('administration_head'));
    }
    
    /**
     * Display users list
     */
    public function index() {
        // Pagination configuration
        $this->load->library('pagination');
        
        // Search and Filters
        $search = $this->input->get('search');
        $role_filter = $this->input->get('role');
        $status_filter = $this->input->get('status');

        $filters = array();
        if ($search) $filters['search'] = $search;
        if ($role_filter) $filters['role'] = $role_filter;
        if ($status_filter) $filters['status'] = $status_filter;

        // Build query string for pagination
        $config['reuse_query_string'] = TRUE;
        
        $config['base_url'] = site_url('users/index');
        $config['total_rows'] = $this->User_model->count_users($filters);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        
        // Pagination styling
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        
        $this->pagination->initialize($config);
        
        // Get users with pagination
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['users'] = $this->User_model->get_users($config['per_page'], $page, $filters);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_users'] = $config['total_rows'];
        
        // Pass data for view
        $data['search_term'] = $search;
        $data['role_filter'] = $role_filter;
        $data['status_filter'] = $status_filter;
        $data['roles'] = $this->User_model->get_available_roles();
        
        $data['title'] = 'Kelola Pengguna';
        $this->load->view('users/index', $data);
    }
    
    /**
     * Add new user form
     */
    public function add() {
        $data['title'] = 'Tambah Pengguna Baru';
        $data['roles'] = $this->User_model->get_available_roles();
        
        $this->load->view('users/add', $data);
    }
    
    /**
     * Process add user
     */
    public function add_process() {
        // Set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[password]');
        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]|max_length[100]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[administration_head,admin_bpkb,admin_sales,operation_manager,c_level]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
        
        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Pengguna Baru';
            $data['roles'] = $this->User_model->get_available_roles();
            $this->load->view('users/add', $data);
        } else {
            $user_data = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'full_name' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'role' => $this->input->post('role'),
                'status' => $this->input->post('status'),
                'created_at' => date('Y-m-d H:i:s')
            );
            
            if ($this->User_model->create_user($user_data)) {
                $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan!');
                redirect('users');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengguna!');
                redirect('users/add');
            }
        }
    }
    
    /**
     * Edit user form
     */
    public function edit($id = null) {
        if (!$id) {
            show_404();
        }
        
        $data['user'] = $this->User_model->get_user($id);
        if (!$data['user']) {
            show_404();
        }
        
        $data['title'] = 'Edit Pengguna';
        $data['roles'] = $this->User_model->get_available_roles();
        
        $this->load->view('users/edit', $data);
    }
    
    /**
     * Process edit user
     */
    public function edit_process($id = null) {
        if (!$id) {
            show_404();
        }
        
        $user = $this->User_model->get_user($id);
        if (!$user) {
            show_404();
        }
        
        // Set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[3]|max_length[50]|callback_check_username_edit['.$id.']');
        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[100]|callback_check_email_edit['.$id.']');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[administration_head,admin_bpkb,admin_sales,operation_manager,c_level]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
        
        // Only validate password if provided
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'matches[password]');
        }
        
        if ($this->form_validation->run() == FALSE) {
            $data['user'] = $user;
            $data['title'] = 'Edit Pengguna';
            $data['roles'] = $this->User_model->get_available_roles();
            $this->load->view('users/edit', $data);
        } else {
            $user_data = array(
                'username' => $this->input->post('username'),
                'full_name' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'role' => $this->input->post('role'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            
            // Update password only if provided
            if ($this->input->post('password')) {
                $user_data['password'] = $this->input->post('password');
            }
            
            if ($this->User_model->update_user($id, $user_data)) {
                $this->session->set_flashdata('success', 'Pengguna berhasil diperbarui!');
                redirect('users');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui pengguna!');
                redirect('users/edit/'.$id);
            }
        }
    }
    
    /**
     * Delete user
     */
    public function delete($id = null) {
        if (!$id) {
            show_404();
        }
        
        $user = $this->User_model->get_user($id);
        if (!$user) {
            show_404();
        }
        
        // Prevent deleting current user
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun sendiri!');
            redirect('users');
        }
        
        if ($this->User_model->delete_user($id)) {
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna!');
        }
        
        redirect('users');
    }
    
    /**
     * View user details
     */
    public function view($id = null) {
        if (!$id) {
            show_404();
        }
        
        $data['user'] = $this->User_model->get_user($id);
        if (!$data['user']) {
            show_404();
        }
        
        $data['title'] = 'Detail Pengguna';
        $this->load->view('users/view', $data);
    }
    
    /**
     * Custom validation for username on edit
     */
    public function check_username_edit($username, $user_id) {
        $existing_user = $this->User_model->get_user_by_username($username);
        if ($existing_user && $existing_user->id != $user_id) {
            $this->form_validation->set_message('check_username_edit', 'Username sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Custom validation for email on edit
     */
    public function check_email_edit($email, $user_id) {
        $existing_user = $this->User_model->get_user_by_email($email);
        if ($existing_user && $existing_user->id != $user_id) {
            $this->form_validation->set_message('check_email_edit', 'Email sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Bulk actions
     */
    public function bulk_action() {
        $action = $this->input->post('bulk_action');
        $selected_ids = $this->input->post('selected_users');
        
        if (!$action || !$selected_ids) {
            $this->session->set_flashdata('error', 'Pilih pengguna dan aksi yang akan dilakukan!');
            redirect('users');
        }
        
        $count = 0;
        $current_user_id = $this->session->userdata('user_id');
        
        foreach ($selected_ids as $user_id) {
            // Skip current user
            if ($user_id == $current_user_id) {
                continue;
            }
            
            switch ($action) {
                case 'activate':
                    if ($this->User_model->update_user($user_id, array('status' => 'active'))) {
                        $count++;
                    }
                    break;
                case 'deactivate':
                    if ($this->User_model->update_user($user_id, array('status' => 'inactive'))) {
                        $count++;
                    }
                    break;
                case 'delete':
                    if ($this->User_model->delete_user($user_id)) {
                        $count++;
                    }
                    break;
            }
        }
        
        if ($count > 0) {
            $this->session->set_flashdata('success', $count . ' pengguna berhasil diproses!');
        } else {
            $this->session->set_flashdata('error', 'Tidak ada pengguna yang diproses!');
        }
        
        redirect('users');
    }
}
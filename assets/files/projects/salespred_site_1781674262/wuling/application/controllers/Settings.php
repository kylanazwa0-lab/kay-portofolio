<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper(array('url'));
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user($user_id);
        
        $this->load->view('settings/index', $data);
    }

    public function update_profile() {
        $user_id = $this->session->userdata('user_id');
        
        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        
        // Cek username unique kecuali milik sendiri
        $original_value = $this->db->query("SELECT username FROM users WHERE id = ".$user_id)->row()->username;
        if($this->input->post('username') != $original_value) {
            $is_unique =  '|is_unique[users.username]';
        } else {
            $is_unique =  '';
        }
        $this->form_validation->set_rules('username', 'Username', 'required|trim'.$is_unique);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('settings');
        } else {
            $update_data = array(
                'full_name' => $this->input->post('full_name'),
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email')
            );
            
            if ($this->User_model->update_user($user_id, $update_data)) {
                // Update session
                $this->session->set_userdata('full_name', $update_data['full_name']);
                $this->session->set_userdata('username', $update_data['username']);
                
                $this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui profil.');
            }
            redirect('settings');
        }
    }

    public function update_password() {
        $user_id = $this->session->userdata('user_id');
        
        $this->form_validation->set_rules('current_password', 'Password Saat Ini', 'required');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[new_password]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_password', validation_errors());
            redirect('settings');
        } else {
            $user = $this->User_model->get_user($user_id);
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');
            
            // Verifikasi password lama
            if ($this->User_model->verify_password($current_password, $user->password)) {
                $update_data = array(
                    'password' => $new_password // User_model::update_user akan menghash otomatis jika ada field password
                );
                
                if ($this->User_model->update_user($user_id, $update_data)) {
                    $this->session->set_flashdata('success_password', 'Password berhasil diubah.');
                } else {
                    $this->session->set_flashdata('error_password', 'Gagal mengubah password.');
                }
            } else {
                $this->session->set_flashdata('error_password', 'Password saat ini salah.');
            }
            redirect('settings');
        }
    }
}

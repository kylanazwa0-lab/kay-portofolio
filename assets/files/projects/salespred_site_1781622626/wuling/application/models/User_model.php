<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
    private $table = 'users';
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Authenticate user login
     */
    public function authenticate($username, $password) {
        // Ambil user berdasarkan username & status aktif saja
        $this->db->where('username', $username);
        $this->db->where('status', 'active');
        $query = $this->db->get($this->table);

        if ($query->num_rows() == 1) {
            $user = $query->row();
            // Verifikasi password dengan bcrypt
            if ($this->verify_password($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Hash password dengan bcrypt
     */
    public function hash_password($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verifikasi password terhadap hash
     */
    public function verify_password($password, $hash) {
        // Backward-compat: jika hash bukan bcrypt (plain-text lama), coba cocokkan langsung
        if (strpos($hash, '$2y$') !== 0 && strpos($hash, '$2a$') !== 0) {
            return ($password === $hash);
        }
        return password_verify($password, $hash);
    }
    
    /**
     * Get user by ID
     */
    public function get_user($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        
        return false;
    }
    
    /**
     * Get user by username
     */
    public function get_user_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get($this->table);
        
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        
        return false;
    }
    
    /**
     * Get user by email
     */
    public function get_user_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get($this->table);
        
        if ($query->num_rows() == 1) {
            return $query->row();
        }
        
        return false;
    }
    
    /**
     * Get all users with pagination and filters
     */
    public function get_users($limit = null, $offset = null, $filters = array()) {
        if (!empty($filters['role'])) {
            $this->db->where('role', $filters['role']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('username', $filters['search']);
            $this->db->or_like('full_name', $filters['search']);
            $this->db->or_like('email', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get($this->table);
        
        return $query->result();
    }
    
    /**
     * Count total users with filters
     */
    public function count_users($filters = array()) {
        if (!empty($filters['role'])) {
            $this->db->where('role', $filters['role']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('username', $filters['search']);
            $this->db->or_like('full_name', $filters['search']);
            $this->db->or_like('email', $filters['search']);
            $this->db->group_end();
        }
        return $this->db->count_all_results($this->table);
    }
    
    /**
     * Search users
     */
    public function search_users($search_term) {
        $this->db->group_start();
        $this->db->like('username', $search_term);
        $this->db->or_like('full_name', $search_term);
        $this->db->or_like('email', $search_term);
        $this->db->or_like('role', $search_term);
        $this->db->group_end();
        
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get($this->table);
        
        return $query->result();
    }
    
    /**
     * Create new user
     */
    public function create_user($data) {
        // Hash password sebelum disimpan
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = $this->hash_password($data['password']);
        }
        return $this->db->insert($this->table, $data);
    }
    
    /**
     * Update user
     */
    public function update_user($id, $data) {
        // Hash password jika disertakan dalam update
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = $this->hash_password($data['password']);
        }
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Delete user
     */
    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
    
    /**
     * Get all users by role
     */
    public function get_users_by_role($role) {
        $this->db->where('role', $role);
        $this->db->where('status', 'active');
        $query = $this->db->get($this->table);
        
        return $query->result();
    }
    
    /**
     * Update last login
     */
    public function update_last_login($user_id) {
        $data = array(
            'last_login' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $user_id);
        return $this->db->update($this->table, $data);
    }
    
    /**
     * Get available roles
     */
    public function get_available_roles() {
        return array(
            'administration_head' => 'Administration Head',
            'admin_bpkb' => 'Admin BPKB',
            'admin_sales' => 'Admin Sales',
            'operation_manager' => 'Operation Manager',
            'c_level' => 'C-Level Executive'
        );
    }
    
    /**
     * Get role display name
     */
    public function get_role_name($role) {
        $roles = $this->get_available_roles();
        return isset($roles[$role]) ? $roles[$role] : $role;
    }
    
    /**
     * Get users statistics
     */
    public function get_users_stats() {
        $stats = array();
        
        // Count by role
        $this->db->select('role, COUNT(*) as count');
        $this->db->group_by('role');
        $query = $this->db->get($this->table);
        $role_counts = $query->result();
        
        foreach ($role_counts as $role) {
            $stats['by_role'][$role->role] = $role->count;
        }
        
        // Count by status
        $this->db->select('status, COUNT(*) as count');
        $this->db->group_by('status');
        $query = $this->db->get($this->table);
        $status_counts = $query->result();
        
        foreach ($status_counts as $status) {
            $stats['by_status'][$status->status] = $status->count;
        }
        
        // Total users
        $stats['total'] = $this->count_users();
        
        // Recent users (last 30 days)
        $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')));
        $stats['recent'] = $this->db->count_all_results($this->table);
        
        return $stats;
    }
    
    /**
     * Check if username exists
     */
    public function username_exists($username, $exclude_id = null) {
        $this->db->where('username', $username);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        return $this->db->count_all_results($this->table) > 0;
    }
    
    /**
     * Check if email exists
     */
    public function email_exists($email, $exclude_id = null) {
        $this->db->where('email', $email);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        return $this->db->count_all_results($this->table) > 0;
    }
    
    /**
     * Get recent activities (if you have activity log)
     */
    public function get_recent_activities($limit = 10) {
        // This would require an activity log table
        // For now, return recent user registrations
        $this->db->select('id, username, full_name, role, created_at');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    /**
     * Bulk update status
     */
    public function bulk_update_status($user_ids, $status) {
        $this->db->where_in('id', $user_ids);
        return $this->db->update($this->table, array('status' => $status, 'updated_at' => date('Y-m-d H:i:s')));
    }
    
    /**
     * Bulk delete users
     */
    public function bulk_delete($user_ids) {
        $this->db->where_in('id', $user_ids);
        return $this->db->delete($this->table);
    }
}
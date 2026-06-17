<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Helper
 * Helper functions for authentication and authorization
 */

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is logged in
     * @return boolean
     */
    function is_logged_in() {
        $CI =& get_instance();
        return $CI->session->userdata('logged_in') ? true : false;
    }
}

if (!function_exists('get_user_role')) {
    /**
     * Get current user role
     * @return string|null
     */
    function get_user_role() {
        $CI =& get_instance();
        return $CI->session->userdata('role');
    }
}

if (!function_exists('get_user_name')) {
    /**
     * Get current user full name
     * @return string|null
     */
    function get_user_name() {
        $CI =& get_instance();
        return $CI->session->userdata('full_name');
    }
}

if (!function_exists('get_user_id')) {
    /**
     * Get current user ID
     * @return int|null
     */
    function get_user_id() {
        $CI =& get_instance();
        return $CI->session->userdata('user_id');
    }
}

if (!function_exists('has_role')) {
    /**
     * Check if user has specific role
     * @param string $role
     * @return boolean
     */
    function has_role($role) {
        $CI =& get_instance();
        return $CI->session->userdata('role') === $role;
    }
}

if (!function_exists('check_access')) {
    /**
     * Check access and redirect if not authorized
     * @param array $allowed_roles
     * @param string $redirect_url
     */
    function check_access($allowed_roles = array(), $redirect_url = 'auth') {
        $CI =& get_instance();
        
        // Check if logged in
        if (!is_logged_in()) {
            $CI->session->set_flashdata('error', 'Anda harus login terlebih dahulu!');
            redirect($redirect_url);
        }
        
        // Check role access if roles specified
        if (!empty($allowed_roles)) {
            $user_role = get_user_role();
            if (!in_array($user_role, $allowed_roles)) {
                $CI->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman tersebut!');
                redirect('dashboard');
            }
        }
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if user is administration head
     * @return boolean
     */
    function is_admin() {
        return has_role('administration_head');
    }
}

if (!function_exists('is_bpkb_admin')) {
    /**
     * Check if user is BPKB admin
     * @return boolean
     */
    function is_bpkb_admin() {
        return has_role('admin_bpkb');
    }
}

if (!function_exists('is_sales_admin')) {
    /**
     * Check if user is sales admin
     * @return boolean
     */
    function is_sales_admin() {
        return has_role('admin_sales');
    }
}

if (!function_exists('is_operation_manager')) {
    /**
     * Check if user is operation manager
     * @return boolean
     */
    function is_operation_manager() {
        return has_role('operation_manager');
    }
}

if (!function_exists('is_c_level')) {
    /**
     * Check if user is C-level executive
     * @return boolean
     */
    function is_c_level() {
        return has_role('c_level');
    }
}

if (!function_exists('get_role_display_name')) {
    /**
     * Get role display name
     * @param string $role
     * @return string
     */
    function get_role_display_name($role = null) {
        if (!$role) {
            $role = get_user_role();
        }
        
        $roles = array(
            'administration_head' => 'Administration Head',
            'admin_bpkb' => 'Admin BPKB',
            'admin_sales' => 'Admin Sales',
            'operation_manager' => 'Operation Manager',
            'c_level' => 'C-Level Executive'
        );
        
        return isset($roles[$role]) ? $roles[$role] : $role;
    }
}

if (!function_exists('get_dashboard_url')) {
    /**
     * Get dashboard URL based on role
     * @param string $role
     * @return string
     */
    function get_dashboard_url($role = null) {
        if (!$role) {
            $role = get_user_role();
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
}

if (!function_exists('can_access_menu')) {
    /**
     * Check if user can access specific menu
     * @param string $menu_code
     * @return boolean
     */
    function can_access_menu($menu_code) {
        $role = get_user_role();
        
        // Define menu access permissions
        $permissions = array(
            'administration_head' => array('users', 'settings', 'reports', 'backup', 'all'),
            'admin_bpkb' => array('bpkb_register', 'bpkb_search', 'bpkb_update', 'bpkb_print'),
            'admin_sales' => array('sales_transactions', 'customers', 'inventory', 'sales_reports'),
            'operation_manager' => array('tasks', 'logistics', 'warehouse', 'quality'),
            'c_level' => array('analytics', 'financial', 'strategy', 'performance', 'all')
        );
        
        if (!isset($permissions[$role])) {
            return false;
        }
        
        return in_array($menu_code, $permissions[$role]) || in_array('all', $permissions[$role]);
    }
}
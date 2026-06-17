<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kendaraan_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all vehicles
    public function get_all_kendaraan()
    {
        $this->db->order_by('id', 'ASC');
        return $this->db->get('kendaraan')->result();
    }

    // Get vehicle by ID
    public function get_kendaraan_by_id($id)
    {
        return $this->db->get_where('kendaraan', array('id' => $id))->row();
    }

    // Insert new vehicle
    public function insert_kendaraan($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('kendaraan', $data);
    }

    // Update vehicle
    public function update_kendaraan($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('kendaraan', $data);
    }

    // Delete vehicle
    public function delete_kendaraan($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('kendaraan');
    }

    // Get categories for dropdown
    public function get_categories()
    {
        return array(
            'SUV' => 'SUV',
            'Electric' => 'Electric',
            'MPV' => 'MPV',
            'Pickup' => 'Pickup',
            'Sedan' => 'Sedan',
            'Hatchback' => 'Hatchback'
        );
    }
}
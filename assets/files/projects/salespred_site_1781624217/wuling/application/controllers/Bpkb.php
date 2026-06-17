<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bpkb extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Ensure user has access to BPKB module
        $role = $this->session->userdata('role');
        if ($role !== 'admin_bpkb' && $role !== 'administration_head' && $role !== 'operation_manager' && $role !== 'c_level') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman BPKB.');
            redirect('dashboard');
        }
    }

    public function search() {
        // We will use search() as the main index view for BPKB
        $keyword = $this->input->get('q');
        $status = $this->input->get('status');
        $model = $this->input->get('model');
        $type = $this->input->get('type');
        
        // Build query
        $this->db->select('*');
        $this->db->from('transactions');
        
        if ($keyword !== null && $keyword !== '') {
            $this->db->group_start();
            $this->db->like('customer', $keyword);
            $this->db->or_like('inv_no', $keyword);
            $this->db->or_like('chassis', $keyword);
            $this->db->group_end();
        }
        
        if ($status !== null && $status !== '') {
            $this->db->where('do_status', $status);
        }
        
        if ($model !== null && $model !== '') {
            $this->db->where('model', $model);
        }
        
        if ($type !== null && $type !== '') {
            $this->db->where('type', $type);
        }
        
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $data['results'] = $query->result();
        
        // Get unique models for filter
        $this->db->select('model');
        $this->db->distinct();
        $this->db->where('model !=', '');
        $this->db->where('model IS NOT NULL', null, false);
        $data['models'] = $this->db->get('transactions')->result();
        
        // Get unique types for filter
        $this->db->select('type');
        $this->db->distinct();
        $this->db->where('type !=', '');
        $this->db->where('type IS NOT NULL', null, false);
        $data['types'] = $this->db->get('transactions')->result();

        $data['keyword'] = $keyword;
        $data['status'] = $status;
        $data['filter_model'] = $model;
        $data['filter_type'] = $type;
        $data['title'] = 'BPKB Tracking';
        $data['current_uri'] = 'bpkb/search';
        
        $this->load->view('bpkb/search', $data);
    }

    public function create() {
        $data['title'] = 'Tambah Data BPKB';
        $data['current_uri'] = 'bpkb/search';
        $this->load->view('bpkb/form', $data);
    }

    public function store() {
        $this->form_validation->set_rules('inv_no',   'Invoice No', 'required|trim');
        $this->form_validation->set_rules('customer', 'Customer',   'required|trim');
        $this->form_validation->set_rules('chassis',  'Chassis',    'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Data BPKB';
            $data['current_uri'] = 'bpkb/search';
            $this->load->view('bpkb/form', $data);
        } else {
            $insert_data = array(
                'inv_no'       => $this->input->post('inv_no',    TRUE),
                'customer'     => $this->input->post('customer',  TRUE),
                'ktp_no'       => $this->input->post('ktp_no',    TRUE),
                'chassis'      => $this->input->post('chassis',   TRUE),
                'model'        => $this->input->post('model',     TRUE),
                'type'         => $this->input->post('type',      TRUE),
                'do_status'    => (int) $this->input->post('do_status'),
                'sl_date'      => time(),
                'tahun'        => date('Y'),
                'bulan'        => date('F'),
                'hari'         => date('d'),
                'tunai_kredit' => 'Tunai',
                'price_list'   => 0,
                'price_net'    => 0
            );
            $this->Transaction_model->insert_transaction($insert_data);
            $this->session->set_flashdata('success', 'Data BPKB berhasil ditambahkan.');
            redirect('bpkb/search');
        }
    }

    public function edit($id) {
        $transaction = $this->Transaction_model->get_transaction_by_id($id);
        if (empty($transaction)) {
            $this->session->set_flashdata('error', 'Data BPKB tidak ditemukan.');
            redirect('bpkb/search');
            return;
        }
        $data['transaction'] = $transaction;
        $data['title'] = 'Edit Data BPKB';
        $data['current_uri'] = 'bpkb/search';
        $this->load->view('bpkb/form', $data);
    }

    public function update($id) {
        $this->form_validation->set_rules('inv_no',   'Invoice No', 'required|trim');
        $this->form_validation->set_rules('customer', 'Customer',   'required|trim');
        $this->form_validation->set_rules('chassis',  'Chassis',    'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $transaction = $this->Transaction_model->get_transaction_by_id($id);
            if (empty($transaction)) {
                $this->session->set_flashdata('error', 'Data BPKB tidak ditemukan.');
                redirect('bpkb/search');
                return;
            }
            $data['transaction'] = $transaction;
            $data['title'] = 'Edit Data BPKB';
            $data['current_uri'] = 'bpkb/search';
            $this->load->view('bpkb/form', $data);
        } else {
            $update_data = array(
                'inv_no'    => $this->input->post('inv_no',    TRUE),
                'customer'  => $this->input->post('customer',  TRUE),
                'ktp_no'    => $this->input->post('ktp_no',    TRUE),
                'chassis'   => $this->input->post('chassis',   TRUE),
                'model'     => $this->input->post('model',     TRUE),
                'type'      => $this->input->post('type',      TRUE),
                'do_status' => (int) $this->input->post('do_status')
            );
            $this->Transaction_model->update_transaction($id, $update_data);
            $this->session->set_flashdata('success', 'Data BPKB berhasil diupdate.');
            redirect('bpkb/search');
        }
    }

    public function delete($id) {
        $this->Transaction_model->delete_transaction($id);
        $this->session->set_flashdata('success', 'Data BPKB berhasil dihapus.');
        redirect('bpkb/search');
    }

    public function export() {
        require_once APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['A1'=>'No. Invoice', 'B1'=>'Customer', 'C1'=>'KTP No', 'D1'=>'Chassis', 'E1'=>'Model', 'F1'=>'Tipe', 'G1'=>'Status DO'];
        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
        }
        
        $keyword = $this->input->get('q');
        $status = $this->input->get('status');
        $model = $this->input->get('model');
        $type = $this->input->get('type');
        
        $this->db->select('inv_no, customer, ktp_no, chassis, model, type, do_status');
        $this->db->from('transactions');
        if ($keyword !== null && $keyword !== '') {
            $this->db->group_start();
            $this->db->like('customer', $keyword);
            $this->db->or_like('inv_no', $keyword);
            $this->db->or_like('chassis', $keyword);
            $this->db->group_end();
        }
        if ($status !== null && $status !== '') {
            $this->db->where('do_status', $status);
        }
        if ($model !== null && $model !== '') {
            $this->db->where('model', $model);
        }
        if ($type !== null && $type !== '') {
            $this->db->where('type', $type);
        }
        $query = $this->db->get();
        $results = $query->result();
        
        $row = 2;
        foreach ($results as $res) {
            $sheet->setCellValue('A'.$row, $res->inv_no);
            $sheet->setCellValue('B'.$row, $res->customer);
            $sheet->setCellValue('C'.$row, $res->ktp_no);
            $sheet->setCellValue('D'.$row, $res->chassis);
            $sheet->setCellValue('E'.$row, $res->model);
            $sheet->setCellValue('F'.$row, $res->type);
            $sheet->setCellValue('G'.$row, $res->do_status == 1 ? 'Delivered' : 'Pending');
            $row++;
        }
        
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        $filename = 'BPKB_Tracking_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function import() {
        $data['title'] = 'Import Data BPKB';
        $data['current_uri'] = 'bpkb/search';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_excel'])) {
            require_once APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php';
            
            $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            
            if(isset($_FILES['file_excel']['name']) && in_array($_FILES['file_excel']['type'], $file_mimes)) {
                $arr_file = explode('.', $_FILES['file_excel']['name']);
                $extension = end($arr_file);
                
                if('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else if('xls' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                
                $spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                
                $batch_data = [];
                for($i = 1; $i < count($sheetData); $i++) {
                    // Assuming columns: A:Invoice, B:Customer, C:KTP, D:Chassis, E:Model, F:Type, G:Status DO
                    if(!empty($sheetData[$i][0])) { // Check if Invoice No is not empty
                        $batch_data[] = array(
                            'inv_no' => $sheetData[$i][0],
                            'customer' => $sheetData[$i][1],
                            'ktp_no' => $sheetData[$i][2],
                            'chassis' => $sheetData[$i][3],
                            'model' => $sheetData[$i][4],
                            'type' => $sheetData[$i][5],
                            'do_status' => (strtolower(trim($sheetData[$i][6] ?? '')) == 'delivered' || $sheetData[$i][6] == '1') ? 1 : 0,
                            'sl_date' => time(),
                            'tahun' => date('Y'),
                            'bulan' => date('F'),
                            'hari' => date('d'),
                            'tunai_kredit' => 'Tunai',
                            'price_list' => 0,
                            'price_net' => 0
                        );
                    }
                }
                
                if (!empty($batch_data)) {
                    $this->db->insert_batch('transactions', $batch_data);
                    $this->session->set_flashdata('success', 'Data BPKB berhasil diimport.');
                } else {
                    $this->session->set_flashdata('error', 'File kosong atau format salah.');
                }
                redirect('bpkb/search');
            } else {
                $this->session->set_flashdata('error', 'Format file tidak didukung.');
            }
        }
        
        $this->load->view('bpkb/import', $data);
    }
}

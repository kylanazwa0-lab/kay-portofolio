<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['transactions'] = $this->Transaction_model->get_all_transactions();
        $data['title'] = 'Transaction List';
        $this->load->view('transaction/Transactions_view', $data);
    }

    public function Transactions_form() {
        $data['title'] = 'Add Transaction';
        $data['sales_users'] = $this->Transaction_model->get_sales_users();
        $data['spv_users'] = $this->Transaction_model->get_spv_users();
        $this->load->view('transaction/Transactions_form', $data);
    }

    public function add() {
        $data['title'] = 'Add Transaction';
        $data['sales_users'] = $this->Transaction_model->get_sales_users();
        $data['spv_users'] = $this->Transaction_model->get_spv_users();
        $this->load->view('transaction/Transactions_form', $data);
    }

    public function save() {
        $this->form_validation->set_rules('sl_date', 'SL Date', 'required');
        $this->form_validation->set_rules('customer', 'Customer', 'required');
        $this->form_validation->set_rules('cust_phone', 'Customer Phone', 'required');
        $this->form_validation->set_rules('tunai_kredit', 'Tunai/Kredit', 'required');
        $this->form_validation->set_rules('sales_name', 'Sales Name', 'required');
        $this->form_validation->set_rules('spv', 'SPV', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Add Transaction';
            $data['sales_users'] = $this->Transaction_model->get_sales_users();
            $data['spv_users'] = $this->Transaction_model->get_spv_users();
            $this->load->view('transaction/Transactions_form', $data);
        } else {
            $data = array(
                'sl_date' => $this->input->post('sl_date'),
                'customer' => $this->input->post('customer'),
                'alamat' => $this->input->post('alamat'),
                'cust_phone' => $this->input->post('cust_phone'),
                'sales_name' => $this->input->post('sales_name'),
                'spv' => $this->input->post('spv'),
                'leasing' => $this->input->post('leasing'),
                'insurance' => $this->input->post('insurance'),
                'inv_no' => $this->input->post('inv_no'),
                'code' => $this->input->post('code'),
                'type' => $this->input->post('type'),
                'chassis' => $this->input->post('chassis'),
                'price_list' => $this->input->post('price_list'),
                'discount' => $this->input->post('discount'),
                'price_net' => $this->input->post('price_net'),
                'dp_amt' => $this->input->post('dp_amt'),
                'leasing_amt' => $this->input->post('leasing_amt'),
                'description_2' => $this->input->post('description_2'),
                'tenor' => $this->input->post('tenor'),
                'ktp_no' => $this->input->post('ktp_no'),
                'do_status' => $this->input->post('do_status'),
                'tunai_kredit' => $this->input->post('tunai_kredit'),
                'model' => $this->input->post('model'),
                'hari' => $this->input->post('hari'),
                'bulan' => $this->input->post('bulan'),
                'tahun' => $this->input->post('tahun')
            );
            $this->Transaction_model->insert_transaction($data);
            redirect('transaction');
        }
    }

    public function edit($id) {
        $data['transaction'] = $this->Transaction_model->get_transaction_by_id($id);
        $data['title'] = 'Edit Transaction';
        $data['sales_users'] = $this->Transaction_model->get_sales_users();
        $data['spv_users'] = $this->Transaction_model->get_spv_users();
        $this->load->view('transaction/edit', $data);
    }

    public function update($id) {
        $this->form_validation->set_rules('sl_date', 'SL Date', 'required');
        $this->form_validation->set_rules('customer', 'Customer', 'required');
        $this->form_validation->set_rules('cust_phone', 'Customer Phone', 'required');
        $this->form_validation->set_rules('tunai_kredit', 'Tunai/Kredit', 'required');
        $this->form_validation->set_rules('sales_name', 'Sales Name', 'required');
        $this->form_validation->set_rules('spv', 'SPV', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['transaction'] = $this->Transaction_model->get_transaction_by_id($id);
            $data['title'] = 'Edit Transaction';
            $data['sales_users'] = $this->Transaction_model->get_sales_users();
            $data['spv_users'] = $this->Transaction_model->get_spv_users();
            $this->load->view('transaction/edit', $data);
        } else {
            $data = array(
                'sl_date' => $this->input->post('sl_date'),
                'customer' => $this->input->post('customer'),
                'alamat' => $this->input->post('alamat'),
                'cust_phone' => $this->input->post('cust_phone'),
                'sales_name' => $this->input->post('sales_name'),
                'spv' => $this->input->post('spv'),
                'leasing' => $this->input->post('leasing'),
                'insurance' => $this->input->post('insurance'),
                'inv_no' => $this->input->post('inv_no'),
                'code' => $this->input->post('code'),
                'type' => $this->input->post('type'),
                'chassis' => $this->input->post('chassis'),
                'price_list' => $this->input->post('price_list'),
                'discount' => $this->input->post('discount'),
                'price_net' => $this->input->post('price_net'),
                'dp_amt' => $this->input->post('dp_amt'),
                'leasing_amt' => $this->input->post('leasing_amt'),
                'description_2' => $this->input->post('description_2'),
                'tenor' => $this->input->post('tenor'),
                'ktp_no' => $this->input->post('ktp_no'),
                'do_status' => $this->input->post('do_status'),
                'tunai_kredit' => $this->input->post('tunai_kredit'),
                'model' => $this->input->post('model'),
                'hari' => $this->input->post('hari'),
                'bulan' => $this->input->post('bulan'),
                'tahun' => $this->input->post('tahun')
            );
            $this->Transaction_model->update_transaction($id, $data);
            redirect('transaction');
        }
    }

    public function delete($id) {
        $this->Transaction_model->delete_transaction($id);
        redirect('transaction');
    }

    public function export_excel()
    {
        // Load PHPSpreadsheet
        require_once APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $headers = [
            'A1' => 'ID',
            'B1' => 'SL Date',
            'C1' => 'Tahun',
            'D1' => 'Bulan',
            'E1' => 'Hari',
            'F1' => 'Customer',
            'G1' => 'Alamat',
            'H1' => 'No. Telepon',
            'I1' => 'Sales Name',
            'J1' => 'Supervisor',
            'K1' => 'Leasing',
            'L1' => 'Insurance',
            'M1' => 'Invoice No',
            'N1' => 'Code',
            'O1' => 'Type',
            'P1' => 'Chassis',
            'Q1' => 'Price List',
            'R1' => 'Discount',
            'S1' => 'Price Net',
            'T1' => 'DP Amount',
            'U1' => 'Leasing Amount',
            'V1' => 'Description',
            'W1' => 'Tenor',
            'X1' => 'KTP No',
            'Y1' => 'DO Status',
            'Z1' => 'Tunai/Kredit',
            'AA1' => 'Model'
        ];
        
        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
        }
        
        // Style headers
        $sheet->getStyle('A1:AA1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E2E2']
            ]
        ]);
        
        // Get data
        $transactions = $this->Transaction_model->get_all();
        $row = 2;
        
        foreach ($transactions as $transaction) {
            $sheet->setCellValue('A' . $row, $transaction->id);
            $sheet->setCellValue('B' . $row, $transaction->sl_date);
            $sheet->setCellValue('C' . $row, $transaction->tahun);
            $sheet->setCellValue('D' . $row, $transaction->bulan);
            $sheet->setCellValue('E' . $row, $transaction->hari);
            $sheet->setCellValue('F' . $row, $transaction->customer);
            $sheet->setCellValue('G' . $row, $transaction->alamat);
            $sheet->setCellValue('H' . $row, $transaction->cust_phone);
            $sheet->setCellValue('I' . $row, $transaction->sales_name);
            $sheet->setCellValue('J' . $row, $transaction->spv);
            $sheet->setCellValue('K' . $row, $transaction->leasing);
            $sheet->setCellValue('L' . $row, $transaction->insurance);
            $sheet->setCellValue('M' . $row, $transaction->inv_no);
            $sheet->setCellValue('N' . $row, $transaction->code);
            $sheet->setCellValue('O' . $row, $transaction->type);
            $sheet->setCellValue('P' . $row, $transaction->chassis);
            $sheet->setCellValue('Q' . $row, $transaction->price_list);
            $sheet->setCellValue('R' . $row, $transaction->discount);
            $sheet->setCellValue('S' . $row, $transaction->price_net);
            $sheet->setCellValue('T' . $row, $transaction->dp_amt);
            $sheet->setCellValue('U' . $row, $transaction->leasing_amt);
            $sheet->setCellValue('V' . $row, $transaction->description_2);
            $sheet->setCellValue('W' . $row, $transaction->tenor);
            $sheet->setCellValue('X' . $row, $transaction->ktp_no);
            $sheet->setCellValue('Y' . $row, $transaction->do_status);
            $sheet->setCellValue('Z' . $row, $transaction->tunai_kredit);
            $sheet->setCellValue('AA' . $row, $transaction->model);
            $row++;
        }
        
        // Auto-resize columns
        foreach (range('A', 'AA') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'transactions_export_' . date('YmdHis') . '.xlsx';
        
        // Output file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
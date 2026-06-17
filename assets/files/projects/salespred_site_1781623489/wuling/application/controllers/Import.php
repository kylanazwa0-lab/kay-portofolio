<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_model');
        $this->load->helper(['form', 'url']);
        $this->load->library(['upload']);
        $this->load->library('excel'); // pastikan PHPExcel sudah di-load
    }

    public function index() {
        $data['title'] = 'Import Transactions (Excel)';
        $this->load->view('import_view', $data);
    }

    public function upload_excel() {
        // Konfigurasi upload
        $config['upload_path']   = APPPATH . 'uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size']      = 10000; // 10MB

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('excel_file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('import');
        } else {
            $file_data = $this->upload->data();
            $file_path = $file_data['full_path'];

            try {
                $object = PHPExcel_IOFactory::load($file_path);

                $batch_data = [];
                $error_messages = [];
                $row_index = 2; // baris mulai (misalnya 2 jika baris 1 header)

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();

                    for ($row = $row_index; $row <= $highestRow; $row++) {
   $customer     = $worksheet->getCellByColumnAndRow(3, $row)->getValue();  // Customer
$inv_no       = $worksheet->getCellByColumnAndRow(10, $row)->getValue(); // Inv. No.
$chassis      = $worksheet->getCellByColumnAndRow(13, $row)->getValue(); // Chassis
$tunai_kredit = $worksheet->getCellByColumnAndRow(23, $row)->getValue(); // Tunai/Kredit
$model        = $worksheet->getCellByColumnAndRow(24, $row)->getValue(); // Model
$price_net    = $worksheet->getCellByColumnAndRow(16, $row)->getValue(); // Price Net
$dp_amt       = $worksheet->getCellByColumnAndRow(17, $row)->getValue(); // DP Amt
$leasing_amt  = $worksheet->getCellByColumnAndRow(18, $row)->getValue(); // Leasing Amt





                        // Validasi field wajib
                        if (!empty($customer) && !empty($inv_no) && !empty($chassis)) {
                            if (!in_array($tunai_kredit, ['Tunai', 'Kredit'])) {
                                $error_messages[] = "Baris $row: tunai_kredit tidak valid.";
                                continue;
                            }

                            $batch_data[] = [
                                'customer'      => $customer,
                                'inv_no'        => $inv_no,
                                'chassis'       => $chassis,
                                'tunai_kredit'  => $tunai_kredit,
                                'model'         => $model,
                                'created_at'    => date('Y-m-d H:i:s')
                            ];
                        } else {
                            $error_messages[] = "Baris $row: Field wajib kosong.";
                        }
                    }
                }

                // Insert batch
                $success_count = 0;
                if (!empty($batch_data)) {
                    if ($this->Transaction_model->insert_batch($batch_data)) {
                        $success_count = count($batch_data);
                    } else {
                        $error_messages[] = "Gagal menyisipkan data batch.";
                    }
                }

                // Hapus file
                unlink($file_path);

                // Pesan feedback
                $message = "Berhasil mengimpor $success_count data.";
                if (!empty($error_messages)) {
                    $message .= "<br>Error:<br>" . implode('<br>', $error_messages);
                }

                $this->session->set_flashdata('success', $message);
                redirect('import');

            } catch (Exception $e) {
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                $this->session->set_flashdata('error', 'Error memproses file: ' . $e->getMessage());
                redirect('import');
            }
        }
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kendaraan extends CI_Controller
{
    const ROLE_BPKB_ADMIN = 'admin_bpkb';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kendaraan_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'session'));

        // Wajib login
        if (!$this->session->userdata('logged_in')) {
            // simpan tujuan agar bisa balik lagi
            $this->session->set_flashdata('next', current_url());
            redirect('auth/login');
        }

        // Pesan error form rapi
        $this->form_validation->set_error_delimiters('<div class="text-danger small">', '</div>');
    }

    // List kendaraan (semua yang login bisa lihat)
    public function index()
    {
        $data['kendaraan'] = $this->Kendaraan_model->get_all_kendaraan();

        $user_role = (string) $this->session->userdata('role');
        $privileged = ['administration_head', 'operation_manager', 'c_level'];

        // Semua role yang login bisa tambah & edit
        $data['can_edit']   = true;
        // Hanya 3 role khusus yang bisa hapus
        $data['can_delete'] = in_array($user_role, $privileged, true);

        $this->load->view('kendaraan/index', $data);
    }

    // Form tambah
    public function tambah()
    {
        $this->check_crud_access();

        $data['categories'] = $this->Kendaraan_model->get_categories();
        $this->load->view('kendaraan/tambah', $data);
    }

    // Proses tambah
    public function proses_tambah()
    {
        $this->check_crud_access();

        $categories = (array) $this->Kendaraan_model->get_categories();
        $category_names = array_map(function($c){
            // dukung baik array assoc atau numeric
            return is_array($c) ? ($c['name'] ?? $c['category'] ?? $c[0] ?? '') : (string)$c;
        }, $categories);
        $category_names = array_filter(array_unique($category_names));

        $this->form_validation->set_rules('model_name', 'Nama Model', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('category', 'Kategori', 'trim|required|in_list['.implode(',', $category_names).']');
        $this->form_validation->set_rules('price', 'Harga', 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required|min_length[5]');

        if ($this->form_validation->run() === FALSE) {
            $data['categories'] = $categories;
            $this->load->view('kendaraan/tambah', $data);
            return;
        }

        $data = array(
            'model_name'  => $this->input->post('model_name', TRUE),
            'category'    => $this->input->post('category', TRUE),
            'price'       => (float) $this->input->post('price', TRUE),
            'description' => $this->input->post('description', TRUE),
        );

        if ($this->Kendaraan_model->insert_kendaraan($data)) {
            $this->session->set_flashdata('success', 'Data kendaraan berhasil ditambahkan!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data kendaraan!');
        }
        redirect('kendaraan');
    }

    // Form edit
    public function edit($id = null)
    {
        $this->check_crud_access();

        $id = $this->sanitize_id($id);

        $kendaraan = $this->Kendaraan_model->get_kendaraan_by_id($id);
        if (empty($kendaraan)) {
            show_404();
        }

        $data['kendaraan'] = $kendaraan;
        $data['categories'] = $this->Kendaraan_model->get_categories();
        $this->load->view('kendaraan/edit', $data);
    }

    // Proses edit
    public function proses_edit($id = null)
    {
        $this->check_crud_access();

        $id = $this->sanitize_id($id);

        $kendaraan = $this->Kendaraan_model->get_kendaraan_by_id($id);
        if (empty($kendaraan)) {
            show_404();
        }

        $categories = (array) $this->Kendaraan_model->get_categories();
        $category_names = array_map(function($c){
            return is_array($c) ? ($c['name'] ?? $c['category'] ?? $c[0] ?? '') : (string)$c;
        }, $categories);
        $category_names = array_filter(array_unique($category_names));

        $this->form_validation->set_rules('model_name', 'Nama Model', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('category', 'Kategori', 'trim|required|in_list['.implode(',', $category_names).']');
        $this->form_validation->set_rules('price', 'Harga', 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|required|min_length[5]');

        if ($this->form_validation->run() === FALSE) {
            $data['kendaraan']  = $kendaraan;
            $data['categories'] = $categories;
            $this->load->view('kendaraan/edit', $data);
            return;
        }

        $data = array(
            'model_name'  => $this->input->post('model_name', TRUE),
            'category'    => $this->input->post('category', TRUE),
            'price'       => (float) $this->input->post('price', TRUE),
            'description' => $this->input->post('description', TRUE),
        );

        if ($this->Kendaraan_model->update_kendaraan($id, $data)) {
            $this->session->set_flashdata('success', 'Data kendaraan berhasil diupdate!');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate data kendaraan!');
        }
        redirect('kendaraan');
    }

    // Hapus - HARUS POST (hindari CSRF/accidental GET)
    public function hapus($id = null)
    {
        $this->check_delete_access();

        if (strtoupper($this->input->method(TRUE)) !== 'POST') {
            $this->output->set_status_header(405); // Method Not Allowed
            echo 'Metode tidak diizinkan.';
            return;
        }

        $id = $this->sanitize_id($id);

        $kendaraan = $this->Kendaraan_model->get_kendaraan_by_id($id);
        if (empty($kendaraan)) {
            show_404();
        }

        if ($this->Kendaraan_model->delete_kendaraan($id)) {
            $this->session->set_flashdata('success', 'Data kendaraan berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data kendaraan!');
        }
        redirect('kendaraan');
    }

    // Detail - semua role bisa
    public function detail($id = null)
    {
        $id = $this->sanitize_id($id);

        $kendaraan = $this->Kendaraan_model->get_kendaraan_by_id($id);
        if (empty($kendaraan)) {
            show_404();
        }

        $data['kendaraan'] = $kendaraan;
        $this->load->view('kendaraan/detail', $data);
    }

    private function check_crud_access(): void
    {
        // Semua role yang sudah login boleh tambah & edit
        // (login sudah dicek di __construct)
    }

    private function check_delete_access(): void
    {
        $user_role = (string) $this->session->userdata('role');
        $allowed = ['administration_head', 'operation_manager', 'c_level'];
        if (!in_array($user_role, $allowed, true)) {
            $this->session->set_flashdata('error', 'Akses ditolak! Hanya Administration Head, Operation Manager, dan C-Level yang memiliki hak untuk menghapus data.');
            redirect('kendaraan');
        }
    }

    // Fitur Halaman Import Kendaraan
    public function import_page()
    {
        $this->check_crud_access();
        $this->load->view('kendaraan/import_excel');
    }

    // Fitur Download Template Excel
    public function download_template()
    {
        $this->check_crud_access();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'Model');
        $sheet->setCellValue('B1', 'Kategori');
        $sheet->setCellValue('C1', 'Harga');
        $sheet->setCellValue('D1', 'Deskripsi');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFED1C24']],
        ];
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Template_Import_Kendaraan.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    // Fitur Import Excel
    public function import()
    {
        $this->check_crud_access();

        if (isset($_FILES['file_import']['name']) && $_FILES['file_import']['name'] != "") {
            $file_tmp = $_FILES['file_import']['tmp_name'];
            $file_ext = strtolower(pathinfo($_FILES['file_import']['name'], PATHINFO_EXTENSION));

            if (in_array($file_ext, ['xls', 'xlsx'])) {
                try {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_tmp);
                    $sheet = $spreadsheet->getActiveSheet();
                    $dataRow = $sheet->toArray();
                    
                    $inserted = 0;
                    $is_header = true;

                    foreach ($dataRow as $data) {
                        if ($is_header) {
                            $is_header = false;
                            continue;
                        }

                        // Mapping: Model(0), Kategori(1), Harga(2), Deskripsi(3)
                        if (isset($data[0]) && trim($data[0]) != "") {
                            $insert_data = array(
                                'model_name'  => trim($data[0]),
                                'category'    => isset($data[1]) ? trim($data[1]) : 'Lainnya',
                                'price'       => isset($data[2]) ? (float) str_replace(['Rp', '.', ',', ' '], '', $data[2]) : 0,
                                'description' => isset($data[3]) ? trim($data[3]) : ''
                            );

                            if ($this->Kendaraan_model->insert_kendaraan($insert_data)) {
                                $inserted++;
                            }
                        }
                    }

                    $this->session->set_flashdata('success', $inserted . ' data kendaraan berhasil diimport dari Excel!');
                } catch (Exception $e) {
                    $this->session->set_flashdata('error', 'Error membaca file Excel: ' . $e->getMessage());
                }
            } else {
                $this->session->set_flashdata('error', 'Format file tidak didukung! Harap unggah file .xlsx atau .xls.');
            }
        } else {
            $this->session->set_flashdata('error', 'Tidak ada file yang diunggah!');
        }

        redirect('kendaraan');
    }

    private function sanitize_id($id): int
    {
        if (!ctype_digit((string)$id)) {
            show_404();
        }
        return (int) $id;
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Report_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('form_validation');
    }

    public function index() {
        $month = $this->input->get('month');
        $year = $this->input->get('year');
        
        // Get data based on filters
        $data['transactions'] = $this->Report_model->get_transactions($month, $year);
        $data['summary'] = $this->Report_model->get_summary($month, $year);
       $data['sales_summary'] = $this->Report_model->get_sales_summary($month, $year);
       $data['model_summary'] = $this->Report_model->get_model_summary($month, $year);
        
        // Get filter options
         $data['years'] = $this->Report_model->get_years();
        $data['months'] = $this->Report_model->get_months();
        
        // Current filter values
        $data['selected_month'] = $month;
        $data['selected_year'] = $year;
        
        $this->load->view('report/transaction_report', $data);
    }

    public function export_excel() {
        $month = $this->input->get('month');
        $year = $this->input->get('year');
        
        $transactions = $this->Report_model->get_transactions($month, $year);
        $summary = $this->Report_model->get_summary($month, $year);
        
        $month_text = empty($month) ? 'Semua Bulan' : date('F', mktime(0, 0, 0, $month, 10));
        $year_text = empty($year) ? 'Semua Tahun' : $year;
        
        $filename = 'Laporan_Transaksi_Wuling_' . date('Ymd_His') . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<html><head><style>';
        echo 'table { border-collapse: collapse; width: 100%; font-family: sans-serif; }';
        echo 'th, td { border: 1px solid #dddddd; padding: 8px; text-align: left; }';
        echo 'th { background-color: #f2f2f2; font-weight: bold; }';
        echo '</style></head><body>';
        
        echo '<h2>Laporan Transaksi Wuling</h2>';
        echo '<p>Periode: ' . $month_text . ' ' . $year_text . '<br>Diekspor pada: ' . date('d M Y H:i') . '</p>';

        if ($summary) {
            echo '<table>';
            echo '<tr><th colspan="2">Ringkasan Pendapatan</th></tr>';
            echo '<tr><td>Total Transaksi</td><td>' . number_format($summary->total_transactions, 0, ',', '.') . '</td></tr>';
            echo '<tr><td>Total Harga List</td><td>Rp ' . number_format($summary->total_price_list, 0, ',', '.') . '</td></tr>';
            echo '<tr><td>Total Diskon</td><td>Rp ' . number_format($summary->total_discount, 0, ',', '.') . '</td></tr>';
            echo '<tr><td>Total Pendapatan Bersih (Net)</td><td>Rp ' . number_format($summary->total_price_net, 0, ',', '.') . '</td></tr>';
            echo '</table><br><br>';
        }

        echo '<table>';
        echo '<tr>';
        echo '<th width="50">No</th>';
        echo '<th width="120">Tanggal</th>';
        echo '<th width="150">Customer</th>';
        echo '<th width="150">Sales</th>';
        echo '<th width="150">Model</th>';
        echo '<th width="150">Tipe</th>';
        echo '<th width="120">Tunai/Kredit</th>';
        echo '<th width="130">Harga List</th>';
        echo '<th width="100">Diskon</th>';
        echo '<th width="130">Harga Net</th>';
        echo '<th width="130">DP</th>';
        echo '<th width="130">Leasing</th>';
        echo '<th width="100">Status</th>';
        echo '</tr>';

        if (!empty($transactions)) {
            $no = 1;
            foreach ($transactions as $t) {
                echo '<tr>';
                echo '<td>' . $no++ . '</td>';
                echo '<td>' . $t->hari . ' ' . $t->bulan . ' ' . $t->tahun . '</td>';
                echo '<td>' . $t->customer . '</td>';
                echo '<td>' . $t->sales_name . '</td>';
                echo '<td>' . $t->model . '</td>';
                echo '<td>' . $t->type . '</td>';
                echo '<td>' . $t->tunai_kredit . '</td>';
                echo '<td>Rp ' . number_format($t->price_list, 0, ',', '.') . '</td>';
                echo '<td>Rp ' . number_format($t->discount, 0, ',', '.') . '</td>';
                echo '<td>Rp ' . number_format($t->price_net, 0, ',', '.') . '</td>';
                echo '<td>Rp ' . number_format($t->dp_amt, 0, ',', '.') . '</td>';
                echo '<td>Rp ' . number_format($t->leasing_amt, 0, ',', '.') . '</td>';
                echo '<td>' . ($t->do_status == 1 ? 'Selesai' : 'Pending') . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="13" align="center">Tidak ada transaksi pada periode ini.</td></tr>';
        }
        
        echo '</table>';
        echo '</body></html>';
        exit;
    }

    public function print_report() {
        $month = $this->input->get('month');
        $year = $this->input->get('year');
        
        $data['transactions'] = $this->Report_model->get_transactions($month, $year);
        $data['summary'] = $this->Report_model->get_summary($month, $year);
        $data['selected_month'] = $month;
        $data['selected_year'] = $year;
        
        $this->load->view('report/print_report', $data);
    }

    public function download_template() {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['Tanggal (YYYY-MM-DD)', 'Customer', 'Sales', 'Model', 'Tipe', 'Tunai/Kredit', 'Harga List', 'Diskon', 'Harga Net', 'DP', 'Leasing', 'Status (Selesai/Pending)'];
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFED1C24']],
        ];
        $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Template_Import_Transaksi.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function import_page() {
        $data['page_title'] = 'Import Data Transaksi';
        $this->load->view('report/import_excel', $data);
    }

    public function import_excel() {
        if (isset($_FILES['file_import']['name']) && $_FILES['file_import']['name'] != "") {
            $file_tmp = $_FILES['file_import']['tmp_name'];
            $file_ext = strtolower(pathinfo($_FILES['file_import']['name'], PATHINFO_EXTENSION));

            if (in_array($file_ext, ['xls', 'xlsx'])) {
                $this->load->model('Transaction_model');
                try {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_tmp);
                    $sheet = $spreadsheet->getActiveSheet();
                    $dataRow = $sheet->toArray();

                    $is_header = true;
                    $inserted = 0;

                    foreach ($dataRow as $data) {
                        if ($is_header) {
                            $is_header = false;
                            continue;
                        }

                        // Format: Tanggal(0), Customer(1), Sales(2), Model(3), Tipe(4), Tunai/Kredit(5), Harga List(6), Diskon(7), Harga Net(8), DP(9), Leasing(10), Status(11)
                        if (isset($data[0]) && trim($data[0]) != "") {
                            $sl_date = trim($data[0]);
                            $time = strtotime($sl_date);
                            $insert_data = array(
                                'sl_date' => $sl_date,
                                'customer' => isset($data[1]) ? trim($data[1]) : '',
                                'sales_name' => isset($data[2]) ? trim($data[2]) : '',
                                'model' => isset($data[3]) ? trim($data[3]) : '',
                                'type' => isset($data[4]) ? trim($data[4]) : '',
                                'tunai_kredit' => isset($data[5]) ? trim($data[5]) : '',
                                'price_list' => isset($data[6]) ? (float) str_replace(['Rp', '.', ',', ' '], '', $data[6]) : 0,
                                'discount' => isset($data[7]) ? (float) str_replace(['Rp', '.', ',', ' '], '', $data[7]) : 0,
                                'price_net' => isset($data[8]) ? (float) str_replace(['Rp', '.', ',', ' '], '', $data[8]) : 0,
                                'dp_amt' => isset($data[9]) ? (float) str_replace(['Rp', '.', ',', ' '], '', $data[9]) : 0,
                                'leasing_amt' => isset($data[10]) ? (float) str_replace(['Rp', '.', ',', ' '], '', $data[10]) : 0,
                                'do_status' => (isset($data[11]) && strtolower(trim($data[11])) == 'selesai') ? 1 : 0,
                                'hari' => $time ? date('d', $time) : '',
                                'bulan' => $time ? date('m', $time) : '',
                                'tahun' => $time ? date('Y', $time) : ''
                            );
                            
                            $this->Transaction_model->insert_transaction($insert_data);
                            $inserted++;
                        }
                    }
                    $this->session->set_flashdata('success', $inserted . ' data transaksi berhasil diimport dari Excel!');
                } catch (Exception $e) {
                    $this->session->set_flashdata('error', 'Error membaca file Excel: ' . $e->getMessage());
                }
            } else {
                $this->session->set_flashdata('error', 'Format file tidak didukung! Harap unggah file .xlsx atau .xls');
            }
        } else {
            $this->session->set_flashdata('error', 'Tidak ada file yang diunggah!');
        }
        redirect('report');
    }
}
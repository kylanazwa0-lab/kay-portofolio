<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load PHPExcel
require_once APPPATH . 'third_party/PHPExcel.php';

/**
 * Excel Library untuk CodeIgniter
 * Wrapper class untuk PHPExcel
 */
class Excel extends PHPExcel 
{
    public function __construct()
    {
        parent::__construct();
    }
}

/* End of file Excel.php */
/* Location: ./application/libraries/Excel.php */
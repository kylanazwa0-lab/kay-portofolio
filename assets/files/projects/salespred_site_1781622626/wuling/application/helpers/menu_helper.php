<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_menu_items')) {
    function get_menu_items($role) {
        switch ($role) {
            case 'administration_head':
                return [
                    ['icon' => 'fa-users', 'title' => 'Kelola Pengguna', 'url' => 'users', 'desc' => 'Kelola pengguna sistem'],
                    ['icon' => 'fa-file', 'title' => 'Laporan', 'url' => 'report', 'desc' => 'Halaman untuk laporan transaksi'],
                    ['icon' => 'fa-shopping-cart', 'title' => 'Transaksi', 'url' => 'transaction', 'desc' => 'Data transaksi penjualan'],
                    ['icon' => 'fa-chart-bar', 'title' => 'Prediksi', 'url' => 'prediction', 'desc' => 'Analisis prediksi penjualan']
                ];
            case 'admin_bpkb':
                return [
                    ['icon' => 'fa-file-alt', 'title' => 'Kelola Kendaraan', 'url' => 'kendaraan', 'desc' => 'Registrasi BPKB baru'],
                    ['icon' => 'fa-search', 'title' => 'BPKB Search', 'url' => 'bpkb/search', 'desc' => 'Pencarian status BPKB'],
                    ['icon' => 'fa-shopping-cart', 'title' => 'Reports', 'url' => 'transaction', 'desc' => 'Transaksi penjualan'],
                    ['icon' => 'fa-file', 'title' => 'Laporan', 'url' => 'report', 'desc' => 'Halaman untuk laporan transaksi']
                ];
            case 'admin_sales':
                return [
                    ['icon' => 'fa-shopping-cart', 'title' => 'Reports', 'url' => 'transaction', 'desc' => 'Transaksi penjualan'],
                    ['icon' => 'fa-car', 'title' => 'Vehicle Inventory', 'url' => 'kendaraan', 'desc' => 'Inventori kendaraan']
                ];
            case 'operation_manager':
            case 'c_level':
                return [
                    ['icon' => 'fa-chart-bar', 'title' => 'Prediksi', 'url' => 'prediction', 'desc' => 'Prediksi transaksi'],
                    ['icon' => 'fa-file', 'title' => 'Laporan', 'url' => 'report', 'desc' => 'Halaman untuk laporan transaksi'],
                    ['icon' => 'fa-car', 'title' => 'Vehicle Inventory', 'url' => 'kendaraan', 'desc' => 'Inventori kendaraan'],
                    ['icon' => 'fa-shopping-cart', 'title' => 'Reports', 'url' => 'transaction', 'desc' => 'Transaksi penjualan']
                ];
            default:
                return [];
        }
    }
}

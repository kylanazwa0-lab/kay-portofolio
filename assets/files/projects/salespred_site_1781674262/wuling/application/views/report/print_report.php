<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .filter-info {
            margin-bottom: 20px;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
        }
        
        .summary-section {
            margin-bottom: 20px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .summary-item {
            background: #f8f9fa;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .summary-value {
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }
        
        .summary-label {
            font-size: 11px;
            color: #666;
            margin-top: 3px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        
        th {
            background-color: #333;
            color: white;
            font-weight: bold;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-primary {
            background-color: #007bff;
            color: white;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: black;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
        
        @media print {
            body {
                margin: 0;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .print-button {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .btn-print {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-print:hover {
            background-color: #0056b3;
        }
    </style>

    <script>if(localStorage.getItem('theme') === 'light') document.documentElement.setAttribute('data-theme', 'light');</script>
</head>
<body>
    <div class="no-print print-button">
        <button onclick="window.print()" class="btn-print">
            📄 Cetak Laporan
        </button>
        <button onclick="window.close()" class="btn-print" style="background-color: #6c757d;">
            ✖ Tutup
        </button>
    </div>

    <div class="header">
        <div class="company-name">WULING MOTORS</div>
        <div class="report-title">LAPORAN TRANSAKSI PENJUALAN</div>
        <div>
            Periode: 
            <?php if ($selected_month && $selected_year): ?>
                <?= $selected_month ?> <?= $selected_year ?>
            <?php elseif ($selected_month): ?>
                <?= $selected_month ?> (Semua Tahun)
            <?php elseif ($selected_year): ?>
                <?= $selected_year ?> (Semua Bulan)
            <?php else: ?>
                Semua Periode
            <?php endif; ?>
        </div>
        <div>Tanggal Cetak: <?= date('d F Y H:i') ?></div>
    </div>

    <?php if ($selected_month || $selected_year): ?>
    <div class="filter-info">
        <strong>Filter yang Digunakan:</strong>
        <?php if ($selected_month): ?>
            Bulan: <?= $selected_month ?>
        <?php endif; ?>
        <?php if ($selected_year): ?>
            <?= $selected_month ? ', ' : '' ?>Tahun: <?= $selected_year ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="summary-section">
        <h3>Ringkasan</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-value"><?= number_format($summary->total_transactions) ?></div>
                <div class="summary-label">Total Transaksi</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">Rp <?= number_format($summary->total_price_net, 0, ',', '.') ?></div>
                <div class="summary-label">Total Pendapatan</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">Rp <?= number_format($summary->total_discount, 0, ',', '.') ?></div>
                <div class="summary-label">Total Diskon</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">Rp <?= number_format($summary->total_dp, 0, ',', '.') ?></div>
                <div class="summary-label">Total DP</div>
            </div>
        </div>
    </div>

    <h3>Detail Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Customer</th>
                <th>Sales</th>
                <th>Model</th>
                <th>Harga List</th>
                <th>Diskon</th>
                <th>Harga Net</th>
                <th>DP</th>
                <th>Jenis</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($transactions as $transaction): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $transaction->hari . '/' . substr($transaction->bulan, 0, 3) . '/' . $transaction->tahun ?></td>
                <td><?= htmlspecialchars($transaction->customer) ?></td>
                <td><?= htmlspecialchars($transaction->sales_name) ?></td>
                <td><?= htmlspecialchars($transaction->model) ?></td>
                <td class="text-right"                >Rp <?= number_format($transaction->list_price, 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($transaction->discount, 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($transaction->net_price, 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($transaction->dp, 0, ',', '.') ?></td>
                <td class="text-center"><?= htmlspecialchars($transaction->type) ?></td>
                <td class="text-center">
                    <span class="badge badge-<?= $transaction->status == 'completed' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'primary') ?>">
                        <?= htmlspecialchars(ucfirst($transaction->status)) ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (empty($transactions)): ?>
        <div style="text-align: center; padding: 20px; color: #666;">
            Tidak ada transaksi ditemukan untuk periode yang dipilih.
        </div>
    <?php endif; ?>

    <div class="footer">
        <div>Dicetak oleh: <?= htmlspecialchars($current_user ?? 'Sistem') ?></div>
        <div>Tanggal Cetak: <?= date('d F Y H:i') ?></div>
    </div>

    <script>
        // Add confirmation before closing the window
        document.querySelector('.btn-print[style*="background-color: #6c757d"]').addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menutup laporan?')) {
                window.close();
            }
        });

        // Auto print when page loads (optional, can be removed if not needed)
        window.onload = function() {
            <?php if (isset($_GET['print']) && $_GET['print'] == 'auto'): ?>
                window.print();
            <?php endif; ?>
        };
    </script>


    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>
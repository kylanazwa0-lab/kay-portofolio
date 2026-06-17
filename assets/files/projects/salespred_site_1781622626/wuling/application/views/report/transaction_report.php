<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Wuling System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">
    <style>
        /* === DataTables Core Overrides === */
        .dataTables_wrapper {
            font-family: inherit;
            color: var(--text-primary);
        }
        /* Hide the default search box (we use our own filter) */
        .dataTables_wrapper .dataTables_filter { display: none; }

        /* Top bar: Show X entries */
        .dataTables_wrapper .dataTables_length {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-size: 0.88rem;
            margin-bottom: 1rem;
        }
        .dataTables_wrapper .dataTables_length select {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: var(--radius-sm);
            padding: 0.3rem 0.6rem;
            outline: none;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.88rem;
        }

        /* Info text */
        .dataTables_wrapper .dataTables_info {
            color: var(--text-muted);
            font-size: 0.85rem;
            padding: 0;
        }

        /* === Premium Pagination === */
        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 0.6rem;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: var(--bg-card);
            color: var(--text-primary) !important;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled):not(.current) {
            background: var(--bg-surface);
            border-color: var(--accent);
            color: var(--accent) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
            color: #fff !important;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(220,38,38,0.3);
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.previous::before { content: '\2039'; font-size: 1.1rem; line-height: 1; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.next::after    { content: '\203A'; font-size: 1.1rem; line-height: 1; }
        .dataTables_wrapper .dataTables_paginate .ellipsis {
            display: inline-flex; align-items: center; padding: 0 0.4rem;
            color: var(--text-muted); letter-spacing: 2px;
        }

        /* Bottom bar layout */
        .dataTables_wrapper .dt-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
            margin-top: 0.5rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        /* === Compact Table === */
        #transactionsTable {
            font-size: 0.8rem;
        }
        #transactionsTable thead th {
            padding: 0.55rem 0.6rem;
            white-space: nowrap;
            font-size: 0.75rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        #transactionsTable tbody td {
            padding: 0.5rem 0.6rem;
            white-space: nowrap;
        }
        .dt-scroll-wrapper {
            overflow-x: auto;
            width: 100%;
        }
    </style>

    <script>if(localStorage.getItem('theme') === 'light') document.documentElement.setAttribute('data-theme', 'light');</script>
</head>
<body>

    <!-- Navbar -->
    <?php $this->load->view('templates/navbar'); ?>

    <!-- Layout -->
    <div class="wl-layout">
        
        <!-- Sidebar -->
        <?php $this->load->view('templates/sidebar'); ?>

        <!-- Main Content -->
        <main class="wl-main">
            <!-- Page Header -->
            <div class="wl-page-header wl-fade-up-2" style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h1 class="wl-page-title">Laporan Transaksi</h1>
                    <p class="wl-page-subtitle">Ringkasan dan detail laporan penjualan</p>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="wl-fade-up-3" style="margin-bottom: 2rem;">
                <form method="GET" action="<?= site_url('report') ?>" onsubmit="event.preventDefault(); window.location.href = '<?= site_url('report') ?>?month=' + document.getElementById('input_month').value + '&year=' + document.getElementById('input_year').value;">
                    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius); padding: 0.75rem 1.25rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                            
                            <div style="display: flex; align-items: center;">
                                <input type="hidden" name="month" id="input_month" value="<?= $selected_month ?>">
                                <div class="wl-dropdown" style="position: relative;" onclick="toggleDropdown(this, event)">
                                    <button type="button" class="wl-dropdown-toggle wl-flex wl-justify-between wl-items-center" style="width: 150px; background: var(--bg-surface); color: var(--text-primary); border: 1px solid var(--border); padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); outline: none; cursor: pointer; font-family: inherit; font-size: 0.9rem;">
                                        <span class="selected-text">
                                            <?= empty($selected_month) ? 'Semua Bulan' : (isset($months[$selected_month]) ? $months[$selected_month] : 'Semua Bulan') ?>
                                        </span>
                                        <i class="fas fa-chevron-down text-muted" style="font-size: 10px;"></i>
                                    </button>
                                    <ul class="wl-dropdown-menu" style="width: 100%; min-width: 150px;">
                                        <li class="wl-dropdown-item <?= empty($selected_month) ? 'active' : '' ?>" onclick="selectOption(this, 'month', '', 'Semua Bulan')">Semua Bulan</li>
                                        <?php foreach($months as $key => $month_name): ?>
                                            <li class="wl-dropdown-item <?= $selected_month == $key ? 'active' : '' ?>" onclick="selectOption(this, 'month', '<?= $key ?>', '<?= $month_name ?>')"><?= $month_name ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            
                            <div style="display: flex; align-items: center;">
                                <input type="hidden" name="year" id="input_year" value="<?= $selected_year ?>">
                                <div class="wl-dropdown" style="position: relative;" onclick="toggleDropdown(this, event)">
                                    <button type="button" class="wl-dropdown-toggle wl-flex wl-justify-between wl-items-center" style="width: 140px; background: var(--bg-surface); color: var(--text-primary); border: 1px solid var(--border); padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); outline: none; cursor: pointer; font-family: inherit; font-size: 0.9rem;">
                                        <span class="selected-text">
                                            <?= empty($selected_year) ? 'Semua Tahun' : $selected_year ?>
                                        </span>
                                        <i class="fas fa-chevron-down text-muted" style="font-size: 10px;"></i>
                                    </button>
                                    <ul class="wl-dropdown-menu" style="width: 100%; min-width: 140px;">
                                        <li class="wl-dropdown-item <?= empty($selected_year) ? 'active' : '' ?>" onclick="selectOption(this, 'year', '', 'Semua Tahun')">Semua Tahun</li>
                                        <?php foreach($years as $year_data): ?>
                                            <li class="wl-dropdown-item <?= $selected_year == $year_data->tahun ? 'active' : '' ?>" onclick="selectOption(this, 'year', '<?= $year_data->tahun ?>', '<?= $year_data->tahun ?>')"><?= $year_data->tahun ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>

                            <div style="display: flex; gap: 0.5rem; border-left: 1px solid var(--border); padding-left: 1rem;">
                                <button type="submit" class="wl-btn wl-btn-primary wl-btn-sm" style="padding: 0.4rem 1rem;">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <?php if (!empty($selected_month) || !empty($selected_year)): ?>
                                <a href="<?= site_url('report') ?>" class="wl-btn wl-btn-secondary wl-btn-sm" style="padding: 0.4rem 1rem;">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                            <a href="<?= site_url('report/import_page'); ?>" class="wl-btn wl-btn-sm wl-btn-secondary" style="padding: 0.4rem 1rem; border: 1px dashed var(--border); background: rgba(255,255,255,0.05); color: var(--text-secondary);">
                                <i class="fas fa-file-import"></i> Import Excel
                            </a>
                            <a href="<?= site_url('report/export_excel?' . http_build_query($_GET)) ?>" class="wl-btn wl-btn-sm" style="background: var(--success); border-color: var(--success); color: #fff; padding: 0.4rem 1rem;">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                            <a href="<?= site_url('report/print_report?' . http_build_query($_GET)) ?>" target="_blank" class="wl-btn wl-btn-sm" style="background: var(--info); border-color: var(--info); color: #fff; padding: 0.4rem 1rem;">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="wl-stat-grid wl-fade-up-4">
                <div class="wl-stat-card">
                    <div class="wl-stat-icon blue"><i class="fas fa-receipt"></i></div>
                    <div>
                        <div class="wl-stat-value"><?= number_format($summary->total_transactions) ?></div>
                        <div class="wl-stat-label">Total Transaksi</div>
                    </div>
                </div>
                <div class="wl-stat-card">
                    <div class="wl-stat-icon green"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <div class="wl-stat-value">Rp <?= number_format($summary->total_price_net, 0, ',', '.') ?></div>
                        <div class="wl-stat-label">Total Pendapatan</div>
                    </div>
                </div>
                <div class="wl-stat-card">
                    <div class="wl-stat-icon orange"><i class="fas fa-percentage"></i></div>
                    <div>
                        <div class="wl-stat-value">Rp <?= number_format($summary->total_discount, 0, ',', '.') ?></div>
                        <div class="wl-stat-label">Total Diskon</div>
                    </div>
                </div>
                <div class="wl-stat-card">
                    <div class="wl-stat-icon purple"><i class="fas fa-money-check-alt"></i></div>
                    <div>
                        <div class="wl-stat-value">Rp <?= number_format($summary->total_dp, 0, ',', '.') ?></div>
                        <div class="wl-stat-label">Total DP</div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;" class="wl-fade-up-5">
                <div class="wl-card">
                    <div class="wl-card-header" style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
                        <h2 class="wl-card-title" style="margin-bottom: 0;"><i class="fas fa-user-tie"></i> Performance Sales</h2>
                        <button type="button" style="background: transparent; border: none; color: var(--text-muted); cursor: pointer;" onclick="toggleCardBody('performance-sales-body', this)">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="wl-table-wrapper" id="performance-sales-body" style="display: none; padding-top: 1rem;">
                        <table class="wl-table">
                            <thead>
                                <tr>
                                    <th>Sales</th>
                                    <th>Total Penjualan</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($sales_summary as $sales): ?>
                                <tr>
                                    <td><?= htmlspecialchars($sales->sales_name) ?></td>
                                    <td><span class="wl-badge wl-badge-blue"><?= number_format($sales->total_sales) ?></span></td>
                                    <td style="color: var(--success); font-weight: 600;">Rp <?= number_format($sales->total_revenue, 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="wl-card">
                    <div class="wl-card-header" style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
                        <h2 class="wl-card-title" style="margin-bottom: 0;"><i class="fas fa-car"></i> Model Terlaris</h2>
                        <button type="button" style="background: transparent; border: none; color: var(--text-muted); cursor: pointer;" onclick="toggleCardBody('model-terlaris-body', this)">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="wl-table-wrapper" id="model-terlaris-body" style="display: none; padding-top: 1rem;">
                        <table class="wl-table">
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>Terjual</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($model_summary as $model): ?>
                                <tr>
                                    <td><?= htmlspecialchars($model->model) ?></td>
                                    <td><span class="wl-badge wl-badge-info"><?= number_format($model->total_sold) ?></span></td>
                                    <td style="color: var(--success); font-weight: 600;">Rp <?= number_format($model->total_revenue, 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="wl-card wl-fade-up-6">
                <div class="wl-card-header">
                    <h2 class="wl-card-title"><i class="fas fa-list"></i> Detail Transaksi</h2>
                </div>
                <div class="dt-scroll-wrapper">
                <table id="transactionsTable" class="wl-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Sales</th>
                            <th>Model</th>
                            <th>Tipe</th>
                            <th>Harga List</th>
                            <th>Diskon</th>
                            <th>Harga Net</th>
                            <th>DP</th>
                            <th>Bayar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        function shortRp($num) {
                            if ($num >= 1000000000) return 'Rp ' . number_format($num/1000000000, 1, ',', '.') . ' M';
                            if ($num >= 1000000)    return 'Rp ' . number_format($num/1000000, 1, ',', '.') . ' jt';
                            return 'Rp ' . number_format($num, 0, ',', '.');
                        }
                        foreach($transactions as $transaction): ?>
                        <tr>
                            <td><?= $transaction->id ?></td>
                            <td><?= $transaction->hari . ' ' . substr($transaction->bulan, 0, 3) . ' ' . $transaction->tahun ?></td>
                            <td><?= htmlspecialchars($transaction->customer) ?></td>
                            <td><?= htmlspecialchars($transaction->sales_name) ?></td>
                            <td><?= htmlspecialchars($transaction->model) ?></td>
                            <td class="muted" title="<?= htmlspecialchars($transaction->type) ?>"><?= strlen($transaction->type) > 14 ? substr($transaction->type, 0, 14).'…' : htmlspecialchars($transaction->type) ?></td>
                            <td class="muted"><?= shortRp($transaction->price_list) ?></td>
                            <td class="muted"><?= shortRp($transaction->discount) ?></td>
                            <td style="color: var(--success); font-weight: 600;"><?= shortRp($transaction->price_net) ?></td>
                            <td class="muted"><?= shortRp($transaction->dp_amt) ?></td>
                            <td>
                                <span class="wl-badge wl-badge-<?= $transaction->tunai_kredit == 'Tunai' ? 'success' : 'blue' ?>">
                                    <?= $transaction->tunai_kredit ?>
                                </span>
                            </td>
                            <td>
                                <span class="wl-badge wl-badge-<?= $transaction->do_status == 1 ? 'success' : 'warning' ?>">
                                    <?= $transaction->do_status == 1 ? 'Done' : 'Pending' ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#transactionsTable').DataTable({
                "pageLength": 10,
                "order": [[ 0, "desc" ]],
                "pagingType": "simple_numbers",
                "dom": '<"dt-top"l>t<"dt-bottom"ip>',
                "language": {
                    "lengthMenu": 'Tampilkan _MENU_ data per halaman',
                    "info":       'Menampilkan _START_–_END_ dari _TOTAL_ data',
                    "infoEmpty": 'Tidak ada data tersedia',
                    "infoFiltered": '(difilter dari _MAX_ total data)',
                    "paginate": {
                        "previous": '',
                        "next": ''
                    },
                    "emptyTable": 'Tidak ada data transaksi'
                },
                "columnDefs": [
                    { "targets": [6, 7, 8, 9], "className": "text-end" }
                ],
                "initComplete": function() {
                    // Custom length menu dropdown
                    var $select = $('.dataTables_length select');
                    $select.hide();
                    
                    var optionsHtml = '';
                    var selectedText = '10';
                    $select.find('option').each(function() {
                        var val = $(this).val();
                        var text = $(this).text();
                        var isActive = $(this).is(':selected') ? 'active' : '';
                        if (isActive) selectedText = text;
                        optionsHtml += `<li class="wl-dropdown-item ${isActive}" data-val="${val}">${text}</li>`;
                    });
                    
                    var customDropdown = $(`
                        <div class="wl-dropdown" style="display: inline-block; width: 65px; margin: 0 0.5rem;" onclick="toggleDropdown(this, event)">
                            <button type="button" class="wl-dropdown-toggle wl-select wl-flex wl-justify-between wl-items-center" style="width: 100%; background: var(--bg-surface); border: 1px solid var(--border); color: var(--text-primary); cursor: pointer; padding: 0.25rem 0.5rem; min-height: 32px; border-radius: var(--radius-sm);">
                                <span class="selected-text" style="font-size: var(--fs-sm);">${selectedText}</span>
                                <i class="fas fa-chevron-down text-muted" style="font-size: 10px;"></i>
                            </button>
                            <ul class="wl-dropdown-menu" style="width: 100%; min-width: 60px;">
                                ${optionsHtml}
                            </ul>
                        </div>
                    `);
                    
                    $select.after(customDropdown);
                    
                    customDropdown.find('.wl-dropdown-item').on('click', function(e) {
                        e.stopPropagation();
                        var val = $(this).data('val');
                        var text = $(this).text();
                        
                        // Update native select and trigger change for DataTables
                        $select.val(val).trigger('change');
                        
                        // Update custom UI
                        customDropdown.find('.selected-text').text(text);
                        customDropdown.find('.wl-dropdown-item').removeClass('active');
                        $(this).addClass('active');
                        
                        // Close dropdown
                        customDropdown.removeClass('is-open');
                    });
                }
            });
        });

        // Dropdown Logic
        function toggleDropdown(el, event) {
            event.stopPropagation();
            document.querySelectorAll('.wl-dropdown').forEach(d => {
                if (d !== el) d.classList.remove('is-open');
            });
            el.classList.toggle('is-open');
        }

        document.addEventListener('click', function(event) {
            document.querySelectorAll('.wl-dropdown').forEach(d => {
                if (!d.contains(event.target)) {
                    d.classList.remove('is-open');
                }
            });
        });

        function selectOption(el, inputName, value, text) {
            // Set hidden input value
            document.getElementById('input_' + inputName).value = value;
            
            const dropdown = el.closest('.wl-dropdown');
            // Update button text
            dropdown.querySelector('.selected-text').textContent = text;
            
            // Update active state
            dropdown.querySelectorAll('.wl-dropdown-item').forEach(item => item.classList.remove('active'));
            el.classList.add('active');
        }
        function toggleCardBody(targetId, btn) {
            const el = document.getElementById(targetId);
            const icon = btn.querySelector('i');
            if (el.style.display === 'none') {
                el.style.display = 'block';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                el.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    </script>

    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>
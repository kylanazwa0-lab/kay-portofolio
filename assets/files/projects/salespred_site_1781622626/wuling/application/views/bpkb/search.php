<?php
/**
 * CI URL strategy with index_page = 'index.php?'
 * site_url('bpkb/search') = http://host/wuling/index.php?/bpkb/search
 *
 * GET form PROBLEM: when browser submits a GET form, it REPLACES everything
 * after '?' with the new query string. So action=".../index.php?/bpkb/search"
 * would lose the "/bpkb/search" part.
 *
 * SOLUTION: Set form action = base_url('index.php') only, then pass the
 * CI route as a hidden field named with the literal key '/' so the
 * resulting URL becomes: index.php?/bpkb/search&q=...&model=...
 * 
 * The hidden input trick: name="/bpkb/search" value="" — but CI reads the
 * QUERY_STRING so we need the key literally be "/bpkb/search".
 * Simplest: action = site_url('bpkb/search') works because CI's
 * index_page already embeds '?'. The issue is the browser strips the 
 * path info and replaces. Use JavaScript to submit instead.
 */

// Build export URL preserving current filters  
$export_params = [];
if (!empty($keyword))                          $export_params[] = 'q='      . urlencode($keyword);
if (isset($status) && $status !== '')          $export_params[] = 'status=' . urlencode($status);
if (!empty($filter_model))                     $export_params[] = 'model='  . urlencode($filter_model);
if (!empty($filter_type))                      $export_params[] = 'type='   . urlencode($filter_type);

$export_url = site_url('bpkb/export') . (!empty($export_params) ? '?' . implode('&', $export_params) : '');

// Check if any filter is active
$has_filter = !empty($keyword) || (isset($status) && $status !== '') || !empty($filter_model) || !empty($filter_type);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'BPKB Tracking') ?> - Wuling System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css') ?>">
    <script>if(localStorage.getItem('theme')==='light')document.documentElement.setAttribute('data-theme','light');</script>
    <style>
        .compact-table td, .compact-table th { padding: 0.5rem 0.75rem !important; font-size: 0.85rem; }
        .compact-btn { padding: 0.3rem 0.65rem !important; font-size: 0.8rem !important; }
        .wl-page-header { margin-bottom: 1rem !important; }
        .compact-filter-card { padding: 1rem 1.5rem !important; margin-bottom: 1.25rem !important; }
        .filter-input { font-size: 0.85rem !important; padding: 0.4rem 0.75rem !important; height: auto !important; }
        .compact-badge { font-size: 0.72rem !important; padding: 0.2rem 0.45rem !important; }
        .filter-row { display: flex; gap: 0.6rem; align-items: center; flex-wrap: wrap; }
        .filter-row .fi-search { flex: 2; position: relative; min-width: 220px; }
        .filter-row .fi-search i { position: absolute; left: 0.65rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.8rem; pointer-events: none; }
        .filter-row .fi-search input { padding-left: 2rem !important; width: 100%; box-sizing: border-box; }
        .filter-row .fi-sel { flex: 1; min-width: 110px; }
        .filter-row .fi-sel select { width: 100%; box-sizing: border-box; }
    </style>
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

            <!-- Breadcrumb -->
            <div class="wl-breadcrumb wl-fade-up">
                <a href="<?= site_url('dashboard') ?>" style="color: var(--text-muted); text-decoration: none;">Dashboard</a>
                <i class="fas fa-chevron-right" style="font-size:0.65rem;"></i>
                <span>BPKB Search &amp; Tracking</span>
            </div>

            <!-- Page Header -->
            <div class="wl-page-header wl-fade-up-2" style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <h2 class="wl-page-title" style="font-size:1.5rem;margin:0;">Data Tracking BPKB</h2>
                    <p class="wl-page-subtitle" style="font-size:0.82rem;margin:0.2rem 0 0;">Kelola status dokumen BPKB kendaraan.</p>
                </div>
                <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                    <a href="<?= site_url('bpkb/import') ?>" class="wl-btn wl-btn-ghost compact-btn">
                        <i class="fas fa-file-import"></i> Import
                    </a>
                    <a href="<?= $export_url ?>" class="wl-btn wl-btn-ghost compact-btn">
                        <i class="fas fa-file-export"></i> Export
                    </a>
                    <a href="<?= site_url('bpkb/create') ?>" class="wl-btn wl-btn-primary compact-btn">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if($this->session->flashdata('success')): ?>
                <div class="wl-alert wl-alert-success wl-fade-up" style="margin-bottom:0.75rem;padding:0.65rem 1rem;">
                    <i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div class="wl-alert wl-alert-danger wl-fade-up" style="margin-bottom:0.75rem;padding:0.65rem 1rem;">
                    <i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Filter Form -->
            <!-- JS-driven submit: we redirect manually to preserve CI's index.php?/route format -->
            <div class="wl-card compact-filter-card wl-fade-up-3">
                <form id="filterForm" action="#" method="GET" onsubmit="doFilter(event)">
                    <div class="filter-row">

                        <div class="fi-search">
                            <i class="fas fa-search"></i>
                            <input type="text" name="q" class="wl-input filter-input"
                                   placeholder="No. Rangka, Customer, Invoice..."
                                   value="<?= htmlspecialchars($keyword ?? '') ?>">
                        </div>

                        <div class="fi-sel">
                            <select name="model" class="wl-input filter-input wl-custom-select-init">
                                <option value="">Semua Model</option>
                                <?php foreach($models as $m): ?>
                                    <option value="<?= htmlspecialchars($m->model) ?>"
                                        <?= (!empty($filter_model) && $filter_model === $m->model) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($m->model) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="fi-sel">
                            <select name="type" class="wl-input filter-input wl-custom-select-init">
                                <option value="">Semua Tipe</option>
                                <?php foreach($types as $t): ?>
                                    <option value="<?= htmlspecialchars($t->type) ?>"
                                        <?= (!empty($filter_type) && $filter_type === $t->type) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($t->type) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="fi-sel">
                            <select name="status" class="wl-input filter-input wl-custom-select-init">
                                <option value="">Semua Status DO</option>
                                <option value="0" <?= (isset($status) && $status === '0') ? 'selected' : '' ?>>Pending</option>
                                <option value="1" <?= (isset($status) && $status === '1') ? 'selected' : '' ?>>Delivered</option>
                            </select>
                        </div>



                        <?php if($has_filter): ?>
                        <a href="<?= site_url('bpkb/search') ?>" class="wl-btn wl-btn-ghost compact-btn" title="Reset semua filter">
                            <i class="fas fa-times"></i> Reset
                        </a>
                        <?php endif; ?>

                    </div>
                </form>
            </div>

            <!-- Results Table -->
            <div class="wl-card wl-fade-up-5" style="padding:0;">
                <?php if($has_filter): ?>
                <div style="padding:0.5rem 1rem;font-size:0.8rem;color:var(--text-muted);border-bottom:1px solid var(--border);">
                    <i class="fas fa-info-circle"></i>
                    Menampilkan <strong><?= count($results) ?></strong> data
                    <?= !empty($keyword) ? 'untuk kata kunci "<strong>' . htmlspecialchars($keyword) . '</strong>"' : '' ?>
                    <?= !empty($filter_model) ? '| Model: <strong>' . htmlspecialchars($filter_model) . '</strong>' : '' ?>
                    <?= !empty($filter_type) ? '| Tipe: <strong>' . htmlspecialchars($filter_type) . '</strong>' : '' ?>
                    <?= (isset($status) && $status !== '') ? '| Status: <strong>' . ($status == '1' ? 'Delivered' : 'Pending') . '</strong>' : '' ?>
                </div>
                <?php endif; ?>

                <div class="wl-table-container">
                    <table class="wl-table compact-table">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Customer</th>
                                <th>No. Rangka (Chassis)</th>
                                <th>Model &amp; Tipe</th>
                                <th>Status DO</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($results) > 0): ?>
                                <?php foreach($results as $row): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight:600;color:var(--text-primary);"><?= htmlspecialchars($row->inv_no) ?></div>
                                    </td>
                                    <td>
                                        <div style="font-weight:600;color:var(--text-primary);"><?= htmlspecialchars($row->customer) ?></div>
                                        <div style="font-size:0.7rem;color:var(--text-muted);">KTP: <?= htmlspecialchars($row->ktp_no ?? '-') ?></div>
                                    </td>
                                    <td>
                                        <span style="font-family:monospace;font-size:0.8rem;font-weight:600;color:#8b5cf6;background:rgba(139,92,246,0.1);padding:0.15rem 0.4rem;border-radius:4px;">
                                            <?= htmlspecialchars($row->chassis) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="color:var(--text-primary);font-weight:500;"><?= htmlspecialchars($row->model ?? '-') ?></div>
                                        <div style="font-size:0.72rem;color:var(--text-muted);"><?= htmlspecialchars($row->type ?? '-') ?></div>
                                    </td>
                                    <td>
                                        <?php if($row->do_status == 1): ?>
                                            <span class="wl-badge wl-badge-success compact-badge"><i class="fas fa-check-circle" style="margin-right:3px;"></i>Delivered</span>
                                        <?php else: ?>
                                            <span class="wl-badge wl-badge-warning compact-badge"><i class="fas fa-clock" style="margin-right:3px;"></i>Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:right;white-space:nowrap;">
                                        <a href="<?= site_url('bpkb/edit/' . $row->id) ?>"
                                           class="wl-btn wl-btn-sm wl-btn-ghost compact-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= site_url('bpkb/delete/' . $row->id) ?>"
                                           class="wl-btn wl-btn-sm wl-btn-ghost compact-btn"
                                           style="color:var(--danger);" title="Hapus"
                                           onclick="return confirm('Hapus data BPKB ini?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;padding:3rem 1rem !important;color:var(--text-muted);">
                                        <?php if($has_filter): ?>
                                            <div style="font-size:2rem;margin-bottom:0.5rem;opacity:0.4;"><i class="fas fa-search"></i></div>
                                            <div>Tidak ada data yang cocok dengan filter yang dipilih.</div>
                                            <a href="<?= site_url('bpkb/search') ?>" style="color:var(--primary);font-size:0.82rem;">Reset filter</a>
                                        <?php else: ?>
                                            <div style="font-size:2rem;margin-bottom:0.5rem;opacity:0.4;"><i class="fas fa-folder-open"></i></div>
                                            <div>Belum ada data BPKB. <a href="<?= site_url('bpkb/create') ?>" style="color:var(--primary);">Tambah sekarang</a></div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script src="<?= base_url('assets/js/theme.js?v=' . time()) ?>"></script>
    <script>
    // Fix for CI index_page='index.php?' — GET forms break the CI route segment.
    // We manually build the URL and redirect instead of using native form submit.
    var CI_BASE = '<?= site_url('bpkb/search') ?>';

    function doFilter(e) {
        if (e) e.preventDefault();
        var form  = document.getElementById('filterForm');
        var q     = form.querySelector('[name="q"]').value.trim();
        var model = form.querySelector('[name="model"]').value;
        var type  = form.querySelector('[name="type"]').value;
        var status = form.querySelector('[name="status"]').value;

        var params = [];
        if (q)      params.push('q='      + encodeURIComponent(q));
        if (model)  params.push('model='  + encodeURIComponent(model));
        if (type)   params.push('type='   + encodeURIComponent(type));
        if (status !== '') params.push('status=' + encodeURIComponent(status));

        var url = CI_BASE + (params.length ? '?' + params.join('&') : '');
        window.location.href = url;
    }

    // Auto submit form on change
    var filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('change', function() {
            doFilter();
        });
    }
    </script>
</body>
</html>

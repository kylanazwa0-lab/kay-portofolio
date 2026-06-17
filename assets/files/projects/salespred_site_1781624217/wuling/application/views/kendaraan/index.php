<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kendaraan — Wuling System</title>
    <meta name="description" content="Daftar inventaris kendaraan Wuling. Kelola, tambah, dan pantau data kendaraan secara lengkap.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">

    <script>if(localStorage.getItem('theme') === 'light') document.documentElement.setAttribute('data-theme', 'light');</script>

    <style>
        /* ── Page-specific overrides ─────────────────────── */
        .kdr-hero {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
        }

        .kdr-hero-left {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .kdr-eyebrow {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--accent);
            margin-bottom: 0.2rem;
        }

        .kdr-eyebrow span {
            display: inline-block;
            width: 20px;
            height: 2px;
            background: var(--accent);
            border-radius: 2px;
        }

        .kdr-title {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: var(--text-primary);
            line-height: 1.1;
            font-family: var(--font-display);
        }

        .kdr-sub {
            font-size: var(--fs-sm);
            color: var(--text-muted);
            font-weight: 400;
        }

        .kdr-actions {
            display: flex;
            gap: 0.65rem;
            align-items: center;
            flex-wrap: wrap;
        }

        /* ── Stats strip ───────────────────────────────── */
        .kdr-stats-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: var(--border);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            margin-bottom: 2rem;
            animation: fadeSlideUp 0.5s cubic-bezier(0.16,1,0.3,1) 0.1s both;
        }

        .kdr-stat-cell {
            background: var(--bg-card);
            padding: 1.25rem 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            transition: background var(--transition-fast);
        }

        .kdr-stat-cell:hover { background: var(--bg-elevated); }

        .kdr-stat-num {
            font-size: 1.75rem;
            font-weight: 800;
            font-family: var(--font-display);
            letter-spacing: -0.04em;
            color: var(--text-primary);
            line-height: 1;
        }

        .kdr-stat-num.accent { color: var(--accent); }
        .kdr-stat-num.green  { color: #10b981; }

        .kdr-stat-desc {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
        }

        /* ── Table panel ───────────────────────────────── */
        .kdr-panel {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            overflow: hidden;
            animation: fadeSlideUp 0.5s cubic-bezier(0.16,1,0.3,1) 0.2s both;
        }

        .kdr-panel-head {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            background: var(--bg-elevated);
        }

        .kdr-panel-title {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: var(--fs-sm);
            font-weight: 700;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .kdr-panel-title i {
            color: var(--accent);
            font-size: 0.85rem;
        }

        .kdr-count-pill {
            background: var(--accent-glow);
            border: 1px solid var(--accent-border);
            color: var(--accent);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.65rem;
            border-radius: 99px;
            letter-spacing: 0.04em;
        }

        /* ── Data table ────────────────────────────────── */
        .kdr-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kdr-table thead th {
            padding: 0.85rem 1.25rem;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            text-align: left;
            background: var(--bg-elevated);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .kdr-table thead th:first-child { padding-left: 2rem; }
        .kdr-table thead th:last-child  { padding-right: 1.5rem; text-align: right; }

        .kdr-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background var(--transition-fast);
            animation: rowIn 0.4s cubic-bezier(0.16,1,0.3,1) both;
        }

        .kdr-table tbody tr:last-child { border-bottom: none; }

        .kdr-table tbody tr:hover {
            background: rgba(255,255,255,0.025);
        }

        [data-theme="light"] .kdr-table tbody tr:hover {
            background: rgba(9,9,11,0.025);
        }

        .kdr-table td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
        }

        .kdr-table td:first-child { padding-left: 2rem; }
        .kdr-table td:last-child  { padding-right: 1.5rem; }

        .kdr-row-no {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            font-family: var(--font-mono);
            width: 2rem;
        }

        .kdr-model-name {
            font-weight: 700;
            font-size: var(--fs-sm);
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }

        .kdr-cat-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.65rem;
            border-radius: 99px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: var(--bg-elevated);
            border: 1px solid var(--border-hover);
            color: var(--text-secondary);
        }

        .kdr-price {
            font-weight: 700;
            font-family: var(--font-mono);
            font-size: var(--fs-sm);
            color: #10b981;
            letter-spacing: -0.01em;
            white-space: nowrap;
        }

        .kdr-desc-text {
            font-size: var(--fs-xs);
            color: var(--text-muted);
            max-width: 260px;
            line-height: 1.5;
        }

        .kdr-date {
            font-size: var(--fs-xs);
            font-family: var(--font-mono);
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* ── Empty state ───────────────────────────────── */
        .kdr-empty {
            text-align: center;
            padding: 5rem 2rem;
        }

        .kdr-empty-icon {
            width: 64px;
            height: 64px;
            border-radius: var(--radius-lg);
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: var(--text-muted);
            margin-bottom: 1.25rem;
        }

        .kdr-empty h3 {
            font-size: var(--fs-lg);
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .kdr-empty p {
            font-size: var(--fs-sm);
            color: var(--text-muted);
        }

        /* ── Animations ────────────────────────────────── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes rowIn {
            from { opacity: 0; transform: translateX(-8px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .kdr-hero {
            animation: fadeSlideUp 0.4s cubic-bezier(0.16,1,0.3,1) both;
        }

        /* stagger rows */
        .kdr-table tbody tr:nth-child(1)  { animation-delay: 0.22s; }
        .kdr-table tbody tr:nth-child(2)  { animation-delay: 0.27s; }
        .kdr-table tbody tr:nth-child(3)  { animation-delay: 0.32s; }
        .kdr-table tbody tr:nth-child(4)  { animation-delay: 0.37s; }
        .kdr-table tbody tr:nth-child(5)  { animation-delay: 0.42s; }
        .kdr-table tbody tr:nth-child(6)  { animation-delay: 0.47s; }
        .kdr-table tbody tr:nth-child(7)  { animation-delay: 0.52s; }
        .kdr-table tbody tr:nth-child(8)  { animation-delay: 0.57s; }
        .kdr-table tbody tr:nth-child(n+9){ animation-delay: 0.6s; }

        /* ── Buttons restyle ───────────────────────────── */
        .kdr-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.1rem;
            border-radius: var(--radius);
            font-size: var(--fs-sm);
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-fast);
            border: 1px solid transparent;
            text-decoration: none;
            white-space: nowrap;
            letter-spacing: -0.01em;
        }

        .kdr-btn:active { transform: scale(0.98) translateY(1px); }

        .kdr-btn-primary {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
            box-shadow: 0 2px 8px var(--accent-glow);
        }

        .kdr-btn-primary:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
            box-shadow: 0 4px 16px var(--accent-glow);
            color: #fff;
        }

        .kdr-btn-ghost {
            background: transparent;
            color: var(--text-secondary);
            border-color: var(--border);
        }

        .kdr-btn-ghost:hover {
            background: var(--bg-elevated);
            color: var(--text-primary);
            border-color: var(--border-hover);
        }

        .kdr-btn-dashed {
            background: transparent;
            color: var(--text-muted);
            border: 1px dashed var(--border-hover);
        }

        .kdr-btn-dashed:hover {
            background: var(--bg-elevated);
            color: var(--text-secondary);
            border-color: var(--text-muted);
        }
    </style>
</head>
<body>

    <?php $this->load->view('templates/navbar'); ?>

    <div class="wl-layout">

        <?php $this->load->view('templates/sidebar'); ?>

        <main class="wl-main">

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="wl-alert wl-alert-success wl-fade-up" style="margin-bottom: 1.5rem;">
                    <i class="fas fa-check-circle wl-alert-icon"></i>
                    <div class="wl-alert-body"><div class="wl-alert-msg"><?= $this->session->flashdata('success'); ?></div></div>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="wl-alert wl-alert-danger wl-fade-up" style="margin-bottom: 1.5rem;">
                    <i class="fas fa-exclamation-triangle wl-alert-icon"></i>
                    <div class="wl-alert-body"><div class="wl-alert-msg"><?= $this->session->flashdata('error'); ?></div></div>
                </div>
            <?php endif; ?>

            <!-- Hero Header -->
            <div class="kdr-hero">
                <div class="kdr-hero-left">
                    <div class="kdr-eyebrow"><span></span> Inventaris</div>
                    <h1 class="kdr-title">Kelola Kendaraan</h1>
                    <p class="kdr-sub">Daftar lengkap armada Wuling yang tersedia</p>
                </div>
                <div class="kdr-actions">
                    <a href="<?= site_url('dashboard'); ?>" class="kdr-btn kdr-btn-ghost">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                    <?php if (isset($can_delete) && $can_delete): ?>
                    <a href="<?= site_url('kendaraan/import_page'); ?>" class="kdr-btn kdr-btn-dashed">
                        <i class="fas fa-file-import"></i> Import Excel
                    </a>
                    <?php endif; ?>
                    <?php if (isset($can_edit) && $can_edit): ?>
                    <a href="<?= site_url('kendaraan/tambah'); ?>" class="kdr-btn kdr-btn-primary">
                        <i class="fas fa-plus"></i> Tambah Kendaraan
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Stats Strip -->
            <?php if (!empty($kendaraan)): ?>
            <?php
                $total = count($kendaraan);
                $total_harga = array_sum(array_map(fn($k) => $k->price, $kendaraan));
                $categories = array_unique(array_map(fn($k) => $k->category, $kendaraan));
            ?>
            <div class="kdr-stats-strip">
                <div class="kdr-stat-cell">
                    <div class="kdr-stat-num accent"><?= $total ?></div>
                    <div class="kdr-stat-desc">Total Kendaraan</div>
                </div>
                <div class="kdr-stat-cell">
                    <div class="kdr-stat-num green"><?= count($categories) ?></div>
                    <div class="kdr-stat-desc">Kategori</div>
                </div>
                <div class="kdr-stat-cell">
                    <div class="kdr-stat-num" style="font-size:1.25rem; color: #10b981;">Rp <?= number_format($total_harga, 0, ',', '.') ?></div>
                    <div class="kdr-stat-desc">Total Nilai Inventaris</div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Table Panel -->
            <div class="kdr-panel">
                <div class="kdr-panel-head">
                    <div class="kdr-panel-title">
                        <i class="fas fa-car"></i>
                        Data Kendaraan
                    </div>
                    <?php if (!empty($kendaraan)): ?>
                    <span class="kdr-count-pill"><?= count($kendaraan) ?> unit</span>
                    <?php endif; ?>
                </div>

                <?php if (empty($kendaraan)): ?>
                <div class="kdr-empty">
                    <div class="kdr-empty-icon"><i class="fas fa-car-side"></i></div>
                    <h3>Belum ada kendaraan</h3>
                    <p>Tambahkan kendaraan pertama untuk memulai inventaris.</p>
                    <?php if (isset($can_edit) && $can_edit): ?>
                    <br>
                    <a href="<?= site_url('kendaraan/tambah'); ?>" class="kdr-btn kdr-btn-primary" style="margin: 0 auto;">
                        <i class="fas fa-plus"></i> Tambah Sekarang
                    </a>
                    <?php endif; ?>
                </div>

                <?php else: ?>
                <div style="overflow-x: auto; overflow-y: visible;">
                    <table class="kdr-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Model</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Deskripsi</th>
                                <th>Dibuat</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($kendaraan as $item): ?>
                            <tr>
                                <td><span class="kdr-row-no"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></span></td>
                                <td>
                                    <div class="kdr-model-name"><?= htmlspecialchars($item->model_name) ?></div>
                                </td>
                                <td>
                                    <span class="kdr-cat-badge"><?= htmlspecialchars($item->category) ?></span>
                                </td>
                                <td>
                                    <span class="kdr-price">Rp <?= number_format($item->price, 0, ',', '.') ?></span>
                                </td>
                                <td>
                                    <span class="kdr-desc-text">
                                        <?= strlen($item->description) > 60
                                            ? substr(htmlspecialchars($item->description), 0, 60) . '…'
                                            : htmlspecialchars($item->description) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="kdr-date"><?= date('d/m/Y', strtotime($item->created_at)) ?></span>
                                </td>
                                <td style="text-align:right; position: relative;">
                                    <div class="wl-dropdown">
                                        <button type="button" class="wl-dropdown-trigger" onclick="toggleActionDropdown(this.parentElement, event)" aria-haspopup="true" aria-expanded="false" aria-label="Menu aksi">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="wl-dropdown-menu">
                                            <li><a class="wl-dropdown-item" href="<?= site_url('kendaraan/detail/'.$item->id); ?>"><i class="fas fa-eye"></i> Lihat Detail</a></li>
                                            <?php if (isset($can_edit) && $can_edit): ?>
                                            <li><a class="wl-dropdown-item" href="<?= site_url('kendaraan/edit/'.$item->id); ?>"><i class="fas fa-edit"></i> Edit Data</a></li>
                                            <?php endif; ?>
                                            <?php if (isset($can_delete) && $can_delete): ?>
                                            <li style="border-top: 1px solid var(--border); margin-top: 0.25rem; padding-top: 0.25rem;">
                                                <a class="wl-dropdown-item wl-dropdown-item-danger" href="<?= site_url('kendaraan/hapus/'.$item->id); ?>" onclick="return confirm('Hapus kendaraan <?= htmlspecialchars(addslashes($item->model_name)) ?>?')">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <script>
        // Auto-hide flash messages
        setTimeout(() => {
            document.querySelectorAll('.wl-alert').forEach(a => {
                a.style.transition = 'opacity 0.3s';
                a.style.opacity = '0';
                setTimeout(() => a.remove(), 300);
            });
        }, 5000);

        // Dropdown toggle with fixed positioning to avoid overflow clipping
        function positionDropdown(el) {
            const trigger = el.querySelector('.wl-dropdown-trigger');
            const menu    = el.querySelector('.wl-dropdown-menu');
            if (!trigger || !menu) return;

            const rect = trigger.getBoundingClientRect();
            const menuW = 170; // approx min-width
            const leftPos = rect.right - menuW;

            menu.style.position = 'fixed';
            menu.style.top  = (rect.bottom + 6) + 'px';
            menu.style.left = Math.max(8, leftPos) + 'px';
            menu.style.right = 'auto';
            menu.style.zIndex = '9999';
        }

        function toggleActionDropdown(el, event) {
            event.stopPropagation();
            const isOpen = el.classList.contains('is-open');

            // Close all open dropdowns
            document.querySelectorAll('.wl-dropdown.is-open').forEach(d => {
                if (d !== el) {
                    d.classList.remove('is-open');
                    const btn = d.querySelector('.wl-dropdown-trigger');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            });

            if (!isOpen) {
                el.classList.add('is-open');
                positionDropdown(el);
                const trigger = el.querySelector('.wl-dropdown-trigger');
                if (trigger) trigger.setAttribute('aria-expanded', 'true');
            } else {
                el.classList.remove('is-open');
                const trigger = el.querySelector('.wl-dropdown-trigger');
                if (trigger) trigger.setAttribute('aria-expanded', 'false');
            }
        }

        document.addEventListener('click', function(e) {
            document.querySelectorAll('.wl-dropdown.is-open').forEach(d => {
                if (!d.contains(e.target)) {
                    d.classList.remove('is-open');
                    const btn = d.querySelector('.wl-dropdown-trigger');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            });
        });

        // Reposition on scroll/resize
        window.addEventListener('scroll', () => {
            document.querySelectorAll('.wl-dropdown.is-open').forEach(d => positionDropdown(d));
        }, true);
        window.addEventListener('resize', () => {
            document.querySelectorAll('.wl-dropdown.is-open').forEach(d => d.classList.remove('is-open'));
        });
    </script>

    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>
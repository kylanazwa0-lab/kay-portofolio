<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPKB Search & Import - Wuling System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">
    <style>
        .import-wrapper {
            max-width: 820px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* ── Breadcrumb ── */
        .import-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }
        .import-breadcrumb a { color: var(--text-muted); text-decoration: none; }
        .import-breadcrumb a:hover { color: var(--accent); }
        .import-breadcrumb .sep { opacity: 0.4; }

        /* ── Header ── */
        .import-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .import-title-wrap h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin: 0 0 0.25rem;
        }
        .import-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.2rem 0.5rem;
            border-radius: 99px;
            background: var(--accent);
            color: #fff;
            letter-spacing: 0.04em;
            vertical-align: middle;
        }
        .import-title-wrap p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* ── Drop Zone ── */
        .drop-zone-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .drop-zone {
            border: 2px dashed rgba(156, 163, 175, 0.4);
            border-radius: 12px;
            margin: 1.5rem;
            padding: 4rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(156, 163, 175, 0.03);
            position: relative;
        }
        .drop-zone:hover, .drop-zone.dragover {
            border-color: var(--accent);
            background: rgba(220, 38, 38, 0.04);
            transform: scale(0.995);
        }
        .drop-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        .drop-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.25rem;
            background: var(--bg-surface);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--text-muted);
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .drop-zone:hover .drop-icon, .drop-zone.dragover .drop-icon {
            color: var(--accent);
            border-color: rgba(220, 38, 38, 0.3);
            background: rgba(220, 38, 38, 0.1);
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(220, 38, 38, 0.15);
        }
        .drop-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.4rem;
        }
        .drop-sub {
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        .drop-sub span {
            color: var(--accent);
            font-weight: 600;
        }

        /* Selected file state */
        .drop-zone.has-file .drop-icon {
            color: #10b981;
            border-color: rgba(16, 185, 129, 0.3);
            background: rgba(16, 185, 129, 0.1);
        }
        .drop-zone.has-file { 
            border-color: #10b981; 
            background: rgba(16, 185, 129, 0.03); 
        }

        /* ── Info Footer ── */
        .drop-zone-footer {
            padding: 0.85rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.6rem;
        }
        .format-tags {
            display: flex;
            gap: 0.4rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .format-tag {
            font-size: 0.72rem;
            padding: 0.2rem 0.55rem;
            border-radius: 4px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        /* ── Flash Messages ── */
        .flash-success {
            background: rgba(22, 163, 74, 0.1);
            border: 1px solid rgba(22, 163, 74, 0.3);
            color: #4ade80;
            border-radius: var(--radius-sm);
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.25rem;
        }
        .flash-error {
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: #f87171;
            border-radius: var(--radius-sm);
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.25rem;
        }

        /* ── Upload button ── */
        .upload-btn-row {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
        }
        .file-chosen-label {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-style: italic;
        }
        .file-chosen-label.has-file {
            color: #4ade80;
            font-style: normal;
            font-weight: 500;
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
            <div class="import-wrapper wl-fade-up-2">

                <!-- Breadcrumb -->
                <div class="import-breadcrumb">
                    <a href="<?= site_url('dashboard'); ?>">Dashboard</a>
                    <span class="sep"><i class="fas fa-chevron-right" style="font-size:9px;"></i></span>
                    <span>BPKB Search & Import</span>
                </div>

                <!-- Flash Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                <div class="flash-success">
                    <i class="fas fa-check-circle"></i>
                    <?= $this->session->flashdata('success'); ?>
                </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                <div class="flash-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $this->session->flashdata('error'); ?>
                </div>
                <?php endif; ?>

                <!-- Header -->
                <div class="import-header">
                    <div class="import-title-wrap">
                        <h1>
                            Import Transactions from CSV/Excel
                            <span class="import-badge">IMPORT</span>
                        </h1>
                        <p>Upload file Excel transaksi (format lama/CSV didukung) → Sinkronisasi data otomatis</p>
                    </div>
                </div>

                <!-- Drop Zone Card -->
                <form action="<?= site_url('import/upload_excel'); ?>" method="post" enctype="multipart/form-data" id="importForm">
                    <div class="drop-zone-card">
                        <div class="drop-zone" id="dropZone">
                            <input type="file" name="excel_file" id="fileInput" accept=".xlsx, .xls, .csv" required>
                            <div class="drop-icon" id="dropIcon">
                                <i class="fas fa-file-excel"></i>
                            </div>
                            <div class="drop-title" id="dropTitle">Drag &amp; drop file Excel/CSV ke sini</div>
                            <div class="drop-sub" id="dropSub">atau <span>klik untuk pilih file</span> (.xlsx, .xls, .csv maks 10 MB)</div>
                        </div>

                        <div class="drop-zone-footer">
                            <div class="format-tags">
                                <span style="font-size: 0.78rem; color: var(--text-muted); margin-right: 0.4rem;">Format:</span>
                                <span class="format-tag">.csv</span>
                                <span class="format-tag">.xlsx</span>
                                <span class="format-tag">.xls</span>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.4;">
                                <i class="fas fa-info-circle" style="margin-right: 0.3rem;"></i>
                                File harus memiliki header (mulai baris 2). Field wajib: <strong>customer, inv_no, chassis</strong>.<br>
                                Urutan kolom lengkap: sl_date, tahun, bulan, hari, customer, alamat, cust_phone, sales_name, spv, leasing, insurance, inv_no, code, type, chassis, price_list, discount, price_net, dp_amt, leasing_amt, description_2, tenor, ktp_no, do_status, tunai_kredit, model.
                            </div>
                        </div>

                        <div class="upload-btn-row">
                            <span class="file-chosen-label" id="fileLabel">Belum ada file dipilih</span>
                            <button type="button" class="wl-btn wl-btn-secondary wl-btn-sm" onclick="document.getElementById('fileInput').click()">
                                <i class="fas fa-folder-open"></i> Pilih File
                            </button>
                            <button type="submit" class="wl-btn wl-btn-primary wl-btn-sm" id="submitBtn" disabled style="opacity:0.5; cursor:not-allowed;">
                                <i class="fas fa-upload"></i> Upload &amp; Import
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </main>
    </div>

    <script src="<?= base_url('assets/js/wuling.js'); ?>"></script>
    <script>
        const fileInput  = document.getElementById('fileInput');
        const dropZone   = document.getElementById('dropZone');
        const dropIcon   = document.getElementById('dropIcon');
        const dropTitle  = document.getElementById('dropTitle');
        const dropSub    = document.getElementById('dropSub');
        const fileLabel  = document.getElementById('fileLabel');
        const submitBtn  = document.getElementById('submitBtn');

        function setFile(file) {
            if (!file) return;
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['xlsx', 'xls', 'csv'].includes(ext)) {
                dropZone.classList.remove('has-file');
                fileLabel.textContent = 'Format tidak valid!';
                fileLabel.classList.remove('has-file');
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
                return;
            }
            const sizeMB = (file.size / 1024 / 1024).toFixed(1);
            dropZone.classList.add('has-file');
            dropIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
            dropTitle.textContent = file.name;
            dropSub.textContent = sizeMB + ' MB';
            fileLabel.textContent = file.name;
            fileLabel.classList.add('has-file');
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }

        fileInput.addEventListener('change', () => setFile(fileInput.files[0]));

        // Drag & Drop
        dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            if (file) {
                // Transfer to input
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                setFile(file);
            }
        });
    </script>
</body>
</html>
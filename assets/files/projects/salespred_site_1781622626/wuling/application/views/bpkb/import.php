<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Import BPKB') ?> - Wuling System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css') ?>">
    <script>if(localStorage.getItem('theme')==='light')document.documentElement.setAttribute('data-theme','light');</script>
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
                <a href="<?= site_url('dashboard') ?>" style="color:var(--text-muted);text-decoration:none;">Dashboard</a>
                <i class="fas fa-chevron-right" style="font-size:0.65rem;"></i>
                <a href="<?= site_url('bpkb/search') ?>" style="color:var(--text-muted);text-decoration:none;">BPKB Tracking</a>
                <i class="fas fa-chevron-right" style="font-size:0.65rem;"></i>
                <span>Import Data</span>
            </div>

            <!-- Page Header -->
            <div class="wl-page-header wl-fade-up-2" style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <h2 class="wl-page-title" style="margin:0;"><?= htmlspecialchars($title ?? 'Import Data BPKB') ?></h2>
                    <p class="wl-page-subtitle" style="font-size:0.82rem;margin:0.2rem 0 0;">Upload file Excel/CSV untuk mengimpor data BPKB secara massal.</p>
                </div>
                <a href="<?= site_url('bpkb/search') ?>" class="wl-btn wl-btn-ghost">
                    <i class="fas fa-arrow-left" style="margin-right:0.4rem;"></i> Kembali
                </a>
            </div>

            <!-- Flash Messages -->
            <?php if($this->session->flashdata('success')): ?>
                <div class="wl-alert wl-alert-success wl-fade-up" style="margin-bottom:1rem;padding:0.65rem 1rem;">
                    <i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div class="wl-alert wl-alert-danger wl-fade-up" style="margin-bottom:1rem;padding:0.65rem 1rem;">
                    <i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Import Card -->
            <div class="wl-card wl-fade-up-3" style="max-width:600px;padding:2rem;">

                <!-- Template info -->
                <div style="background:rgba(var(--primary-rgb,59,130,246),0.08);border:1px solid rgba(var(--primary-rgb,59,130,246),0.2);border-radius:8px;padding:1rem;margin-bottom:1.5rem;">
                    <div style="font-weight:600;margin-bottom:0.5rem;font-size:0.9rem;"><i class="fas fa-info-circle" style="margin-right:0.4rem;"></i>Format Kolom Template</div>
                    <table style="width:100%;font-size:0.8rem;border-collapse:collapse;">
                        <tr><th style="text-align:left;padding:0.2rem 0.5rem;background:rgba(0,0,0,0.05);">Kolom</th><th style="text-align:left;padding:0.2rem 0.5rem;background:rgba(0,0,0,0.05);">Isi</th></tr>
                        <tr><td style="padding:0.2rem 0.5rem;">A</td><td style="padding:0.2rem 0.5rem;">No. Invoice</td></tr>
                        <tr style="background:rgba(0,0,0,0.03);"><td style="padding:0.2rem 0.5rem;">B</td><td style="padding:0.2rem 0.5rem;">Nama Customer</td></tr>
                        <tr><td style="padding:0.2rem 0.5rem;">C</td><td style="padding:0.2rem 0.5rem;">No. KTP</td></tr>
                        <tr style="background:rgba(0,0,0,0.03);"><td style="padding:0.2rem 0.5rem;">D</td><td style="padding:0.2rem 0.5rem;">No. Rangka (Chassis)</td></tr>
                        <tr><td style="padding:0.2rem 0.5rem;">E</td><td style="padding:0.2rem 0.5rem;">Model</td></tr>
                        <tr style="background:rgba(0,0,0,0.03);"><td style="padding:0.2rem 0.5rem;">F</td><td style="padding:0.2rem 0.5rem;">Tipe</td></tr>
                        <tr><td style="padding:0.2rem 0.5rem;">G</td><td style="padding:0.2rem 0.5rem;">Status DO (Delivered / Pending)</td></tr>
                    </table>
                </div>

                <!-- Upload Form (POST — site_url() is safe for POST forms) -->
                <form action="<?= site_url('bpkb/import') ?>" method="POST" enctype="multipart/form-data" class="wl-form">

                    <div class="wl-form-group">
                        <label class="wl-form-label">Pilih File (Excel .xlsx / .xls / CSV .csv) *</label>
                        <input type="file" name="file_excel" class="wl-input" required
                               accept=".csv,.xls,.xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel">
                        <small style="display:block;margin-top:0.4rem;color:var(--text-muted);font-size:0.78rem;">
                            Baris pertama dianggap sebagai header dan akan dilewati.
                        </small>
                    </div>

                    <div style="margin-top:2rem;display:flex;justify-content:flex-end;gap:0.75rem;">
                        <a href="<?= site_url('bpkb/search') ?>" class="wl-btn wl-btn-ghost">Batal</a>
                        <button type="submit" class="wl-btn wl-btn-primary">
                            <i class="fas fa-upload" style="margin-right:0.4rem;"></i> Import Data
                        </button>
                    </div>

                </form>
            </div>

        </main>
    </div>

    <script src="<?= base_url('assets/js/theme.js') ?>"></script>
</body>
</html>

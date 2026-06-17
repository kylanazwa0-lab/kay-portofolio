<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kendaraan - Wuling System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">

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
            <div class="wl-page-header wl-fade-up-2 wl-flex wl-items-center wl-justify-between">
                <div>
                    <h1 class="wl-page-title">Detail Kendaraan</h1>
                    <p class="wl-page-subtitle">Informasi lengkap spesifikasi kendaraan.</p>
                </div>
                <div class="wl-flex wl-gap-2">
                    <a href="<?= site_url('kendaraan'); ?>" class="wl-btn wl-btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="<?= site_url('kendaraan/edit/'.$kendaraan->id); ?>" class="wl-btn wl-btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>

            <!-- Main Card -->
            <div class="wl-card wl-fade-up-3">
                <div class="wl-card-header wl-flex wl-justify-between wl-items-center">
                    <h2 class="wl-card-title"><i class="fas fa-car-side"></i> <?= htmlspecialchars($kendaraan->model_name); ?></h2>
                    <span class="wl-badge wl-badge-primary"><?= htmlspecialchars($kendaraan->category); ?></span>
                </div>
                
                <div class="wl-grid-2">
                    <!-- Column 1 -->
                    <div class="wl-flex wl-flex-col wl-gap-4">
                        <div class="wl-card" style="background: var(--surface);">
                            <div class="wl-flex wl-items-center wl-gap-3">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(124,58,237,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                                <div>
                                    <div class="wl-text-xs wl-text-muted wl-mb-1" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">ID Kendaraan</div>
                                    <div class="wl-text-lg wl-font-bold"><?= $kendaraan->id; ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="wl-card" style="background: var(--surface);">
                            <div class="wl-flex wl-items-center wl-gap-3">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(16,185,129,0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div>
                                    <div class="wl-text-xs wl-text-muted wl-mb-1" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Harga</div>
                                    <div class="wl-text-xl wl-font-bold" style="color: #10b981;">Rp <?= number_format($kendaraan->price, 0, ',', '.'); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="wl-card" style="background: var(--surface);">
                            <div class="wl-flex wl-items-center wl-gap-3">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(245,158,11,0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <div>
                                    <div class="wl-text-xs wl-text-muted wl-mb-1" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Kategori</div>
                                    <div class="wl-text-lg wl-font-bold"><?= htmlspecialchars($kendaraan->category); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="wl-flex wl-flex-col wl-gap-4">
                        <div class="wl-card" style="background: var(--surface); height: 100%;">
                            <div class="wl-flex wl-gap-3">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(59,130,246,0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <div class="wl-text-xs wl-text-muted wl-mb-2" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Deskripsi</div>
                                    <p class="wl-text-sm" style="line-height: 1.6; color: var(--text-secondary);">
                                        <?= nl2br(htmlspecialchars($kendaraan->description)); ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="wl-grid-2">
                            <div class="wl-card" style="background: var(--surface); padding: 1rem;">
                                <div class="wl-text-xs wl-text-muted wl-mb-1">Tanggal Dibuat</div>
                                <div class="wl-text-sm wl-font-bold"><i class="far fa-calendar-plus wl-mr-1"></i> <?= date('d M Y, H:i', strtotime($kendaraan->created_at)); ?></div>
                            </div>
                            <div class="wl-card" style="background: var(--surface); padding: 1rem;">
                                <div class="wl-text-xs wl-text-muted wl-mb-1">Terakhir Diupdate</div>
                                <div class="wl-text-sm wl-font-bold"><i class="far fa-clock wl-mr-1"></i> <?= date('d M Y, H:i', strtotime($kendaraan->updated_at)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wl-flex wl-gap-2 wl-justify-end wl-mt-4" style="border-top: 1px solid var(--border); padding-top: 1rem;">
                    <a href="<?= site_url('kendaraan/hapus/'.$kendaraan->id); ?>" class="wl-btn wl-btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?');">
                        <i class="fas fa-trash"></i> Hapus Kendaraan
                    </a>
                </div>
            </div>
        </main>
    </div>


    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kendaraan - Wuling System</title>
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
            <!-- Flash Messages -->
            <?php if (validation_errors()): ?>
                <div class="wl-alert wl-alert-danger wl-fade-up">
                    <i class="fas fa-exclamation-triangle wl-alert-icon"></i>
                    <div class="wl-alert-body">
                        <div class="wl-alert-msg"><?= validation_errors(); ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="wl-page-header wl-fade-up-2 wl-flex wl-items-center wl-justify-between">
                <div>
                    <h1 class="wl-page-title">Tambah Kendaraan</h1>
                    <p class="wl-page-subtitle">Masukkan detail kendaraan Wuling baru ke sistem.</p>
                </div>
                <div>
                    <a href="<?= site_url('kendaraan'); ?>" class="wl-btn wl-btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Form Card -->
            <div class="wl-card wl-fade-up-3" style="max-width: 800px; margin: 0 auto;">
                <div class="wl-card-header">
                    <h2 class="wl-card-title"><i class="fas fa-plus"></i> Form Kendaraan Baru</h2>
                </div>

                <?= form_open('kendaraan/proses_tambah', ['id' => 'formKendaraan']); ?>
                    <div class="wl-grid-2">
                        <div class="wl-form-group">
                            <label class="wl-label">Nama Model <span class="wl-text-danger">*</span></label>
                            <input type="text" class="wl-input" id="model_name" name="model_name" placeholder="Contoh: Wuling Almaz RS" value="<?= set_value('model_name'); ?>" required>
                        </div>

                        <div class="wl-form-group">
                            <label class="wl-label">Kategori <span class="wl-text-danger">*</span></label>
                            <select class="wl-select" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $key => $value): ?>
                                    <option value="<?= $key; ?>" <?= set_select('category', $key); ?>><?= $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="wl-form-group">
                        <label class="wl-label">Harga <span class="wl-text-danger">*</span></label>
                        <div style="display: flex; align-items: center;">
                            <div style="background: var(--surface); border: 1px solid var(--border); border-right: none; padding: 0.75rem 1rem; border-radius: var(--radius) 0 0 var(--radius); color: var(--text-secondary);">Rp</div>
                            <input type="number" class="wl-input" id="price" name="price" style="border-radius: 0 var(--radius) var(--radius) 0;" value="<?= set_value('price'); ?>" min="0" required>
                        </div>
                        <span class="wl-text-muted wl-text-xs">Masukkan harga tanpa titik/koma</span>
                    </div>

                    <div class="wl-form-group">
                        <label class="wl-label">Deskripsi <span class="wl-text-danger">*</span></label>
                        <textarea class="wl-input" id="description" name="description" rows="4" placeholder="Deskripsi lengkap kendaraan..." required><?= set_value('description'); ?></textarea>
                    </div>

                    <div class="wl-flex wl-gap-2 wl-justify-end wl-mt-4" style="border-top: 1px solid var(--border); padding-top: 1rem;">
                        <button type="reset" class="wl-btn wl-btn-ghost">Reset</button>
                        <button type="submit" class="wl-btn wl-btn-primary"><i class="fas fa-save"></i> Simpan Data</button>
                    </div>
                <?= form_close(); ?>
            </div>
        </main>
    </div>


    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>
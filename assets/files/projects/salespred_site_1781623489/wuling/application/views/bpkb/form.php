<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Form BPKB') ?> - Wuling System</title>
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
                <span><?= htmlspecialchars($title ?? '') ?></span>
            </div>

            <!-- Page Header -->
            <div class="wl-page-header wl-fade-up-2" style="display:flex;justify-content:space-between;align-items:center;">
                <h2 class="wl-page-title" style="margin:0;"><?= htmlspecialchars($title ?? '') ?></h2>
                <a href="<?= site_url('bpkb/search') ?>" class="wl-btn wl-btn-ghost">
                    <i class="fas fa-arrow-left" style="margin-right:0.4rem;"></i> Kembali
                </a>
            </div>

            <?php
            // Support both object (from DB result()) and array
            $trx = isset($transaction) ? $transaction : null;
            $isEdit = !empty($trx);

            // Helper to get value — supports object or array
            function trx_val($trx, $key, $default = '') {
                if (!$trx) return $default;
                if (is_object($trx)) return $trx->$key ?? $default;
                return $trx[$key] ?? $default;
            }
            $trx_id = trx_val($trx, 'id');
            $form_action = $isEdit
                ? site_url('bpkb/update/' . $trx_id)
                : site_url('bpkb/store');
            ?>

            <!-- Flash errors -->
            <?php if($this->session->flashdata('error')): ?>
                <div class="wl-alert wl-alert-danger wl-fade-up" style="margin-bottom:1rem;padding:0.65rem 1rem;">
                    <i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>
            <?php if(validation_errors()): ?>
                <div class="wl-alert wl-alert-danger wl-fade-up" style="margin-bottom:1rem;padding:0.65rem 1rem;">
                    <i class="fas fa-exclamation-circle"></i> <?= validation_errors() ?>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="wl-card wl-fade-up-3" style="max-width:800px;padding:2rem;">
                <form action="<?= $form_action ?>" method="POST" class="wl-form">

                    <div class="wl-grid-2">
                        <div class="wl-form-group">
                            <label class="wl-form-label">No. Invoice *</label>
                            <input type="text" name="inv_no" class="wl-input" required
                                   value="<?= htmlspecialchars(set_value('inv_no', trx_val($trx, 'inv_no'))) ?>"
                                   placeholder="Contoh: F.UN/4644/0421/0031">
                        </div>

                        <div class="wl-form-group">
                            <label class="wl-form-label">No. Rangka (Chassis) *</label>
                            <input type="text" name="chassis" class="wl-input" required
                                   value="<?= htmlspecialchars(set_value('chassis', trx_val($trx, 'chassis'))) ?>"
                                   placeholder="Masukkan No. Rangka">
                        </div>
                    </div>

                    <div class="wl-grid-2">
                        <div class="wl-form-group">
                            <label class="wl-form-label">Nama Customer *</label>
                            <input type="text" name="customer" class="wl-input" required
                                   value="<?= htmlspecialchars(set_value('customer', trx_val($trx, 'customer'))) ?>"
                                   placeholder="Nama Lengkap Customer">
                        </div>

                        <div class="wl-form-group">
                            <label class="wl-form-label">No. KTP</label>
                            <input type="text" name="ktp_no" class="wl-input"
                                   value="<?= htmlspecialchars(set_value('ktp_no', trx_val($trx, 'ktp_no'))) ?>"
                                   placeholder="NIK / No. KTP">
                        </div>
                    </div>

                    <div class="wl-grid-2">
                        <div class="wl-form-group">
                            <label class="wl-form-label">Model</label>
                            <input type="text" name="model" class="wl-input"
                                   value="<?= htmlspecialchars(set_value('model', trx_val($trx, 'model'))) ?>"
                                   placeholder="Contoh: CORTEZ, ALMAZ">
                        </div>

                        <div class="wl-form-group">
                            <label class="wl-form-label">Tipe</label>
                            <input type="text" name="type" class="wl-input"
                                   value="<?= htmlspecialchars(set_value('type', trx_val($trx, 'type'))) ?>"
                                   placeholder="Contoh: 1.5 MT DB MY 8P">
                        </div>
                    </div>

                    <div class="wl-form-group">
                        <label class="wl-form-label">Status DO *</label>
                        <select name="do_status" class="wl-input" required>
                            <?php
                            $cur_status = set_value('do_status', trx_val($trx, 'do_status', '0'));
                            ?>
                            <option value="0" <?= ($cur_status == '0' || $cur_status === 0) ? 'selected' : '' ?>>Pending</option>
                            <option value="1" <?= ($cur_status == '1' || $cur_status === 1) ? 'selected' : '' ?>>Delivered</option>
                        </select>
                    </div>

                    <div style="margin-top:2rem;display:flex;justify-content:flex-end;gap:0.75rem;">
                        <a href="<?= site_url('bpkb/search') ?>" class="wl-btn wl-btn-ghost">Batal</a>
                        <button type="submit" class="wl-btn wl-btn-primary">
                            <i class="fas fa-save" style="margin-right:0.4rem;"></i>
                            <?= $isEdit ? 'Update Data' : 'Simpan Data' ?>
                        </button>
                    </div>

                </form>
            </div>

        </main>
    </div>

    <script src="<?= base_url('assets/js/theme.js') ?>"></script>
</body>
</html>

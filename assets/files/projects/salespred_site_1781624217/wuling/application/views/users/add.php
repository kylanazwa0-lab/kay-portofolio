<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> - Wuling System</title>
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
            <?php if ($this->session->flashdata('success')): ?>
                <div class="wl-alert wl-alert-success wl-fade-up">
                    <i class="fas fa-check-circle wl-alert-icon"></i>
                    <div class="wl-alert-body">
                        <div class="wl-alert-msg"><?= $this->session->flashdata('success'); ?></div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($this->session->flashdata('error')): ?>
                <div class="wl-alert wl-alert-danger wl-fade-up">
                    <i class="fas fa-exclamation-triangle wl-alert-icon"></i>
                    <div class="wl-alert-body">
                        <div class="wl-alert-msg"><?= $this->session->flashdata('error'); ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="wl-page-header wl-fade-up-2 wl-flex wl-items-center wl-justify-between">
                <div>
                    <h1 class="wl-page-title"><?= $title; ?></h1>
                    <p class="wl-page-subtitle">Tambahkan pengguna baru ke dalam sistem.</p>
                </div>
                <div>
                    <a href="<?= site_url('users'); ?>" class="wl-btn wl-btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Add Form -->
            <div class="wl-card wl-fade-up-3" style="max-width: 800px; margin: 0 auto;">
                <div class="wl-card-header">
                    <h2 class="wl-card-title"><i class="fas fa-user-plus"></i> Form Tambah Pengguna</h2>
                </div>
                
                <?= form_open('users/add_process', ['id' => 'addUserForm']); ?>
                    <div class="wl-form-group">
                        <label class="wl-label">Username <span class="wl-text-danger">*</span></label>
                        <input type="text" class="wl-input" id="username" name="username" placeholder="Masukkan username unik" value="<?= set_value('username'); ?>" required>
                        <?php if (form_error('username')): ?><div class="wl-form-error"><?= form_error('username'); ?></div><?php endif; ?>
                    </div>

                    <div class="wl-form-group">
                        <label class="wl-label">Nama Lengkap <span class="wl-text-danger">*</span></label>
                        <input type="text" class="wl-input" id="full_name" name="full_name" placeholder="Nama Lengkap" value="<?= set_value('full_name'); ?>" required>
                        <?php if (form_error('full_name')): ?><div class="wl-form-error"><?= form_error('full_name'); ?></div><?php endif; ?>
                    </div>

                    <div class="wl-form-group">
                        <label class="wl-label">Email <span class="wl-text-danger">*</span></label>
                        <input type="email" class="wl-input" id="email" name="email" placeholder="contoh@wuling.com" value="<?= set_value('email'); ?>" required>
                        <?php if (form_error('email')): ?><div class="wl-form-error"><?= form_error('email'); ?></div><?php endif; ?>
                    </div>

                    <div class="wl-grid-2">
                        <div class="wl-form-group">
                            <label class="wl-label">Password <span class="wl-text-danger">*</span></label>
                            <input type="password" class="wl-input" id="password" name="password" placeholder="Minimal 8 karakter" required>
                            <?php if (form_error('password')): ?><div class="wl-form-error"><?= form_error('password'); ?></div><?php endif; ?>
                        </div>
                        <div class="wl-form-group">
                            <label class="wl-label">Konfirmasi Password <span class="wl-text-danger">*</span></label>
                            <input type="password" class="wl-input" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
                            <?php if (form_error('confirm_password')): ?><div class="wl-form-error"><?= form_error('confirm_password'); ?></div><?php endif; ?>
                        </div>
                    </div>

                    <div class="wl-form-group">
                        <label class="wl-label">Role <span class="wl-text-danger">*</span></label>
                        <select class="wl-select" id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <?php foreach ($roles as $role_key => $role_name): ?>
                                <option value="<?= $role_key; ?>" <?= set_select('role', $role_key); ?>><?= $role_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (form_error('role')): ?><div class="wl-form-error"><?= form_error('role'); ?></div><?php endif; ?>
                    </div>

                    <div class="wl-form-group">
                        <label class="wl-label">Status <span class="wl-text-danger">*</span></label>
                        <select class="wl-select" id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="active" <?= set_select('status', 'active', true); ?>>Aktif</option>
                            <option value="inactive" <?= set_select('status', 'inactive'); ?>>Nonaktif</option>
                        </select>
                        <?php if (form_error('status')): ?><div class="wl-form-error"><?= form_error('status'); ?></div><?php endif; ?>
                    </div>

                    <div class="wl-flex wl-gap-2 wl-justify-end wl-mt-4" style="border-top: 1px solid var(--border); padding-top: 1rem;">
                        <button type="reset" class="wl-btn wl-btn-ghost">Reset</button>
                        <button type="submit" class="wl-btn wl-btn-primary"><i class="fas fa-save"></i> Simpan Pengguna</button>
                    </div>
                <?= form_close(); ?>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            const pw = document.getElementById('password').value;
            const cpw = document.getElementById('confirm_password').value;
            if (pw !== cpw) {
                e.preventDefault();
                alert('Password dan konfirmasi tidak cocok!');
            }
        });
        
        setTimeout(() => {
            document.querySelectorAll('.wl-alert').forEach(a => a.style.display = 'none');
        }, 5000);
    </script>

    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>
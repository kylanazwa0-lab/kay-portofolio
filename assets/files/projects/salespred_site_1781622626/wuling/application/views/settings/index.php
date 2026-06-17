<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun — Wuling System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">
    <script>if(localStorage.getItem('theme') === 'light') document.documentElement.setAttribute('data-theme', 'light');</script>
    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }
        @media (max-width: 768px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }
        .settings-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 2rem;
        }
        .settings-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .settings-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        .settings-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <?php $this->load->view('templates/navbar'); ?>

    <div class="wl-layout">
        <?php $this->load->view('templates/sidebar'); ?>

        <main class="wl-main">
            
            <div class="wl-page-header wl-fade-up">
                <h1 class="wl-page-title">Pengaturan Akun</h1>
                <p class="wl-page-subtitle">Kelola profil dan preferensi keamanan Anda.</p>
            </div>

            <div class="settings-grid wl-fade-up-1">
                
                <!-- Profil Card -->
                <div class="settings-card">
                    <div class="settings-header">
                        <div class="settings-title">Informasi Profil</div>
                        <div class="settings-subtitle">Perbarui nama, username, dan email Anda.</div>
                    </div>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="wl-alert wl-alert-success wl-mb-4">
                            <i class="fas fa-check-circle wl-alert-icon"></i>
                            <div class="wl-alert-body"><div class="wl-alert-msg"><?= $this->session->flashdata('success'); ?></div></div>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="wl-alert wl-alert-danger wl-mb-4">
                            <i class="fas fa-exclamation-triangle wl-alert-icon"></i>
                            <div class="wl-alert-body"><div class="wl-alert-msg"><?= $this->session->flashdata('error'); ?></div></div>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('settings/update_profile'); ?>" method="POST">
                        <div class="wl-form-group">
                            <label class="wl-form-label">Nama Lengkap</label>
                            <input type="text" name="full_name" class="wl-input" value="<?= set_value('full_name', $user->full_name); ?>" required>
                        </div>
                        <div class="wl-form-group">
                            <label class="wl-form-label">Username</label>
                            <input type="text" name="username" class="wl-input" value="<?= set_value('username', $user->username); ?>" required>
                        </div>
                        <div class="wl-form-group">
                            <label class="wl-form-label">Email</label>
                            <input type="email" name="email" class="wl-input" value="<?= set_value('email', $user->email); ?>" required>
                        </div>
                        <div class="wl-form-group">
                            <label class="wl-form-label">Role Akses</label>
                            <input type="text" class="wl-input" value="<?= ucwords(str_replace('_', ' ', $user->role)); ?>" disabled style="opacity:0.7; cursor:not-allowed;">
                        </div>
                        
                        <div class="wl-mt-6">
                            <button type="submit" class="wl-btn wl-btn-primary">Simpan Profil</button>
                        </div>
                    </form>
                </div>

                <!-- Password Card -->
                <div class="settings-card">
                    <div class="settings-header">
                        <div class="settings-title">Ubah Password</div>
                        <div class="settings-subtitle">Pastikan akun Anda menggunakan password yang kuat.</div>
                    </div>

                    <?php if ($this->session->flashdata('success_password')): ?>
                        <div class="wl-alert wl-alert-success wl-mb-4">
                            <i class="fas fa-check-circle wl-alert-icon"></i>
                            <div class="wl-alert-body"><div class="wl-alert-msg"><?= $this->session->flashdata('success_password'); ?></div></div>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error_password')): ?>
                        <div class="wl-alert wl-alert-danger wl-mb-4">
                            <i class="fas fa-exclamation-triangle wl-alert-icon"></i>
                            <div class="wl-alert-body"><div class="wl-alert-msg"><?= $this->session->flashdata('error_password'); ?></div></div>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('settings/update_password'); ?>" method="POST">
                        <div class="wl-form-group">
                            <label class="wl-form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="wl-input" required>
                        </div>
                        <div class="wl-form-group">
                            <label class="wl-form-label">Password Baru</label>
                            <input type="password" name="new_password" class="wl-input" required minlength="6">
                            <small style="color:var(--text-muted); font-size:0.75rem; margin-top:0.25rem; display:block;">Minimal 6 karakter.</small>
                        </div>
                        <div class="wl-form-group">
                            <label class="wl-form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" class="wl-input" required minlength="6">
                        </div>
                        
                        <div class="wl-mt-6">
                            <button type="submit" class="wl-btn wl-btn-warning">Perbarui Password</button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
    <script>
        setTimeout(() => {
            document.querySelectorAll('.wl-alert').forEach(a => {
                a.style.transition = 'opacity 0.3s';
                a.style.opacity = '0';
                setTimeout(() => a.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>

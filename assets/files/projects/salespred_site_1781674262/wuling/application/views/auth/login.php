<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Wuling Management System</title>
    
    <!-- Link Font Awesome (Untuk Icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">

    <style>
        /* Modern Login Enhancements */
        body {
            margin: 0;
        }
        .wl-login-wrapper {
            min-height: 100vh;
            overflow: hidden; /* Mencegah scrollbar utama */
        }
        .wl-login-right {
            overflow-y: auto; /* Izinkan scroll hanya di area form jika layar sangat kecil */
        }
        .wl-login-box {
            background: rgba(30, 30, 30, 0.6) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 24px !important;
            box-shadow: 
                0 30px 60px -12px rgba(0, 0, 0, 0.6),
                inset 0 1px 1px rgba(255, 255, 255, 0.15),
                inset 0 -1px 1px rgba(0, 0, 0, 0.4) !important;
            padding: 2.5rem !important; /* Kurangi padding agar tidak kepanjangan */
        }
        .wl-input {
            background: rgba(15, 15, 15, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 12px !important;
            transition: all 0.3s ease !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2) !important;
        }
        .wl-input:focus {
            background: rgba(20, 20, 20, 0.8) !important;
            border-color: var(--accent) !important;
            box-shadow: 
                0 0 0 3px rgba(220, 38, 38, 0.15),
                inset 0 2px 4px rgba(0, 0, 0, 0.2) !important;
        }
        .wl-login-btn {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px !important;
            box-shadow: 
                0 4px 15px rgba(220, 38, 38, 0.3),
                inset 0 1px 1px rgba(255, 255, 255, 0.2) !important;
            transition: all 0.3s ease !important;
        }
        .wl-login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 6px 20px rgba(220, 38, 38, 0.4),
                inset 0 1px 1px rgba(255, 255, 255, 0.3) !important;
        }

        /* Light Theme Overrides */
        [data-theme="light"] .wl-login-box {
            background: rgba(255, 255, 255, 0.6) !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            box-shadow: 
                0 30px 60px -12px rgba(0, 0, 0, 0.1),
                inset 0 1px 1px rgba(255, 255, 255, 0.8),
                inset 0 -1px 1px rgba(0, 0, 0, 0.05) !important;
        }
        [data-theme="light"] .wl-input {
            background: rgba(255, 255, 255, 0.5) !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05) !important;
        }
        [data-theme="light"] .wl-input:focus {
            background: rgba(255, 255, 255, 0.8) !important;
            border-color: var(--accent) !important;
            box-shadow: 
                0 0 0 3px rgba(220, 38, 38, 0.15),
                inset 0 2px 4px rgba(0, 0, 0, 0.05) !important;
        }
    </style>

    <script>if(localStorage.getItem('theme') === 'light') document.documentElement.setAttribute('data-theme', 'light');</script>
</head>
<body>

<div class="wl-login-wrapper">
    <!-- Kolom Kiri: Visual/Branding -->
    <div class="wl-login-left">
        <div class="wl-login-bg-text">WULING</div>
        <div class="wl-login-logo-wrap wl-fade-up">
            <img src="<?= base_url('assets/images/wuling_log.jpg'); ?>" alt="Wuling Logo" onerror="this.src='https://via.placeholder.com/180x60/1c1c1c/f5f5f5?text=WULING+LOGO';">
            <h1 class="wl-login-tagline wl-mt-4">Drive For A <span>Better</span> Life.</h1>
            <p class="wl-login-desc">Enterprise Management & Sales System</p>
            
            <!-- Lini Wuling EV Family -->
            <div style="margin-top: 3rem; display: flex; flex-direction: column; gap: 0.75rem; text-align: left; padding: 0 1.5rem;">
                <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--accent);">Lini EV & Smart Innovation</div>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <span class="wl-badge wl-badge-blue" style="font-size: 0.65rem; padding: 0.2rem 0.6rem; border: 1px solid var(--accent-border);">Wuling Air ev</span>
                    <span class="wl-badge wl-badge-blue" style="font-size: 0.65rem; padding: 0.2rem 0.6rem; border: 1px solid var(--accent-border);">Binguo EV</span>
                    <span class="wl-badge wl-badge-blue" style="font-size: 0.65rem; padding: 0.2rem 0.6rem; border: 1px solid var(--accent-border);">Cloud EV</span>
                    <span class="wl-badge wl-badge-muted" style="font-size: 0.65rem; padding: 0.2rem 0.6rem;">WIND Ecosystem</span>
                    <span class="wl-badge wl-badge-muted" style="font-size: 0.65rem; padding: 0.2rem 0.6rem;">ADAS Tech</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Form Login -->
    <div class="wl-login-right">
        <div class="wl-login-box wl-fade-up-2">
            <h2 class="wl-login-heading">Welcome Back.</h2>
            <p class="wl-login-sub">Please enter your credentials to access the system.</p>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="wl-alert wl-alert-danger">
                    <i class="fas fa-exclamation-circle wl-alert-icon"></i>
                    <div class="wl-alert-body">
                        <div class="wl-alert-msg"><?= $this->session->flashdata('error'); ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('info')): ?>
                <div class="wl-alert wl-alert-info">
                    <i class="fas fa-info-circle wl-alert-icon"></i>
                    <div class="wl-alert-body">
                        <div class="wl-alert-msg"><?= $this->session->flashdata('info'); ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <?php echo form_open('auth/login'); ?>
                <div class="wl-form-group">
                    <label for="username" class="wl-label">Username</label>
                    <input type="text" class="wl-input" id="username" name="username" placeholder="Enter your username" value="<?php echo isset($_COOKIE['remember_username']) ? htmlspecialchars($_COOKIE['remember_username']) : set_value('username'); ?>" autocomplete="off" required>
                    <?php echo form_error('username', '<div class="wl-form-error"><i class="fas fa-times-circle"></i> ', '</div>'); ?>
                </div>

                <div class="wl-form-group">
                    <label for="password" class="wl-label">Password</label>
                    <div style="position: relative;">
                        <input type="password" class="wl-input" id="password" name="password" placeholder="••••••••" value="<?php echo isset($_COOKIE['remember_password']) ? htmlspecialchars($_COOKIE['remember_password']) : ''; ?>" required style="padding-right: 40px;">
                        <button type="button" id="togglePassword" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center; height: 100%;">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <?php echo form_error('password', '<div class="wl-form-error"><i class="fas fa-times-circle"></i> ', '</div>'); ?>
                </div>

                <div class="wl-form-group wl-mt-3" style="display: flex; align-items: center; justify-content: space-between;">
                    <label class="wl-flex wl-items-center" style="cursor: pointer; gap: 0.5rem;">
                        <input type="checkbox" name="remember" class="wl-checkbox" <?php echo isset($_COOKIE['remember_username']) ? 'checked' : ''; ?>>
                        <span class="wl-text-sm wl-text-muted">Remember me</span>
                    </label>
                    <a href="#" class="wl-text-sm wl-text-muted" style="text-decoration: underline; transition: color 0.2s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-muted)'">Forgot password?</a>
                </div>

                <div class="wl-form-group wl-mt-4">
                    <button type="submit" class="wl-login-btn">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>


    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
    <script>
        // Show/Hide Password Toggle
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function (e) {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle the eye / eye-slash icon
            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
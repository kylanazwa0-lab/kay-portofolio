    <!-- Navbar -->
    <nav class="wl-navbar wl-fade-up">
        <div style="display:flex;align-items:center;gap:1rem;">
            <button id="sidebarToggle" class="wl-sidebar-toggle" title="Toggle Sidebar" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
            <a href="<?= site_url('dashboard'); ?>" class="wl-navbar-brand">
            <img src="<?= base_url('assets/images/logo_transparent.png'); ?>" alt="Wuling Logo" style="height: 32px; object-fit: contain;">
            </a>
        </div>
        <div class="wl-navbar-right">
            <button id="themeToggle" class="wl-btn wl-btn-sm" style="background: transparent; border: 1px solid var(--border); color: var(--text-primary); margin-right: 0.5rem;" title="Toggle Theme"><i class="fas fa-sun"></i></button>
            <div class="wl-user-badge">
                <?php 
                $user_name = $this->session->userdata('full_name') ?? $this->session->userdata('nama') ?? 'User';
                $user_initial = strtoupper(substr($user_name, 0, 1));
                ?>
                <div class="avatar"><?= $user_initial; ?></div>
                <?= $user_name; ?>
            </div>
        </div>
    </nav>

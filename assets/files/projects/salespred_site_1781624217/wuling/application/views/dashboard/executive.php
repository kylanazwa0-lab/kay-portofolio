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

            <!-- Welcome Header -->
            <div class="wl-welcome-header wl-fade-up-2">
                <i class="fas fa-shield-alt wl-welcome-icon"></i>
                <h2>Welcome, <?= $this->session->userdata('full_name'); ?>.</h2>
                <p>Logged in as <strong style="color: var(--accent);"><?= $role_name; ?></strong>. Menganalisa laporan dan performa.</p>
            </div>

            <!-- Statistics Strip -->
            <div class="wl-fade-up-2" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: var(--border); border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; margin-bottom: 1.5rem;">
                <div style="background: var(--bg-card); padding: 1rem 1.5rem; display: flex; flex-direction: column; gap: 0.25rem;">
                    <div style="font-size: 1.5rem; font-weight: 800; font-family: var(--font-display); color: var(--accent); line-height: 1;"><?= isset($total_users) ? number_format($total_users) : 0 ?></div>
                    <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted);">Total Users</div>
                </div>
                <div style="background: var(--bg-card); padding: 1rem 1.5rem; display: flex; flex-direction: column; gap: 0.25rem;">
                    <div style="font-size: 1.5rem; font-weight: 800; font-family: var(--font-display); color: #8b5cf6; line-height: 1;"><?= isset($total_transactions) ? number_format($total_transactions) : 0 ?></div>
                    <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted);">Total Transaksi</div>
                </div>
                <div style="background: var(--bg-card); padding: 1rem 1.5rem; display: flex; flex-direction: column; gap: 0.25rem;">
                    <div style="font-size: 1.5rem; font-weight: 800; font-family: var(--font-display); color: #10b981; line-height: 1;">Rp <?= isset($total_revenue) ? number_format($total_revenue, 0, ',', '.') : 0 ?></div>
                    <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted);">Total Pendapatan</div>
                </div>
            </div>

            <!-- Menus -->
            <div class="wl-page-header wl-fade-up-3" style="margin-bottom: 1rem;">
                <h3 class="wl-page-title" style="font-size: var(--fs-xl);">Quick Actions</h3>
            </div>

            <div class="wl-menu-grid wl-fade-up-4">
                <?php foreach ($menu_items as $menu): ?>
                <a href="<?= site_url($menu['url']); ?>" style="text-decoration: none;">
                    <div class="wl-menu-card">
                        <div class="wl-menu-card-icon">
                            <i class="fas <?= $menu['icon']; ?>"></i>
                        </div>
                        <h5><?= $menu['title']; ?></h5>
                        <p><?= $menu['desc']; ?></p>
                        <i class="fas fa-arrow-right wl-menu-card-arrow"></i>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            
            <?php if(isset($top_sales) && !empty($top_sales)): ?>
            <!-- Top Sales Section -->
            <div class="wl-page-header wl-fade-up-5" style="margin-top: 3rem; margin-bottom: 1rem;">
                <h3 class="wl-page-title" style="font-size: var(--fs-xl);">Top Sales Performance</h3>
            </div>
            
            <div class="wl-card wl-fade-up-5" style="padding: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach($top_sales as $index => $sales): ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 1rem; border-bottom: 1px solid var(--border); <?= $index === count($top_sales)-1 ? 'border-bottom:none; padding-bottom:0;' : '' ?>">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                <?= $index + 1 ?>
                            </div>
                            <div>
                                <div style="font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($sales->sales_name ?? 'Unknown') ?></div>
                                <div style="font-size: 0.875rem; color: var(--text-muted);"><?= $sales->total_sales ?> Penjualan</div>
                            </div>
                        </div>
                        <div style="font-weight: 700; color: #10b981;">
                            Rp <?= number_format($sales->total_revenue ?? 0, 0, ',', '.') ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
    <script>
    (function() {
        var sidebar   = document.getElementById('mainSidebar');
        var toggleBtn = document.getElementById('sidebarToggle');
        var STORE_KEY = 'wl_sidebar_collapsed';
        function applyState(collapsed, animate) {
            if (!animate) sidebar.style.transition = 'none';
            sidebar.classList.toggle('wl-sidebar--collapsed', collapsed);
            if (!animate) setTimeout(function(){ sidebar.style.transition = ''; }, 50);
            localStorage.setItem(STORE_KEY, collapsed ? '1' : '0');
        }
        applyState(localStorage.getItem(STORE_KEY) === '1', false);
        toggleBtn.addEventListener('click', function(){
            applyState(!sidebar.classList.contains('wl-sidebar--collapsed'), true);
        });
    })();
    </script>
</body>
</html>
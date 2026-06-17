<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> - Wuling System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">
    
    <!-- Google Fonts: Outfit + JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --dash-bg:        var(--bg-base, #0f1117);
            --dash-surface:   var(--bg-card, #1a1d27);
            --dash-border:    var(--border, rgba(255,255,255,0.08));
            --dash-accent:    #cf2127;
            --dash-accent2:   #0ea5e9;
            --dash-gold:      #f59e0b;
            --dash-text:      var(--text-primary, #f4f4f5);
            --dash-muted:     var(--text-muted, #71717a);
            --dash-radius:    16px;
            --dash-font:      'Outfit', system-ui, sans-serif;
            --dash-mono:      'JetBrains Mono', monospace;
        }
        [data-theme="light"] {
            --dash-bg:        #f4f5f7;
            --dash-surface:   #ffffff;
            --dash-border:    rgba(0,0,0,0.08);
            --dash-text:      #09090b;
            --dash-muted:     #52525b;
        }

        body { font-family: var(--dash-font); }
        .dash-main { padding: 0 1.5rem 3rem; }

        /* Page header strip */
        .dash-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            padding: 1.75rem 0 1.25rem;
            border-bottom: 1px solid var(--dash-border);
            margin-bottom: 1.75rem;
            animation: dash-fadein 0.4s ease both;
        }
        .dash-header-eyebrow {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--dash-accent);
            margin-bottom: 0.3rem;
        }
        .dash-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            color: var(--dash-text);
            line-height: 1.1;
            margin: 0;
        }
        .dash-header-meta {
            font-size: 0.72rem;
            color: var(--dash-muted);
            margin-top: 0.35rem;
        }

        /* Top Metrics Row */
        .dash-metrics-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
            animation: dash-fadein 0.45s 0.1s ease both;
        }
        .dash-metric-card {
            background: var(--dash-surface);
            border: 1px solid var(--dash-border);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .dash-metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px -8px rgba(0,0,0,0.25);
            border-color: var(--dash-accent);
        }
        .dash-metric-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .icon-blue  { background: rgba(14,165,233,0.1); color: #0ea5e9; }
        .icon-green { background: rgba(34,197,94,0.1); color: #22c55e; }
        .icon-red   { background: rgba(207,33,39,0.1); color: var(--dash-accent); }
        .icon-amber { background: rgba(245,158,11,0.1); color: #f59e0b; }
        
        .dash-metric-info { flex: 1; }
        .dash-metric-label {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--dash-muted);
            margin-bottom: 0.15rem;
        }
        .dash-metric-val {
            font-family: var(--dash-mono);
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--dash-text);
            line-height: 1.1;
        }

        /* Middle Grid Layout */
        .dash-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
            animation: dash-fadein 0.45s 0.2s ease both;
        }
        @media (max-width: 900px) {
            .dash-metrics-row { grid-template-columns: 1fr; }
            .dash-grid { grid-template-columns: 1fr; }
        }

        /* Panel Common */
        .dash-panel {
            background: var(--dash-surface);
            border: 1px solid var(--dash-border);
            border-radius: 12px;
            padding: 1.25rem;
            height: 100%;
        }
        .dash-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .dash-panel-title {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: var(--dash-text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .dash-panel-title i { color: var(--dash-accent); }

        /* Quick Actions Grid */
        .qa-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        @media (max-width: 560px) { .qa-grid { grid-template-columns: 1fr; } }
        
        .qa-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--dash-border);
            border-radius: 10px;
            padding: 0.85rem;
            text-decoration: none;
            color: var(--dash-text);
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        [data-theme="light"] .qa-card { background: #fafafa; }
        .qa-card:hover {
            background: var(--dash-accent);
            border-color: var(--dash-accent);
            color: white;
            transform: translateY(-2px);
        }
        .qa-card:hover .qa-desc { color: rgba(255,255,255,0.8); }
        .qa-card:hover i { color: white; }
        .qa-card i {
            font-size: 1.1rem;
            color: var(--dash-accent2);
            margin-bottom: 0.2rem;
        }
        .qa-title { font-weight: 600; font-size: 0.8rem; }
        .qa-desc { font-size: 0.65rem; color: var(--dash-muted); line-height: 1.3; transition: color 0.2s;}

        /* Top Sales List */
        .ts-list {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        .ts-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 0.75rem;
            background: rgba(255,255,255,0.015);
            border: 1px solid var(--dash-border);
            border-radius: 8px;
        }
        .ts-name {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--dash-text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .ts-val {
            font-family: var(--dash-mono);
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--dash-gold);
        }

        @keyframes dash-fadein {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
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
            <div class="dash-main">

                <!-- Page Header -->
                <div class="dash-header">
                    <div>
                        <div class="dash-header-eyebrow">Control Center</div>
                        <h1>Administrator Head Dashboard</h1>
                        <div class="dash-header-meta">Logged in as <strong style="color:var(--dash-accent);"><?= $role_name ?></strong> &mdash; System Overview</div>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="wl-alert wl-alert-success" style="margin-bottom:1.5rem; border-radius:10px;">
                        <i class="fas fa-check-circle wl-alert-icon"></i>
                        <div class="wl-alert-body">
                            <div class="wl-alert-msg"><?= $this->session->flashdata('success'); ?></div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Top Metrics Row -->
                <div class="dash-metrics-row">
                    <div class="dash-metric-card">
                        <div class="dash-metric-icon icon-blue"><i class="fas fa-users"></i></div>
                        <div class="dash-metric-info">
                            <div class="dash-metric-label">Total Pengguna Aktif</div>
                            <div class="dash-metric-val"><?= number_format($total_users ?? 0) ?></div>
                        </div>
                    </div>
                    <div class="dash-metric-card">
                        <div class="dash-metric-icon icon-amber"><i class="fas fa-shopping-cart"></i></div>
                        <div class="dash-metric-info">
                            <div class="dash-metric-label">Total Transaksi</div>
                            <div class="dash-metric-val"><?= number_format($total_transactions ?? 0) ?></div>
                        </div>
                    </div>
                    <div class="dash-metric-card">
                        <div class="dash-metric-icon icon-green"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="dash-metric-info">
                            <div class="dash-metric-label">Total Revenue</div>
                            <div class="dash-metric-val" style="font-size:1.15rem;">Rp <?= number_format($total_revenue ?? 0, 0, ',', '.') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Grid Layout -->
                <div class="dash-grid">
                    
                    <!-- Quick Actions Panel -->
                    <div class="dash-panel">
                        <div class="dash-panel-header">
                            <div class="dash-panel-title">
                                <i class="fas fa-bolt"></i> Shortcut &amp; Navigasi Sistem
                            </div>
                        </div>
                        <div class="qa-grid">
                            <?php foreach ($menu_items as $menu): ?>
                            <a href="<?= site_url($menu['url']); ?>" class="qa-card">
                                <i class="fas <?= $menu['icon']; ?>"></i>
                                <div class="qa-title"><?= $menu['title']; ?></div>
                                <div class="qa-desc"><?= $menu['desc']; ?></div>
                            </a>
                            <?php endforeach; ?>
                            
                            <!-- Extra System Settings Shortcut -->
                            <a href="#" class="qa-card" style="border-style: dashed;">
                                <i class="fas fa-cog" style="color:var(--dash-muted);"></i>
                                <div class="qa-title">System Settings</div>
                                <div class="qa-desc">Konfigurasi parameter dan role (Coming Soon)</div>
                            </a>
                        </div>
                    </div>

                    <!-- Top Sales / Activity Panel -->
                    <div class="dash-panel">
                        <div class="dash-panel-header">
                            <div class="dash-panel-title">
                                <i class="fas fa-medal"></i> Top Sales Leaderboard
                            </div>
                        </div>
                        <div class="ts-list">
                            <?php if(!empty($top_sales)): ?>
                                <?php foreach($top_sales as $idx => $ts): ?>
                                <div class="ts-item">
                                    <div class="ts-name">
                                        <?php if($idx == 0): ?><i class="fas fa-crown" style="color:#f59e0b;"></i><?php else: ?><i class="fas fa-user-circle" style="color:var(--dash-muted);"></i><?php endif; ?>
                                        <?= htmlspecialchars($ts->sales_name) ?>
                                    </div>
                                    <div class="ts-val"><?= number_format($ts->total_transactions) ?> unit</div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="text-align:center;color:var(--dash-muted);padding:2rem 0;font-size:0.8rem;">
                                    Belum ada data penjualan.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/js/theme.js') ?>"></script>
</body>
</html>
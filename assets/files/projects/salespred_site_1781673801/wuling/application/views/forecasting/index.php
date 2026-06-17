<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peramalan Tren Penjualan — Wuling</title>
    <meta name="description" content="Analisis peramalan tren penjualan kendaraan Wuling menggunakan metode Simple Moving Average (SMA).">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>if(localStorage.getItem('theme')==='light') document.documentElement.setAttribute('data-theme','light');</script>
</head>
<body>

<!-- ── Navbar ─────────────────────────────────────────────── -->
<?php $this->load->view('templates/navbar'); ?>

<!-- ── Layout ─────────────────────────────────────────────── -->
<div class="wl-layout">

    <!-- Sidebar -->
    <?php $this->load->view('templates/sidebar'); ?>

    <!-- Main Content -->
    <main class="wl-main">

        <!-- Page Header -->
        <div class="wl-page-header wl-fade-up-2">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                <div>
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.35rem;">
                        <div style="width:36px;height:36px;border-radius:10px;background:var(--accent-glow);border:1px solid var(--accent-border);display:flex;align-items:center;justify-content:center;color:var(--accent);">
                            <i class="fas fa-chart-line" style="font-size:1rem;"></i>
                        </div>
                        <h1 class="wl-page-title">Peramalan Tren Penjualan</h1>
                    </div>
                    <p class="wl-page-subtitle">Metode <strong>Simple Moving Average (SMA-<?= $sel_n ?>)</strong> &mdash; PT Maju Global Motor</p>
                </div>
                <a href="<?= site_url('forecasting/export?year='.$sel_year.'&model='.$sel_model.'&periods='.$sel_n.'&forecast_months='.$sel_fm); ?>"
                   class="wl-btn wl-btn-sm" style="border:1px solid var(--border);color:var(--text-secondary);gap:0.5rem;">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="wl-card wl-fade-up-2" style="margin-bottom:1.5rem;">
            <form id="filterForm" method="GET" action="<?= site_url('forecasting'); ?>" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;align-items:end;">
                <div class="wl-form-group" style="margin:0;">
                    <label class="wl-form-label">Tahun</label>
                    <select name="year" class="wl-form-control" id="filterYear">
                        <option value="all" <?= $sel_year==='all'?'selected':''; ?>>Semua Tahun</option>
                        <?php foreach($years as $y): ?>
                        <option value="<?= $y['year'] ?>" <?= $sel_year==$y['year']?'selected':''; ?>><?= $y['year'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="wl-form-group" style="margin:0;">
                    <label class="wl-form-label">Model Kendaraan</label>
                    <select name="model" class="wl-form-control" id="filterModel">
                        <option value="all" <?= $sel_model==='all'?'selected':''; ?>>Semua Model</option>
                        <?php foreach($car_models as $m): ?>
                        <option value="<?= htmlspecialchars($m['model_name']) ?>" <?= $sel_model==$m['model_name']?'selected':''; ?>><?= htmlspecialchars($m['model_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="wl-form-group" style="margin:0;">
                    <label class="wl-form-label">Periode SMA (N)</label>
                    <select name="periods" class="wl-form-control" id="filterN">
                        <?php foreach([2,3,4,5,6] as $p): ?>
                        <option value="<?= $p ?>" <?= $sel_n==$p?'selected':''; ?>>SMA-<?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="wl-form-group" style="margin:0;">
                    <label class="wl-form-label">Proyeksi (Bulan)</label>
                    <select name="forecast_months" class="wl-form-control" id="filterFM">
                        <?php foreach([1,2,3,4,5,6] as $fm): ?>
                        <option value="<?= $fm ?>" <?= $sel_fm==$fm?'selected':''; ?>><?= $fm ?> Bulan</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display:flex;gap:0.5rem;">
                    <button type="submit" class="wl-btn wl-btn-primary" style="flex:1;">
                        <i class="fas fa-sync-alt" style="margin-right:0.4rem;"></i>Terapkan
                    </button>
                </div>
            </form>
        </div>

        <?php if(empty($raw_data)): ?>
        <!-- Empty State -->
        <div class="wl-card" style="text-align:center;padding:4rem 2rem;">
            <i class="fas fa-chart-line" style="font-size:3rem;color:var(--text-muted);margin-bottom:1rem;display:block;"></i>
            <h3 style="color:var(--text-secondary);font-size:1.2rem;">Belum Ada Data Transaksi</h3>
            <p style="color:var(--text-muted);margin-top:0.5rem;font-size:0.875rem;">Tambahkan data transaksi penjualan terlebih dahulu untuk melihat peramalan.</p>
            <a href="<?= site_url('transaction'); ?>" class="wl-btn wl-btn-primary" style="display:inline-flex;margin-top:1.5rem;gap:0.5rem;">
                <i class="fas fa-plus"></i> Input Transaksi
            </a>
        </div>

        <?php else: ?>

        <!-- Accuracy Metrics -->
        <?php if($accuracy): ?>
        <div class="wl-stat-grid wl-fade-up-3" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr));margin-bottom:1.5rem;">
            <div class="wl-stat-card">
                <div class="wl-stat-icon blue"><i class="fas fa-ruler-horizontal"></i></div>
                <div>
                    <div class="wl-stat-value" style="font-size:var(--fs-2xl);"><?= $accuracy['mae'] ?></div>
                    <div class="wl-stat-label">MAE (Mean Absolute Error)</div>
                </div>
            </div>
            <div class="wl-stat-card">
                <div class="wl-stat-icon orange"><i class="fas fa-superscript"></i></div>
                <div>
                    <div class="wl-stat-value" style="font-size:var(--fs-2xl);"><?= $accuracy['mse'] ?></div>
                    <div class="wl-stat-label">MSE (Mean Squared Error)</div>
                </div>
            </div>
            <div class="wl-stat-card">
                <div class="wl-stat-icon green"><i class="fas fa-square-root-alt"></i></div>
                <div>
                    <div class="wl-stat-value" style="font-size:var(--fs-2xl);"><?= $accuracy['rmse'] ?></div>
                    <div class="wl-stat-label">RMSE</div>
                </div>
            </div>
            <div class="wl-stat-card">
                <div class="wl-stat-icon red"><i class="fas fa-percent"></i></div>
                <div>
                    <div class="wl-stat-value" style="font-size:var(--fs-2xl);"><?= $accuracy['mape'] ?>%</div>
                    <div class="wl-stat-label">MAPE</div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Chart -->
        <div class="wl-card wl-fade-up-3" style="margin-bottom:1.5rem;">
            <div class="wl-card-header">
                <div>
                    <div class="wl-card-title">Grafik Tren Penjualan & SMA-<?= $sel_n ?></div>
                    <p style="color:var(--text-muted);font-size:var(--fs-xs);margin-top:0.2rem;">
                        Garis merah putus = proyeksi <?= $sel_fm ?> bulan ke depan
                    </p>
                </div>
                <div style="display:flex;gap:1rem;align-items:center;">
                    <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.75rem;color:var(--text-secondary);">
                        <div style="width:20px;height:3px;background:#0ea5e9;border-radius:2px;"></div> Aktual
                    </div>
                    <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.75rem;color:var(--text-secondary);">
                        <div style="width:20px;height:3px;background:#10b981;border-radius:2px;"></div> SMA-<?= $sel_n ?>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.75rem;color:var(--text-secondary);">
                        <div style="width:20px;height:3px;background:#cf2127;border-radius:2px;border-top:3px dashed #cf2127;border-top:none;"></div> Proyeksi
                    </div>
                </div>
            </div>
            <div style="position:relative;height:360px;">
                <canvas id="forecastChart"></canvas>
            </div>
        </div>

        <!-- Data Table + Forecast Table -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.5rem;" class="wl-fade-up-3">

            <!-- SMA Data Table -->
            <div class="wl-card" style="overflow:hidden;">
                <div class="wl-card-header">
                    <div class="wl-card-title">Tabel SMA-<?= $sel_n ?></div>
                    <span style="font-size:var(--fs-xs);color:var(--text-muted);"><?= count($sma_data) ?> periode</span>
                </div>
                <div class="wl-table-wrapper" style="overflow-x:auto;margin:-2rem;margin-top:0;padding:0;">
                    <table class="wl-table" style="border-radius:0;">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th style="text-align:right;">Aktual</th>
                                <th style="text-align:right;">SMA-<?= $sel_n ?></th>
                                <th style="text-align:right;">Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($sma_data as $row): ?>
                            <tr>
                                <td style="font-size:var(--fs-xs);font-weight:600;"><?= $row['label'] ?></td>
                                <td style="text-align:right;font-family:var(--font-mono);font-size:var(--fs-sm);"><?= $row['actual'] ?></td>
                                <td style="text-align:right;font-family:var(--font-mono);font-size:var(--fs-sm);color:var(--info);">
                                    <?= $row['sma'] !== null ? $row['sma'] : '<span style="color:var(--text-muted);">—</span>' ?>
                                </td>
                                <td style="text-align:right;font-family:var(--font-mono);font-size:var(--fs-sm);">
                                    <?php if($row['forecast_error'] !== null): ?>
                                        <?php $err = $row['forecast_error']; ?>
                                        <span style="color:<?= $err > 0 ? 'var(--success)' : ($err < 0 ? 'var(--danger)' : 'var(--text-muted)') ?>;">
                                            <?= ($err > 0 ? '+' : '') . $err ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color:var(--text-muted);">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Forecast Table -->
            <div class="wl-card" style="overflow:hidden;">
                <div class="wl-card-header">
                    <div class="wl-card-title">Proyeksi <?= $sel_fm ?> Bulan ke Depan</div>
                    <div style="padding:0.25rem 0.65rem;border-radius:99px;background:var(--accent-glow);border:1px solid var(--accent-border);font-size:var(--fs-xs);color:var(--accent);font-weight:600;">
                        SMA-<?= $sel_n ?>
                    </div>
                </div>

                <?php if(empty($forecasts)): ?>
                <div style="text-align:center;padding:2rem;color:var(--text-muted);">
                    <i class="fas fa-exclamation-circle" style="font-size:1.5rem;margin-bottom:0.5rem;display:block;"></i>
                    Data tidak cukup untuk proyeksi.<br>
                    <small>Minimal diperlukan <?= $sel_n ?> periode data.</small>
                </div>
                <?php else: ?>
                <div class="wl-table-wrapper" style="overflow-x:auto;margin:-2rem;margin-top:0;padding:0;">
                    <table class="wl-table" style="border-radius:0;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Bulan Proyeksi</th>
                                <th style="text-align:right;">Perkiraan Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($forecasts as $i => $row): ?>
                            <tr>
                                <td style="color:var(--text-muted);font-size:var(--fs-xs);"><?= $i + 1 ?></td>
                                <td style="font-weight:600;font-size:var(--fs-sm);"><?= $row['label'] ?></td>
                                <td style="text-align:right;">
                                    <span style="
                                        display:inline-flex;align-items:center;gap:0.4rem;
                                        background:var(--accent-glow);border:1px solid var(--accent-border);
                                        color:var(--accent-hover);font-weight:700;font-family:var(--font-mono);
                                        padding:0.3rem 0.85rem;border-radius:99px;font-size:var(--fs-sm);">
                                        <i class="fas fa-car" style="font-size:0.65rem;"></i>
                                        <?= $row['forecast'] ?> unit
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Summary box -->
                <div style="
                    margin-top:1.25rem;padding:1rem;
                    border-radius:var(--radius);
                    background:var(--bg-elevated);
                    border:1px solid var(--border);
                    display:flex;align-items:center;gap:0.75rem;">
                    <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:var(--accent-glow);border:1px solid var(--accent-border);display:flex;align-items:center;justify-content:center;color:var(--accent);flex-shrink:0;">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div style="font-size:var(--fs-xs);color:var(--text-secondary);line-height:1.5;">
                        Proyeksi dihitung menggunakan rata-rata <?= $sel_n ?> periode data terakhir.
                        Nilai proyeksi bersifat statis (SMA tidak iteratif per bulan).
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php endif; ?>
    </main>
</div>

<script>
/* ── Sidebar Toggle ──────────────────────────────────────── */
(function() {
    const sidebar    = document.getElementById('mainSidebar');
    const toggleBtn  = document.getElementById('sidebarToggle');
    const STORE_KEY  = 'wl_sidebar_collapsed';

    function applyState(collapsed, animate) {
        if (!animate) sidebar.style.transition = 'none';
        sidebar.classList.toggle('wl-sidebar--collapsed', collapsed);
        if (!animate) setTimeout(() => sidebar.style.transition = '', 50);
        localStorage.setItem(STORE_KEY, collapsed ? '1' : '0');
    }

    // Restore saved state
    const saved = localStorage.getItem(STORE_KEY) === '1';
    applyState(saved, false);

    toggleBtn.addEventListener('click', () => {
        applyState(!sidebar.classList.contains('wl-sidebar--collapsed'), true);
    });
})();

/* ── Theme Toggle ────────────────────────────────────────── */
(function() {
    const btn  = document.getElementById('themeToggle');
    const html = document.documentElement;
    const icon = btn.querySelector('i');
    function sync() {
        const isLight = html.getAttribute('data-theme') === 'light';
        icon.className = isLight ? 'fas fa-moon' : 'fas fa-sun';
    }
    sync();
    btn.addEventListener('click', () => {
        const isLight = html.getAttribute('data-theme') === 'light';
        html.setAttribute('data-theme', isLight ? 'dark' : 'light');
        localStorage.setItem('theme', isLight ? 'dark' : 'light');
        sync();
    });
})();

<?php if(!empty($sma_data)): ?>
/* ── Forecast Chart ──────────────────────────────────────── */
(function() {
    const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
    const gridColor  = isDark ? 'rgba(255,255,255,0.04)' : 'rgba(0,0,0,0.05)';
    const textColor  = isDark ? '#a1a1aa' : '#71717a';

    // Build datasets from PHP
    const smaData    = <?= json_encode($sma_data); ?>;
    const forecasts  = <?= json_encode($forecasts); ?>;

    const histLabels = smaData.map(r => r.label);
    const actual     = smaData.map(r => r.actual);
    const sma        = smaData.map(r => r.sma);

    const fcLabels   = forecasts.map(r => r.label);
    const fcValues   = forecasts.map(r => r.forecast);

    // Pad actual & sma with nulls for forecast zone
    const paddedActual = [...actual, ...fcLabels.map(() => null)];
    const paddedSma    = [...sma,    ...fcLabels.map(() => null)];

    // Forecast line: last actual point + forecast points (bridge)
    const lastActual    = actual[actual.length - 1] ?? null;
    const paddedFc      = [...histLabels.map(() => null)];
    if (paddedFc.length) paddedFc[paddedFc.length - 1] = lastActual;
    paddedFc.push(...fcValues);

    const allLabels = [...histLabels, ...fcLabels];

    const ctx = document.getElementById('forecastChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: allLabels,
            datasets: [
                {
                    label: 'Aktual',
                    data: paddedActual,
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14,165,233,0.06)',
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#0ea5e9',
                    fill: true,
                    tension: 0.35,
                    spanGaps: false,
                },
                {
                    label: 'SMA-<?= $sel_n ?>',
                    data: paddedSma,
                    borderColor: '#10b981',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#10b981',
                    fill: false,
                    tension: 0.35,
                    spanGaps: false,
                },
                {
                    label: 'Proyeksi',
                    data: paddedFc,
                    borderColor: '#cf2127',
                    backgroundColor: 'rgba(207,33,39,0.06)',
                    borderWidth: 2,
                    borderDash: [6, 4],
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#cf2127',
                    pointStyle: 'triangle',
                    fill: true,
                    tension: 0.2,
                    spanGaps: false,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? '#1f1f23' : '#ffffff',
                    borderColor: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.08)',
                    borderWidth: 1,
                    titleColor: isDark ? '#f4f4f5' : '#09090b',
                    bodyColor: isDark ? '#a1a1aa' : '#71717a',
                    padding: 12,
                    callbacks: {
                        label: ctx => {
                            if (ctx.parsed.y === null) return null;
                            return ` ${ctx.dataset.label}: ${ctx.parsed.y} unit`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { color: textColor, font: { size: 11 }, maxRotation: 45 }
                },
                y: {
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { color: textColor, font: { size: 11 },
                             callback: v => v + ' unit' },
                    beginAtZero: true,
                }
            }
        }
    });
})();
<?php endif; ?>
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">
    
    <!-- Google Fonts: Outfit + JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* ── Prediction Page: Executive Cockpit Design ─────────────── */
        :root {
            --pred-bg:        var(--bg-base, #0f1117);
            --pred-surface:   var(--bg-card, #1a1d27);
            --pred-border:    var(--border, rgba(255,255,255,0.08));
            --pred-accent:    #cf2127;
            --pred-accent2:   #0ea5e9;
            --pred-gold:      #f59e0b;
            --pred-text:      var(--text-primary, #f4f4f5);
            --pred-muted:     var(--text-muted, #71717a);
            --pred-radius:    12px;
            --pred-font:      'Outfit', system-ui, sans-serif;
            --pred-mono:      'JetBrains Mono', monospace;
        }
        [data-theme="light"] {
            --pred-bg:        #f4f5f7;
            --pred-surface:   #ffffff;
            --pred-border:    rgba(0,0,0,0.08);
            --pred-text:      #09090b;
            --pred-muted:     #52525b;
        }

        .pred-main { font-family: var(--pred-font); }

        /* Page header strip */
        .pred-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            padding: 1.75rem 0 1.25rem;
            border-bottom: 1px solid var(--pred-border);
            margin-bottom: 1.75rem;
            animation: pred-fadein 0.4s ease both;
        }
        .pred-header-eyebrow {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--pred-accent);
            margin-bottom: 0.3rem;
        }
        .pred-header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            color: var(--pred-text);
            line-height: 1.1;
            margin: 0;
        }
        .pred-header-meta {
            font-size: 0.72rem;
            color: var(--pred-muted);
            font-family: var(--pred-mono);
            margin-top: 0.35rem;
        }

        /* Control bar */
        .pred-control-bar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: var(--pred-surface);
            border: 1px solid var(--pred-border);
            border-radius: var(--pred-radius);
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            animation: pred-fadein 0.45s 0.05s ease both;
        }
        .pred-control-label {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--pred-muted);
            white-space: nowrap;
        }
        .pred-divider-v {
            width: 1px;
            height: 24px;
            background: var(--pred-border);
            flex-shrink: 0;
        }
        .pred-control-bar .wl-dropdown { flex: 1; min-width: 120px; max-width: 200px; }
        .pred-calc-btn {
            margin-left: auto;
            padding: 0.55rem 1.5rem;
            background: var(--pred-accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: var(--pred-font);
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }
        .pred-calc-btn:hover  { background: #b91c1c; }
        .pred-calc-btn:active { transform: scale(0.98); }
        .pred-calc-btn:disabled { opacity: 0.55; cursor: not-allowed; }

        /* Model strip */
        .pred-model-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: var(--pred-border);
            border: 1px solid var(--pred-border);
            border-radius: var(--pred-radius);
            overflow: hidden;
            margin-bottom: 1.5rem;
            animation: pred-fadein 0.45s 0.1s ease both;
        }
        .pred-model-cell {
            background: var(--pred-surface);
            padding: 1.1rem 1.25rem;
            position: relative;
            overflow: hidden;
        }
        .pred-model-cell::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: var(--pred-accent);
            opacity: 0;
            transition: opacity 0.25s;
        }
        .pred-model-cell:hover::before { opacity: 1; }
        .pred-model-name {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--pred-muted);
            margin-bottom: 0.4rem;
        }
        .pred-model-num {
            font-family: var(--pred-mono);
            font-size: 1.75rem;
            font-weight: 500;
            color: var(--pred-text);
            line-height: 1;
            margin-bottom: 0.2rem;
        }
        .pred-model-sub {
            font-size: 0.68rem;
            color: var(--pred-muted);
        }

        /* Chart panel */
        .pred-chart-panel {
            background: var(--pred-surface);
            border: 1px solid var(--pred-border);
            border-radius: var(--pred-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            animation: pred-fadein 0.45s 0.15s ease both;
        }
        .pred-panel-title {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--pred-muted);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .pred-panel-title i { color: var(--pred-accent2); font-size: 0.8rem; }
        .pred-chart-wrap { position: relative; height: 380px; }

        /* Metrics row */
        .pred-metrics-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: var(--pred-border);
            border: 1px solid var(--pred-border);
            border-radius: var(--pred-radius);
            overflow: hidden;
            margin-bottom: 1.5rem;
            animation: pred-fadein 0.45s 0.2s ease both;
        }
        .pred-metric-cell {
            background: var(--pred-surface);
            padding: 1.25rem 1.5rem;
        }
        .pred-metric-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-bottom: 0.3rem;
        }
        .pred-metric-label.c-blue   { color: var(--pred-accent2); }
        .pred-metric-label.c-teal   { color: #14b8a6; }
        .pred-metric-label.c-green  { color: #22c55e; }
        .pred-metric-label.c-amber  { color: var(--pred-gold); }
        .pred-metric-val {
            font-family: var(--pred-mono);
            font-size: 1.6rem;
            font-weight: 500;
            color: var(--pred-text);
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        .pred-metric-desc {
            font-size: 0.65rem;
            color: var(--pred-muted);
            line-height: 1.4;
        }

        /* Two-col lower grid */
        .pred-lower-grid {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 1rem;
            margin-bottom: 1.5rem;
            animation: pred-fadein 0.45s 0.25s ease both;
        }
        @media (max-width: 900px) {
            .pred-lower-grid { grid-template-columns: 1fr; }
            .pred-model-strip { grid-template-columns: 1fr 1fr; }
            .pred-metrics-row { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 560px) {
            .pred-model-strip, .pred-metrics-row { grid-template-columns: 1fr; }
        }

        /* Data table */
        .pred-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
        .pred-table thead tr { border-bottom: 1px solid var(--pred-border); }
        .pred-table th {
            font-size: 0.63rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--pred-muted);
            padding: 0.5rem 0.75rem;
            text-align: left;
            white-space: nowrap;
        }
        .pred-table td {
            padding: 0.65rem 0.75rem;
            color: var(--pred-text);
            font-family: var(--pred-mono);
            font-size: 0.78rem;
            border-bottom: 1px solid var(--pred-border);
        }
        .pred-table tbody tr:last-child td { border-bottom: none; }
        .pred-table tbody tr:hover td { background: rgba(255,255,255,0.025); }
        .pred-badge {
            display: inline-block;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            font-family: var(--pred-mono);
            font-size: 0.72rem;
            font-weight: 500;
        }
        .pred-badge-blue   { background: rgba(14,165,233,0.15); color: #38bdf8; }
        .pred-badge-red    { background: rgba(207,33,39,0.15);  color: #f87171; }
        .pred-badge-green  { background: rgba(34,197,94,0.12);  color: #4ade80; }
        .pred-badge-amber  { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .pred-badge-neutral{ background: rgba(113,113,122,0.15); color: #a1a1aa; }

        /* Forecast panel */
        .pred-forecast-panel {
            background: var(--pred-surface);
            border: 1px solid var(--pred-border);
            border-radius: var(--pred-radius);
            padding: 1.25rem;
            height: 100%;
        }
        .pred-forecast-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 0;
            border-bottom: 1px solid var(--pred-border);
        }
        .pred-forecast-item:last-child { border-bottom: none; }
        .pred-forecast-period {
            font-size: 0.75rem;
            color: var(--pred-muted);
        }
        .pred-forecast-val {
            font-family: var(--pred-mono);
            font-size: 1.4rem;
            font-weight: 500;
            color: var(--pred-gold);
        }
        .pred-forecast-unit {
            font-size: 0.6rem;
            color: var(--pred-muted);
            font-family: var(--pred-mono);
            margin-left: 0.2rem;
        }

        /* Raw data panel */
        .pred-raw-panel {
            background: var(--pred-surface);
            border: 1px solid var(--pred-border);
            border-radius: var(--pred-radius);
            padding: 0 1.25rem;
            animation: pred-fadein 0.45s 0.3s ease both;
        }

        /* Empty state */
        .pred-empty {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--pred-muted);
            font-size: 0.85rem;
            animation: pred-fadein 0.4s ease both;
        }
        .pred-empty i { font-size: 2rem; margin-bottom: 1rem; display: block; }

        @keyframes pred-fadein {
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
            <div class="pred-main" style="padding: 0 1.5rem 3rem;">

                <!-- Page Header -->
                <div class="pred-header">
                    <div>
                        <div class="pred-header-eyebrow">Sales Intelligence</div>
                        <h1>Prediksi Penjualan</h1>
                        <div class="pred-header-meta">Moving Average &mdash; <?= $selected_year ?> &bull; Model: <?= $selected_model == 'all' ? 'Semua Model' : $selected_model ?> &bull; n=<?= $periods ?></div>
                    </div>
                    <a href="<?= site_url('prediction/export_excel?' . http_build_query(['year' => $selected_year, 'model' => $selected_model, 'periods' => $periods, 'forecast_periods' => $forecast_periods])) ?>" style="display:flex;align-items:center;gap:0.5rem;padding:0.55rem 1.1rem;border:1px solid var(--pred-border);border-radius:8px;font-size:0.78rem;font-weight:600;color:var(--pred-muted);text-decoration:none;transition:border-color 0.2s,color 0.2s;" onmouseover="this.style.borderColor='var(--pred-accent)';this.style.color='var(--pred-text)'" onmouseout="this.style.borderColor='var(--pred-border)';this.style.color='var(--pred-muted)'">
                        <i class="fas fa-file-excel" style="font-size:0.75rem;"></i> Export Excel
                    </a>
                </div>

                <!-- Control Bar -->
                <form id="predictionForm" method="get">
                    <div class="pred-control-bar">
                        <span class="pred-control-label">Filter</span>
                        <div class="pred-divider-v"></div>

                        <!-- Year -->
                        <div style="display:flex;flex-direction:column;gap:3px;">
                            <span style="font-size:0.6rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--pred-muted);font-weight:600;">Tahun</span>
                            <div class="wl-dropdown" onclick="toggleDropdown(this, event)">
                                <button type="button" class="wl-dropdown-toggle wl-select wl-flex wl-justify-between wl-items-center" style="width:100%;background:transparent;cursor:pointer;padding:0.35rem 0.65rem;min-height:30px;border:1px solid var(--pred-border);border-radius:6px;">
                                    <span class="selected-text" style="font-size:0.8rem;font-family:var(--pred-mono);"><?= $selected_year ?></span>
                                    <i class="fas fa-chevron-down" style="font-size:9px;color:var(--pred-muted);"></i>
                                </button>
                                <ul class="wl-dropdown-menu" style="min-width:90px;">
                                    <?php foreach ($years as $year): ?>
                                        <li><button type="button" class="wl-dropdown-item <?= $year['year'] == $selected_year ? 'active' : '' ?>" onclick="selectOption(this,'year','<?= $year['year'] ?>','<?= $year['year'] ?>')"><?= $year['year'] ?></button></li>
                                    <?php endforeach; ?>
                                </ul>
                                <input type="hidden" name="year" id="input_year" value="<?= $selected_year ?>">
                            </div>
                        </div>

                        <!-- Model -->
                        <div style="display:flex;flex-direction:column;gap:3px;">
                            <span style="font-size:0.6rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--pred-muted);font-weight:600;">Model</span>
                            <div class="wl-dropdown" onclick="toggleDropdown(this, event)">
                                <button type="button" class="wl-dropdown-toggle wl-select wl-flex wl-justify-between wl-items-center" style="width:100%;background:transparent;cursor:pointer;padding:0.35rem 0.65rem;min-height:30px;border:1px solid var(--pred-border);border-radius:6px;">
                                    <?php $model_text = ($selected_model == 'all') ? 'All Models' : $selected_model; ?>
                                    <span class="selected-text" style="font-size:0.8rem;"><?= $model_text ?></span>
                                    <i class="fas fa-chevron-down" style="font-size:9px;color:var(--pred-muted);"></i>
                                </button>
                                <ul class="wl-dropdown-menu" style="min-width:140px;">
                                    <li><button type="button" class="wl-dropdown-item <?= $selected_model=='all'?'active':'' ?>" onclick="selectOption(this,'model','all','All Models')">All Models</button></li>
                                    <?php foreach ($models as $m): ?>
                                        <li><button type="button" class="wl-dropdown-item <?= $m['model_name']==$selected_model?'active':'' ?>" onclick="selectOption(this,'model','<?= $m['model_name'] ?>','<?= $m['model_name'] ?>') "><?= $m['model_name'] ?></button></li>
                                    <?php endforeach; ?>
                                </ul>
                                <input type="hidden" name="model" id="input_model" value="<?= $selected_model ?>">
                            </div>
                        </div>

                        <div class="pred-divider-v"></div>

                        <!-- MA Periods -->
                        <div style="display:flex;flex-direction:column;gap:3px;">
                            <span style="font-size:0.6rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--pred-muted);font-weight:600;">MA n=</span>
                            <div class="wl-dropdown" onclick="toggleDropdown(this, event)">
                                <button type="button" class="wl-dropdown-toggle wl-select wl-flex wl-justify-between wl-items-center" style="width:100%;background:transparent;cursor:pointer;padding:0.35rem 0.65rem;min-height:30px;border:1px solid var(--pred-border);border-radius:6px;">
                                    <span class="selected-text" style="font-size:0.8rem;font-family:var(--pred-mono);"><?= $periods ?></span>
                                    <i class="fas fa-chevron-down" style="font-size:9px;color:var(--pred-muted);"></i>
                                </button>
                                <ul class="wl-dropdown-menu" style="min-width:70px;">
                                    <?php foreach([2,3,4,5,6] as $p): ?>
                                    <li><button type="button" class="wl-dropdown-item <?= $periods==$p?'active':'' ?>" onclick="selectOption(this,'periods','<?= $p ?>','<?= $p ?>')"><?= $p ?></button></li>
                                    <?php endforeach; ?>
                                </ul>
                                <input type="hidden" name="periods" id="input_periods" value="<?= $periods ?>">
                            </div>
                        </div>

                        <!-- Forecast Periods -->
                        <div style="display:flex;flex-direction:column;gap:3px;">
                            <span style="font-size:0.6rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--pred-muted);font-weight:600;">Ramalan</span>
                            <div class="wl-dropdown" onclick="toggleDropdown(this, event)">
                                <button type="button" class="wl-dropdown-toggle wl-select wl-flex wl-justify-between wl-items-center" style="width:100%;background:transparent;cursor:pointer;padding:0.35rem 0.65rem;min-height:30px;border:1px solid var(--pred-border);border-radius:6px;">
                                    <span class="selected-text" style="font-size:0.8rem;font-family:var(--pred-mono);"><?= $forecast_periods ?></span>
                                    <i class="fas fa-chevron-down" style="font-size:9px;color:var(--pred-muted);"></i>
                                </button>
                                <ul class="wl-dropdown-menu" style="min-width:70px;">
                                    <?php foreach([1,2,3,4,5,6] as $fp): ?>
                                    <li><button type="button" class="wl-dropdown-item <?= $forecast_periods==$fp?'active':'' ?>" onclick="selectOption(this,'forecast_periods','<?= $fp ?>','<?= $fp ?>')"><?= $fp ?></button></li>
                                    <?php endforeach; ?>
                                </ul>
                                <input type="hidden" name="forecast_periods" id="input_forecast_periods" value="<?= $forecast_periods ?>">
                            </div>
                        </div>

                        <button type="submit" class="pred-calc-btn" id="calcBtn">
                            <i class="fas fa-calculator"></i> Hitung
                        </button>
                    </div>
                </form>

                <!-- Model Summary Strip -->
                <?php if (!empty($sales_summary)): ?>
                <div class="pred-model-strip">
                    <?php foreach (array_slice($sales_summary, 0, 4) as $idx => $s): ?>
                    <div class="pred-model-cell" style="animation: pred-fadein 0.4s <?= $idx * 0.07 ?>s ease both;">
                        <div class="pred-model-name"><?= $s['model'] ?></div>
                        <div class="pred-model-num"><?= number_format($s['total_sales']) ?></div>
                        <div class="pred-model-sub">unit terjual &nbsp;&bull;&nbsp; avg Rp <?= number_format($s['avg_price'] / 1000000, 0) ?>jt</div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($sales_data)): ?>

                <!-- Chart -->
                <div class="pred-chart-panel">
                    <div class="pred-panel-title">
                        <i class="fas fa-chart-area"></i>
                        Tren Penjualan Aktual vs Moving Average vs Ramalan
                        <span style="margin-left:auto;font-size:0.62rem;color:var(--pred-muted);">n=<?= $periods ?> periode &mdash; <?= $selected_year ?></span>
                    </div>
                    <div class="pred-chart-wrap">
                        <canvas id="predictionChart"></canvas>
                    </div>
                </div>

                <?php endif; ?>

                <!-- Accuracy Metrics -->
                <?php if ($accuracy): ?>
                <div class="pred-metrics-row">
                    <div class="pred-metric-cell">
                        <div class="pred-metric-label c-blue">MAE</div>
                        <div class="pred-metric-val"><?= $accuracy['mae'] ?></div>
                        <div class="pred-metric-desc">Mean Absolute Error<br>rata-rata selisih absolut</div>
                    </div>
                    <div class="pred-metric-cell">
                        <div class="pred-metric-label c-teal">MSE</div>
                        <div class="pred-metric-val"><?= $accuracy['mse'] ?></div>
                        <div class="pred-metric-desc">Mean Squared Error<br>penalti error besar</div>
                    </div>
                    <div class="pred-metric-cell">
                        <div class="pred-metric-label c-green">RMSE</div>
                        <div class="pred-metric-val"><?= $accuracy['rmse'] ?></div>
                        <div class="pred-metric-desc">Root Mean Squared Error<br>skala sama dng data asli</div>
                    </div>
                    <div class="pred-metric-cell">
                        <div class="pred-metric-label c-amber">WAPE</div>
                        <div class="pred-metric-val"><?= $accuracy['wape'] ?>%</div>
                        <div class="pred-metric-desc">Weighted Absolute Pct Error<br>total abs / total aktual</div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Lower Grid: MA Table + Forecast -->
                <?php if (!empty($moving_averages) || !empty($forecasts)): ?>
                <div class="pred-lower-grid">
                    <!-- Historical MA Table -->
                    <?php if (!empty($moving_averages)): ?>
                    <div style="background:var(--pred-surface);border:1px solid var(--pred-border);border-radius:var(--pred-radius);overflow:hidden;">
                        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--pred-border);">
                            <div class="pred-panel-title" style="margin:0;">
                                <i class="fas fa-table"></i> Historis Moving Average
                            </div>
                        </div>
                        <div style="overflow-x:auto;">
                            <table class="pred-table">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Aktual</th>
                                        <th>MA-<?= $periods ?></th>
                                        <th>Error</th>
                                        <th>|Error|</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($moving_averages as $item): ?>
                                    <tr>
                                        <td><?= $item['period'] ?></td>
                                        <td><span class="pred-badge pred-badge-blue"><?= $item['actual_sales'] ?></span></td>
                                        <td><span class="pred-badge pred-badge-neutral"><?= $item['moving_average'] ?></span></td>
                                        <td>
                                            <span class="pred-badge <?= $item['forecast_error'] >= 0 ? 'pred-badge-green' : 'pred-badge-red' ?>">
                                                <?= $item['forecast_error'] > 0 ? '+' : '' ?><?= $item['forecast_error'] ?>
                                            </span>
                                        </td>
                                        <td style="color:var(--pred-muted);"><?= abs($item['forecast_error']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Forecast Panel -->
                    <?php if (!empty($forecasts)): ?>
                    <div class="pred-forecast-panel">
                        <div class="pred-panel-title" style="margin-bottom:0.75rem;">
                            <i class="fas fa-arrow-trend-up" style="color:var(--pred-gold);"></i> Ramalan Ke Depan
                        </div>
                        <div style="font-size:0.65rem;color:var(--pred-muted);margin-bottom:1.1rem;font-family:var(--pred-mono);">Metode: Moving Average (n=<?= $periods ?>)</div>
                        <?php foreach ($forecasts as $forecast): ?>
                        <div class="pred-forecast-item">
                            <div>
                                <div class="pred-forecast-period"><?= $forecast['period'] ?></div>
                            </div>
                            <div style="text-align:right;">
                                <span class="pred-forecast-val"><?= $forecast['forecast_sales'] ?></span>
                                <span class="pred-forecast-unit">unit</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (!empty($accuracy)): ?>
                        <div style="margin-top:1rem;padding-top:0.75rem;border-top:1px solid var(--pred-border);">
                            <div style="font-size:0.62rem;color:var(--pred-muted);margin-bottom:0.3rem;letter-spacing:0.08em;text-transform:uppercase;">Akurasi Model</div>
                            <div style="font-family:var(--pred-mono);font-size:1.1rem;color:var(--pred-gold);"><?= $accuracy['wape'] ?>% WAPE</div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Raw Data Table -->
                <?php if (!empty($sales_data)): ?>
                <div class="pred-raw-panel">
                    <div style="padding:1rem 0;border-bottom:1px solid var(--pred-border);margin-bottom:0;">
                        <div class="pred-panel-title" style="margin:0;">
                            <i class="fas fa-database"></i>
                            Data Penjualan Mentah &mdash; <?= $selected_year ?> &bull; <?= $selected_model == 'all' ? 'Semua Model' : $selected_model ?>
                        </div>
                    </div>
                    <table class="pred-table">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Total Unit</th>
                                <th>Total Revenue</th>
                                <th>Avg / Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sales_data as $d): ?>
                            <tr>
                                <td><?= $d['bulan'] ?></td>
                                <td><?= $d['tahun'] ?></td>
                                <td><span class="pred-badge pred-badge-blue"><?= $d['total_sales'] ?></span></td>
                                <td>Rp <?= number_format($d['total_revenue'], 0, ',', '.') ?></td>
                                <td style="color:var(--pred-muted);">Rp <?= number_format($d['total_revenue'] / $d['total_sales'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="border-top:1px solid var(--pred-border);">
                                <td colspan="2" style="font-weight:600;color:var(--pred-muted);">TOTAL</td>
                                <td><span class="pred-badge pred-badge-green"><?= array_sum(array_column($sales_data, 'total_sales')) ?></span></td>
                                <td style="font-weight:600;">Rp <?= number_format(array_sum(array_column($sales_data, 'total_revenue')), 0, ',', '.') ?></td>
                                <td style="color:var(--pred-muted);">Rp <?= number_format(array_sum(array_column($sales_data, 'total_revenue')) / max(1, array_sum(array_column($sales_data, 'total_sales'))), 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else: ?>
                <div class="pred-empty">
                    <i class="fas fa-inbox"></i>
                    Tidak ada data penjualan untuk parameter yang dipilih.<br>
                    <span style="font-size:0.78rem;">Coba ubah tahun atau model kendaraan.</span>
                </div>
                <?php endif; ?>

            </div><!-- /pred-main -->
        </main>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/js/theme.js') ?>"></script>
    
    <!-- Custom Dropdown Logic -->
    <script>
        function toggleDropdown(el, event) {
            event.stopPropagation();
            document.querySelectorAll('.wl-dropdown').forEach(dropdown => {
                if (dropdown !== el) dropdown.classList.remove('active');
            });
            el.classList.toggle('active');
        }

        function selectOption(btn, inputName, value, text) {
            const dropdown = btn.closest('.wl-dropdown');
            dropdown.querySelector('.selected-text').innerText = text;
            dropdown.querySelectorAll('.wl-dropdown-item').forEach(i => i.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('input_' + inputName).value = value;
            dropdown.classList.remove('active');
        }

        document.addEventListener('click', () => {
            document.querySelectorAll('.wl-dropdown').forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        });
    </script>

    <?php if (!empty($sales_data)): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const labels = <?= json_encode(array_column($sales_data, 'bulan')) ?>;
        const actualSales = <?= json_encode(array_column($sales_data, 'total_sales')) ?>;
        const totalPeriods = labels.length;
        
        const maData = new Array(totalPeriods).fill(null);
        <?php foreach ($moving_averages as $ma): ?>
            var idx = labels.indexOf('<?= $ma['period'] ?>');
            if(idx !== -1) {
                maData[idx] = <?= $ma['moving_average'] ?>;
            }
        <?php endforeach; ?>

        const forecastData = new Array(totalPeriods).fill(null);
        <?php foreach ($forecasts as $f): ?>
            labels.push('<?= $f['period'] ?>');
            forecastData.push(<?= $f['forecast_sales'] ?>);
        <?php endforeach; ?>
        
        while(actualSales.length < labels.length) actualSales.push(null);
        while(maData.length < labels.length) maData.push(null);

        const ctx = document.getElementById('predictionChart').getContext('2d');
        
        if(actualSales.length > 0 && forecastData.length > totalPeriods) {
            forecastData[totalPeriods - 1] = maData[totalPeriods - 1] || actualSales[totalPeriods - 1]; 
        }

        const style = getComputedStyle(document.documentElement);
        const textMuted = style.getPropertyValue('--pred-muted').trim() || '#71717a';
        const gridColor = style.getPropertyValue('--pred-border').trim() || 'rgba(255,255,255,0.08)';

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Penjualan Aktual',
                        data: actualSales,
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#0ea5e9',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Moving Average (n=<?= $periods ?>)',
                        data: maData,
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#10b981',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'Ramalan (Forecast)',
                        data: forecastData,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: textMuted,
                            font: {
                                family: "'Outfit', sans-serif",
                                size: 11
                            },
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 29, 39, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#e4e4e7',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        padding: 10,
                        titleFont: {
                            family: "'Outfit', sans-serif",
                            size: 13
                        },
                        bodyFont: {
                            family: "'JetBrains Mono', monospace",
                            size: 12
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        },
                        ticks: {
                            color: textMuted,
                            font: {
                                family: "'Outfit', sans-serif",
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        },
                        ticks: {
                            color: textMuted,
                            font: {
                                family: "'JetBrains Mono', monospace",
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    });
    </script>
    <?php endif; ?>
</body>
</html>
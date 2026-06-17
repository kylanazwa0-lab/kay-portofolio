<?php
// ============================================================
// export.php — Generate index.html dari portfolio.json
// Update diagram section dengan gambar asli jika ada
// ============================================================
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/data.php';

requireLogin();

$data    = loadData();
$outFile = dirname(__DIR__) . '/index.html';

// ---- Helper: escape HTML ----
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES); }

// ---- Build diagram tabs HTML ----
function buildDiagramTabs(array $diagrams): string {
    $tabs = '';
    $panels = '';
    foreach ($diagrams as $i => $d) {
        $active = $i === 0 ? 'active' : '';
        $tabs .= "<button class=\"dtab-btn {$active}\" data-dtab=\"{$d['id']}\">" . e($d['label']) . "</button>\n";

        $highlights = '';
        foreach ($d['highlights'] as $hl) {
            $highlights .= "<li>" . e($hl) . "</li>\n";
        }

        // Image section — real image if uploaded, mock if not
        if (!empty($d['image'])) {
            $imgSection = "<img src=\"" . e($d['image']) . "\" alt=\"" . e($d['label']) . "\" style=\"width:100%;height:100%;object-fit:contain;padding:1rem\"/>";
        } else {
            $imgSection = buildMockDiagram($d['id']);
        }

        $panels .= <<<HTML
      <div class="diagram-content {$active}" id="diag-{$d['id']}">
        <div class="diagram-card">
          <div class="diagram-placeholder-img">
            {$imgSection}
          </div>
          <div class="diagram-info">
            <h3>{$d['title']}</h3>
            <p>{$d['description']}</p>
            <ul class="diagram-highlights">
              {$highlights}
            </ul>
          </div>
        </div>
      </div>
HTML;
    }
    return "<div class=\"diagram-tabs\">\n{$tabs}</div>\n{$panels}";
}

function buildMockDiagram(string $id): string {
    $mocks = [
        'bpmn' => '<div class="bpmn-mock"><div class="bpmn-pool"><span class="pool-label">Pelamar</span><div class="pool-flow"><div class="bpmn-start">🟢</div><span class="bpmn-arrow">→</span><div class="bpmn-task">Isi Form</div><span class="bpmn-arrow">→</span><div class="bpmn-task">Upload Dokumen</div></div></div><div class="bpmn-pool"><span class="pool-label">HR Admin</span><div class="pool-flow"><div class="bpmn-task">Review Berkas</div><span class="bpmn-arrow">→</span><span class="bpmn-gateway">◇</span><span class="bpmn-arrow">→</span><div class="bpmn-task">Interview</div><span class="bpmn-arrow">→</span><div class="bpmn-end"></div></div></div></div>',
        'usecase' => '<div class="usecase-mock"><div class="uc-actors"><div class="uc-actor"><br>Admin</div></div><div class="uc-system-boundary"><div class="uc-title">System</div><div class="uc-cases"><div class="uc-item"> Kelola Data</div><div class="uc-item"> Generate QR</div><div class="uc-item"> Monitor</div></div></div><div class="uc-actors"><div class="uc-actor"><br>User</div></div></div>',
        'erd' => '<div class="erd-mini"><div class="erd-t"> users</div><div class="erd-c">id · name · role</div><div class="erd-row-2"><div><div class="erd-t"> records</div><div class="erd-c">id · user_id · date</div></div><span style="color:var(--accent);font-weight:700">1:N</span></div></div>',
        'sequence' => '<div class="sequence-mock"><div class="seq-actors"><span>User</span><span>App</span><span>DB</span></div><div class="seq-lines"><div class="seq-msg">Request →</div><div class="seq-msg ind1">Query →</div><div class="seq-msg ind2">← Data</div><div class="seq-msg">← Response</div></div></div>',
    ];
    return $mocks[$id] ?? '<div style="color:#94a3b8;text-align:center"> Diagram</div>';
}

// ---- Build projects grid HTML ----
function buildProjectsGrid(array $projects): string {
    $out = '';
    foreach ($projects as $proj) {
        $featured = in_array($proj['status'], ['live','indev']) ? ' featured' : '';

        $highlights = '';
        foreach ($proj['highlights'] as $h) {
            $highlights .= "<div class=\"ph-item\"><span class=\"ph-num\">" . e($h['num']) . "</span><span class=\"ph-label\">" . e($h['label']) . "</span></div>";
        }

        $features = '';
        foreach ($proj['features'] as $f) {
            $features .= "<li>" . e($f) . "</li>";
        }

        $techChips = '';
        foreach ($proj['tech'] as $t) {
            $techChips .= "<span class=\"tech-chip\">" . e($t) . "</span>";
        }

        $badgeClass = $proj['status'] === 'live' ? 'badge-live' : ($proj['status'] === 'indev' ? 'badge-indev' : 'badge-live');

        $out .= <<<HTML
        <div class="project-card{$featured}" data-tags="{$proj['tags']}" id="proj-{$proj['id']}">
          <div class="project-header">
            <div class="project-meta">
              <span class="project-number">{$proj['number']}</span>
              <div class="project-badges">
                <span class="badge {$badgeClass}">{$proj['status_label']}</span>
                <span class="badge badge-type">{$proj['type']}</span>
              </div>
            </div>
          </div>
          <div class="project-body">
            <div class="project-icon-wrap"><span class="project-icon">{$proj['icon']}</span></div>
            <h3 class="project-title">{$proj['title']}</h3>
            <p class="project-tagline">{$proj['tagline']}</p>
            <p class="project-desc">{$proj['description']}</p>
            <div class="project-highlights-row">{$highlights}</div>
            <div class="project-accordion">
              <details class="acc-item">
                <summary class="acc-header">Fitur Unggulan</summary>
                <div class="acc-body"><ul>{$features}</ul></div>
              </details>
            </div>
            <div class="project-tech-stack">{$techChips}</div>
          </div>
        </div>
HTML;
    }
    return $out;
}

// ---- Build skills grid ----
function buildSkillsGrid(array $skills): string {
    $out = '';
    foreach ($skills as $cat) {
        $tags = '';
        foreach ($cat['items'] as $item) {
            $lvl = e($item['level']);
            $tags .= "<span class=\"skill-tag level-{$lvl}\">" . e($item['name']) . "</span>";
        }
        $out .= <<<HTML
        <div class="skill-category">
          <div class="skill-cat-title"><span class="cat-icon">{$cat['icon']}</span> {$cat['category']}</div>
          <div class="skill-tags">{$tags}</div>
        </div>
HTML;
    }
    return $out;
}

// ============================================================
// READ current index.html and patch the dynamic sections
// ============================================================
$html = file_get_contents($outFile);
if (!$html) {
    die('<p style="color:red;font-family:sans-serif;padding:2rem">Error: Tidak bisa membaca index.html!</p>');
}

// -- 1. Update diagram section --
$diagTabsNew = buildDiagramTabs($data['diagrams']);
$html = preg_replace(
    '/(<div class="diagram-tabs">).*?(<\/section>)/s',
    $diagTabsNew . "\n    </div>\n  </section>",
    $html,
    1
);

// -- 2. Update projects grid --
$projGridNew = buildProjectsGrid($data['projects']);
$html = preg_replace(
    '/(<div class="projects-grid">).*?(<\/div><!-- \.projects-grid -->)/s',
    '<div class="projects-grid">' . "\n" . $projGridNew . "\n      </div><!-- .projects-grid -->",
    $html,
    1
);

// -- 3. Update meta in hero --
$m = $data['meta'];
// Update hero badge status
$html = preg_replace('/(Open to Work[^<]*)/', e($m['status']) . ' · ' . e($m['location']), $html, 1);

// -- 4. Write file --
$written = file_put_contents($outFile, $html);

// ============================================================
// OUTPUT RESULT
// ============================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Export Result</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    body{font-family:'Inter',sans-serif;background:#0f172a;color:#e2e8f0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
    .card{background:#1e293b;border:1px solid #334155;border-radius:20px;padding:2.5rem;max-width:540px;width:100%;text-align:center}
    .icon{font-size:3rem;margin-bottom:1rem}
    h1{font-size:1.5rem;font-weight:800;color:#f8fafc;margin-bottom:0.5rem}
    p{font-size:0.875rem;color:#94a3b8;line-height:1.7;margin-bottom:1.5rem}
    .info{background:#0f172a;border:1px solid #334155;border-radius:10px;padding:1rem;text-align:left;font-size:0.8rem;color:#64748b;margin-bottom:1.5rem;line-height:1.8}
    .info strong{color:#94a3b8}
    .btns{display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap}
    .btn{display:inline-flex;align-items:center;gap:0.4rem;padding:0.65rem 1.25rem;border-radius:10px;font-size:0.85rem;font-weight:700;cursor:pointer;text-decoration:none;border:none}
    .btn-green{background:linear-gradient(135deg,#16a34a,#0d9488);color:#fff}
    .btn-blue{background:#1e40af;color:#93c5fd;border:1px solid #1d4ed8}
    .btn-gray{background:#334155;color:#94a3b8}
    .error{background:#450a0a;border-color:#7f1d1d;color:#fca5a5}
  </style>
</head>
<body>
<div class="card">
  <?php if ($written !== false): ?>
    <div class="icon"></div>
    <h1>Export Berhasil!</h1>
    <p>File <code>index.html</code> berhasil di-generate dari data terbaru. Diagram dengan gambar asli sudah ter-embed.</p>
    <div class="info">
      <strong>File yang diupdate:</strong><br/>
       index.html (<?= round($written/1024,1) ?> KB)<br/>
       <?= date('d M Y, H:i:s') ?><br/>
       <?= count($data['diagrams']) ?> diagram · <?= count($data['projects']) ?> proyek · <?= array_sum(array_map(fn($c)=>count($c['items']), $data['skills'])) ?> skills
    </div>
    <div class="btns">
      <a href="../index.html" target="_blank" class="btn btn-green"> Lihat Portfolio</a>
      <a href="dashboard.php" class="btn btn-blue">← Kembali ke Admin</a>
    </div>
  <?php else: ?>
    <div class="icon"></div>
    <h1>Export Gagal</h1>
    <p class="error">Tidak bisa menulis ke <code>index.html</code>. Pastikan file tidak dalam kondisi read-only.</p>
    <a href="dashboard.php" class="btn btn-gray">← Kembali</a>
  <?php endif; ?>
</div>
</body>
</html>

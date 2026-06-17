<?php
// ============================================================
// dashboard.php — Main Admin Dashboard
// ============================================================
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/data.php';

requireLogin();
$data = loadData();
$activeTab = $_GET['tab'] ?? 'home';
$diagUploaded = count(array_filter($data['diagrams'], fn($d)=>!empty($d['image'])));
$totalDiag    = count($data['diagrams']);
$totalProj    = count($data['projects']);
$totalSkills  = array_sum(array_map(fn($c)=>count($c['items']), $data['skills']));
$liveProjects = count(array_filter($data['projects'], fn($p)=>$p['status']==='live'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin — Kyla Portfolio</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet"/>
  <style>
    /* ===== RESET & BASE ===== */
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{font-size:16px;scroll-behavior:smooth}
    body{font-family:'Inter',sans-serif;background:#0f172a;color:#e2e8f0;min-height:100vh;display:flex}
    a{color:inherit;text-decoration:none}
    img{max-width:100%;display:block}
    button{font-family:inherit;cursor:pointer}
    input,textarea,select{font-family:inherit}

    /* ===== SIDEBAR ===== */
    .sidebar{width:260px;flex-shrink:0;background:#1e293b;border-right:1px solid #334155;display:flex;flex-direction:column;position:fixed;top:0;left:0;height:100vh;z-index:100;overflow-y:auto}
    .sidebar-logo{padding:1.5rem 1.25rem 1rem;border-bottom:1px solid #334155}
    .sidebar-logo h2{font-size:1.2rem;font-weight:800;color:#f8fafc;letter-spacing:-0.02em}
    .sidebar-logo h2 span{color:#2563eb}
    .sidebar-logo p{font-size:0.72rem;color:#475569;margin-top:2px}
    .sidebar-user{padding:0.75rem 1.25rem;background:#0f172a;border-bottom:1px solid #334155;display:flex;align-items:center;gap:0.6rem}
    .user-avatar{width:32px;height:32px;background:linear-gradient(135deg,#2563eb,#0d9488);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;color:#fff;flex-shrink:0}
    .user-info strong{display:block;font-size:0.82rem;color:#e2e8f0}
    .user-info span{font-size:0.72rem;color:#475569}

    .sidebar-nav{padding:1rem 0.75rem;flex:1}
    .nav-section-label{font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#475569;padding:0 0.5rem;margin-bottom:0.4rem;margin-top:1rem}
    .nav-section-label:first-child{margin-top:0}
    .nav-item{display:flex;align-items:center;gap:0.6rem;padding:0.6rem 0.75rem;border-radius:8px;font-size:0.875rem;font-weight:500;color:#94a3b8;transition:0.15s;cursor:pointer;border:none;background:none;width:100%;text-align:left}
    .nav-item:hover{background:#334155;color:#e2e8f0}
    .nav-item.active{background:linear-gradient(135deg,rgba(37,99,235,0.2),rgba(13,148,136,0.1));color:#60a5fa;border:1px solid rgba(37,99,235,0.3)}
    .nav-icon{font-size:1rem;width:20px;text-align:center;flex-shrink:0}
    .nav-badge{margin-left:auto;background:#2563eb;color:#fff;font-size:0.65rem;font-weight:700;padding:0.15rem 0.4rem;border-radius:999px}

    .sidebar-footer{padding:1rem 1.25rem;border-top:1px solid #334155}
    .btn-logout{display:flex;align-items:center;gap:0.5rem;width:100%;padding:0.6rem 0.75rem;background:#450a0a;border:1px solid #7f1d1d;color:#fca5a5;border-radius:8px;font-size:0.82rem;font-weight:600;cursor:pointer;transition:0.15s}
    .btn-logout:hover{background:#7f1d1d;color:#fff}
    .btn-view-portfolio{display:flex;align-items:center;gap:0.5rem;width:100%;padding:0.6rem 0.75rem;background:#0f172a;border:1px solid #334155;color:#94a3b8;border-radius:8px;font-size:0.82rem;font-weight:600;cursor:pointer;transition:0.15s;margin-bottom:0.5rem}
    .btn-view-portfolio:hover{color:#e2e8f0;border-color:#475569}

    /* ===== MAIN CONTENT ===== */
    .main{margin-left:260px;flex:1;min-height:100vh;display:flex;flex-direction:column}
    .topbar{background:#1e293b;border-bottom:1px solid #334155;padding:1rem 2rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
    .topbar-title{font-size:1.1rem;font-weight:700;color:#f8fafc}
    .topbar-subtitle{font-size:0.78rem;color:#64748b;margin-top:1px}
    .topbar-actions{display:flex;align-items:center;gap:0.75rem}
    .last-saved{font-size:0.75rem;color:#475569}

    .btn-export{display:inline-flex;align-items:center;gap:0.4rem;padding:0.55rem 1.1rem;background:linear-gradient(135deg,#2563eb,#0d9488);color:#fff;border:none;border-radius:8px;font-size:0.82rem;font-weight:700;cursor:pointer;transition:0.2s}
    .btn-export:hover{opacity:0.85;transform:translateY(-1px)}

    .content{padding:2rem;flex:1}

    /* ===== TOAST ===== */
    .toast-container{position:fixed;top:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:0.5rem}
    .toast{padding:0.75rem 1.25rem;border-radius:10px;font-size:0.85rem;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,0.4);animation:slideIn 0.3s ease;display:flex;align-items:center;gap:0.5rem;min-width:260px}
    .toast.success{background:#14532d;border:1px solid #16a34a;color:#86efac}
    .toast.error{background:#450a0a;border:1px solid #dc2626;color:#fca5a5}
    @keyframes slideIn{from{opacity:0;transform:translateX(100%)}to{opacity:1;transform:translateX(0)}}

    /* ===== SECTION PANELS ===== */
    .tab-panel{display:none}
    .tab-panel.active{display:block}

    .panel-title{font-size:1.3rem;font-weight:800;color:#f8fafc;margin-bottom:0.3rem}
    .panel-sub{font-size:0.85rem;color:#64748b;margin-bottom:2rem}

    /* ===== CARDS ===== */
    .card{background:#1e293b;border:1px solid #334155;border-radius:16px;padding:1.5rem;margin-bottom:1.5rem}
    .card-title{font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#64748b;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem}
    .card-title-icon{font-size:1rem}

    /* ===== DIAGRAM GRID ===== */
    .diagram-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem}
    .diag-card{background:#0f172a;border:1px solid #334155;border-radius:12px;overflow:hidden;transition:0.2s}
    .diag-card:hover{border-color:#475569}
    .diag-preview{height:180px;background:#1e293b;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;cursor:pointer}
    .diag-preview img{width:100%;height:100%;object-fit:contain;padding:0.5rem}
    .diag-preview-empty{display:flex;flex-direction:column;align-items:center;gap:0.5rem;color:#475569}
    .diag-preview-empty .empty-icon{font-size:2.5rem}
    .diag-preview-empty span{font-size:0.75rem;font-weight:500}
    .diag-preview-overlay{position:absolute;inset:0;background:rgba(0,0,0,0.7);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.4rem;opacity:0;transition:0.2s}
    .diag-preview:hover .diag-preview-overlay{opacity:1}
    .overlay-btn{background:#2563eb;color:#fff;border:none;border-radius:6px;padding:0.4rem 0.9rem;font-size:0.78rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:0.3rem;transition:0.15s}
    .overlay-btn:hover{background:#1d4ed8}
    .overlay-btn.danger{background:#dc2626}
    .overlay-btn.danger:hover{background:#b91c1c}

    .diag-body{padding:1rem}
    .diag-label{font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#2563eb;margin-bottom:0.3rem}
    .diag-title{font-size:0.9rem;font-weight:700;color:#e2e8f0;margin-bottom:0.4rem}
    .diag-status{display:inline-flex;align-items:center;gap:0.3rem;font-size:0.7rem;font-weight:600;padding:0.2rem 0.5rem;border-radius:999px}
    .diag-status.has-img{background:rgba(22,163,74,0.15);color:#4ade80;border:1px solid rgba(22,163,74,0.3)}
    .diag-status.no-img{background:rgba(100,116,139,0.15);color:#94a3b8;border:1px solid #334155}
    .diag-actions{display:flex;gap:0.5rem;margin-top:0.75rem;flex-wrap:wrap}

    .btn-sm{padding:0.35rem 0.75rem;border-radius:6px;font-size:0.75rem;font-weight:600;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:0.3rem;transition:0.15s}
    .btn-primary{background:#2563eb;color:#fff}
    .btn-primary:hover{background:#1d4ed8}
    .btn-secondary{background:#334155;color:#94a3b8;border:1px solid #475569}
    .btn-secondary:hover{background:#475569;color:#e2e8f0}
    .btn-danger{background:rgba(220,38,38,0.15);color:#f87171;border:1px solid rgba(220,38,38,0.3)}
    .btn-danger:hover{background:#dc2626;color:#fff}
    .btn-success{background:rgba(22,163,74,0.15);color:#4ade80;border:1px solid rgba(22,163,74,0.3)}
    .btn-success:hover{background:#16a34a;color:#fff}

    /* ===== FORM ELEMENTS ===== */
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
    .form-group{display:flex;flex-direction:column;gap:0.35rem}
    .form-group.full{grid-column:1/-1}
    .form-group label{font-size:0.75rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em}
    .form-group input,.form-group textarea,.form-group select{padding:0.6rem 0.85rem;background:#0f172a;border:1.5px solid #334155;border-radius:8px;color:#e2e8f0;font-size:0.875rem;outline:none;transition:0.2s;width:100%}
    .form-group input:focus,.form-group textarea:focus,.form-group select:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.15)}
    .form-group textarea{resize:vertical;min-height:90px;line-height:1.6}
    .form-hint{font-size:0.72rem;color:#475569;margin-top:2px}
    .form-actions{margin-top:1.25rem;display:flex;gap:0.75rem;align-items:center}

    /* ===== PROJECT LIST ===== */
    .project-list{display:flex;flex-direction:column;gap:1rem}
    .proj-item{background:#0f172a;border:1px solid #334155;border-radius:12px;overflow:hidden;transition:0.2s}
    .proj-item:hover{border-color:#475569}
    .proj-header{padding:1rem 1.25rem;display:flex;align-items:center;gap:1rem;cursor:pointer;user-select:none}
    .proj-num{font-size:0.7rem;font-weight:700;color:#475569;font-family:'Fira Code',monospace;min-width:28px}
    .proj-icon{font-size:1.3rem}
    .proj-info{flex:1}
    .proj-name{font-size:0.95rem;font-weight:700;color:#e2e8f0}
    .proj-tagline{font-size:0.75rem;color:#64748b;margin-top:1px}
    .proj-badges{display:flex;gap:0.4rem;align-items:center;flex-wrap:wrap}
    .badge{font-size:0.65rem;font-weight:600;padding:0.2rem 0.5rem;border-radius:999px}
    .badge-live{background:rgba(22,163,74,0.15);color:#4ade80;border:1px solid rgba(22,163,74,0.3)}
    .badge-indev{background:rgba(37,99,235,0.15);color:#60a5fa;border:1px solid rgba(37,99,235,0.3)}
    .badge-done{background:#1e293b;color:#64748b;border:1px solid #334155}
    .proj-toggle{color:#475569;font-size:0.75rem;transition:transform 0.2s;flex-shrink:0}
    .proj-toggle.open{transform:rotate(180deg)}
    .proj-body{display:none;padding:0 1.25rem 1.25rem;border-top:1px solid #1e293b}
    .proj-body.open{display:block}

    /* ===== META GRID ===== */
    .meta-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}

    /* ===== SKILL EDITOR ===== */
    .skill-cat-list{display:flex;flex-direction:column;gap:0.75rem}
    .skill-cat-item{background:#0f172a;border:1px solid #334155;border-radius:10px;padding:1rem}
    .skill-cat-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem}
    .skill-cat-name{font-size:0.875rem;font-weight:700;color:#e2e8f0;display:flex;align-items:center;gap:0.5rem}
    .skill-tags-editor{display:flex;flex-wrap:wrap;gap:0.4rem;margin-bottom:0.5rem}
    .skill-chip{display:inline-flex;align-items:center;gap:0.3rem;padding:0.25rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:600;border:1px solid #334155;background:#1e293b;color:#94a3b8}
    .skill-chip .remove-skill{background:none;border:none;color:#ef4444;cursor:pointer;font-size:0.8rem;padding:0 2px;line-height:1}
    .skill-chip.expert{border-color:#2563eb;background:rgba(37,99,235,0.1);color:#60a5fa}
    .skill-chip.ai-tag{border-color:#7c3aed;background:rgba(124,58,237,0.1);color:#a78bfa}
    .add-skill-row{display:flex;gap:0.5rem;margin-top:0.5rem}
    .add-skill-row input{flex:1;padding:0.35rem 0.65rem;background:#0f172a;border:1px solid #334155;border-radius:6px;color:#e2e8f0;font-size:0.78rem;outline:none}
    .add-skill-row input:focus{border-color:#2563eb}
    .add-skill-row select{padding:0.35rem 0.5rem;background:#0f172a;border:1px solid #334155;border-radius:6px;color:#e2e8f0;font-size:0.78rem;outline:none}

    /* ===== FILE DROP ZONE ===== */
    .drop-zone{border:2px dashed #334155;border-radius:12px;padding:2rem;text-align:center;transition:0.2s;cursor:pointer;position:relative}
    .drop-zone.dragover{border-color:#2563eb;background:rgba(37,99,235,0.05)}
    .drop-zone input[type="file"]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
    .drop-zone-icon{font-size:2rem;margin-bottom:0.5rem}
    .drop-zone p{font-size:0.85rem;color:#64748b}
    .drop-zone p strong{color:#e2e8f0}
    .drop-zone span{font-size:0.75rem;color:#475569}
    .file-preview-wrap{margin-top:0.75rem;display:none}
    .file-preview-wrap img{max-height:120px;border-radius:8px;margin:0 auto;border:1px solid #334155}
    .file-name{font-size:0.75rem;color:#64748b;margin-top:0.4rem;text-align:center}

    /* ===== STATS BAR ===== */
    .stats-bar{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem}
    .stat-card{background:#1e293b;border:1px solid #334155;border-radius:12px;padding:1.25rem;display:flex;align-items:center;gap:1rem}
    .stat-icon{font-size:1.75rem}
    .stat-val{font-size:1.6rem;font-weight:800;color:#f8fafc;line-height:1}
    .stat-label{font-size:0.72rem;color:#64748b;margin-top:2px}

    /* ===== HOME OVERVIEW ===== */
    .home-greeting{margin-bottom:2rem}
    .home-greeting h1{font-size:1.6rem;font-weight:800;color:#f8fafc;letter-spacing:-0.02em}
    .home-greeting p{font-size:0.875rem;color:#64748b;margin-top:0.3rem}
    .home-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2.5rem}
    .hstat{background:#1e293b;border:1px solid #334155;border-radius:14px;padding:1.25rem;display:flex;flex-direction:column;gap:0.25rem;position:relative;overflow:hidden}
    .hstat::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(37,99,235,0.05),transparent);pointer-events:none}
    .hstat-val{font-size:2rem;font-weight:800;color:#f8fafc;line-height:1;letter-spacing:-0.03em}
    .hstat-label{font-size:0.72rem;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.06em}
    .hstat-icon{font-size:1.4rem;margin-bottom:0.25rem}
    .hstat.accent .hstat-val{color:#60a5fa}
    .hstat.green  .hstat-val{color:#4ade80}
    .hstat.teal   .hstat-val{color:#2dd4bf}
    .hstat.purple .hstat-val{color:#a78bfa}

    .home-sections-title{font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#475569;margin-bottom:1rem}
    .home-sections{display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;margin-bottom:1.5rem}
    .hsec-card{background:#1e293b;border:1px solid #334155;border-radius:16px;padding:1.5rem;cursor:pointer;transition:0.2s;display:flex;flex-direction:column;gap:0;position:relative;overflow:hidden}
    .hsec-card:hover{border-color:#475569;transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,0,0,0.3)}
    .hsec-card:hover .hsec-arrow{opacity:1;transform:translateX(0)}
    .hsec-card-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1rem}
    .hsec-icon-wrap{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0}
    .hsec-icon-wrap.blue{background:rgba(37,99,235,0.15);}
    .hsec-icon-wrap.green{background:rgba(22,163,74,0.15);}
    .hsec-icon-wrap.amber{background:rgba(245,158,11,0.15);}
    .hsec-icon-wrap.teal{background:rgba(13,148,136,0.15);}
    .hsec-icon-wrap.purple{background:rgba(124,58,237,0.15);}
    .hsec-arrow{font-size:1rem;color:#475569;opacity:0;transform:translateX(-6px);transition:0.2s;margin-top:4px}
    .hsec-title{font-size:1rem;font-weight:700;color:#f8fafc;margin-bottom:0.3rem}
    .hsec-desc{font-size:0.8rem;color:#64748b;line-height:1.6;margin-bottom:1rem;flex:1}
    .hsec-footer{display:flex;align-items:center;justify-content:space-between;padding-top:1rem;border-top:1px solid #334155;margin-top:auto}
    .hsec-status{display:inline-flex;align-items:center;gap:0.35rem;font-size:0.72rem;font-weight:600;padding:0.25rem 0.6rem;border-radius:999px}
    .hsec-status.ok{background:rgba(22,163,74,0.12);color:#4ade80;border:1px solid rgba(22,163,74,0.25)}
    .hsec-status.warn{background:rgba(245,158,11,0.12);color:#fbbf24;border:1px solid rgba(245,158,11,0.25)}
    .hsec-status.info{background:rgba(37,99,235,0.12);color:#60a5fa;border:1px solid rgba(37,99,235,0.25)}
    .hsec-btn{font-size:0.75rem;font-weight:700;color:#60a5fa;background:none;border:none;cursor:pointer;padding:0;display:flex;align-items:center;gap:0.3rem;transition:0.15s}
    .hsec-btn:hover{color:#93c5fd}

    .home-quick-actions{background:#1e293b;border:1px solid #334155;border-radius:16px;padding:1.5rem}
    .home-qa-title{font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#475569;margin-bottom:1rem}
    .home-qa-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem}
    .qa-btn{background:#0f172a;border:1px solid #334155;border-radius:10px;padding:1rem;cursor:pointer;transition:0.15s;text-align:left;display:flex;flex-direction:column;gap:0.3rem}
    .qa-btn:hover{border-color:#475569;background:#1e293b}
    .qa-icon{font-size:1.25rem}
    .qa-label{font-size:0.82rem;font-weight:700;color:#e2e8f0}
    .qa-sub{font-size:0.72rem;color:#475569}

    /* ===== RESPONSIVE ===== */
    @media(max-width:768px){
      .sidebar{width:100%;height:auto;position:relative;flex-direction:row;flex-wrap:wrap}
      .main{margin-left:0}
      .diagram-grid{grid-template-columns:1fr}
      .form-grid{grid-template-columns:1fr}
      .stats-bar{grid-template-columns:repeat(2,1fr)}
      .meta-grid{grid-template-columns:1fr}
      .home-stats{grid-template-columns:repeat(2,1fr)}
      .home-sections{grid-template-columns:1fr}
      .home-qa-grid{grid-template-columns:repeat(2,1fr)}
    }

    /* Progress bar */
    .upload-progress{display:none;margin-top:0.75rem}
    .progress-bar{height:4px;background:#334155;border-radius:2px;overflow:hidden}
    .progress-fill{height:100%;background:linear-gradient(90deg,#2563eb,#0d9488);width:0%;transition:width 0.3s;border-radius:2px}
    .progress-text{font-size:0.72rem;color:#64748b;margin-top:4px;text-align:center}

    .divider{border:none;border-top:1px solid #334155;margin:1.25rem 0}
  </style>
</head>
<body>

<!-- ===== TOAST CONTAINER ===== -->
<div class="toast-container" id="toastContainer"></div>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <h2><span>&lt;</span>Kyla<span>/&gt;</span> <span style="color:#64748b;font-weight:400">Admin</span></h2>
    <p>Portfolio Management System</p>
  </div>
  <div class="sidebar-user">
    <div class="user-avatar">KN</div>
    <div class="user-info">
      <strong><?= htmlspecialchars($_SESSION['admin_user'] ?? 'Admin') ?></strong>
      <span>Logged in · <?= date('H:i') ?></span>
    </div>
  </div>
  <nav class="sidebar-nav">
    <button class="nav-item <?= $activeTab==='home'?'active':'' ?>" onclick="switchTab('home')">
      <span class="nav-icon"></span> Home
    </button>
    <div class="nav-section-label">Content</div>
    <button class="nav-item <?= $activeTab==='diagrams'?'active':'' ?>" onclick="switchTab('diagrams')">
      <span class="nav-icon"></span> Diagram Images
      <span class="nav-badge"><?= $diagUploaded ?>/<?= $totalDiag ?></span>
    </button>
    <button class="nav-item <?= $activeTab==='projects'?'active':'' ?>" onclick="switchTab('projects')">
      <span class="nav-icon"></span> Projects
      <span class="nav-badge"><?= $totalProj ?></span>
    </button>
    <button class="nav-item <?= $activeTab==='skills'?'active':'' ?>" onclick="switchTab('skills')">
      <span class="nav-icon"></span> Skills &amp; Certs
      <span class="nav-badge"><?= $totalSkills ?></span>
    </button>
    <button class="nav-item <?= $activeTab==='meta'?'active':'' ?>" onclick="switchTab('meta')">
      <span class="nav-icon"></span> Info Umum
    </button>
    <div class="nav-section-label">Tools</div>
    <button class="nav-item <?= $activeTab==='export'?'active':'' ?>" onclick="switchTab('export')">
      <span class="nav-icon"></span> Export &amp; Deploy
    </button>
  </nav>
  <div class="sidebar-footer">
    <a href="../index.html" target="_blank" class="btn-view-portfolio">
      <span></span> Lihat Portfolio
    </a>
    <a href="logout.php" class="btn-logout">
      <span></span> Logout
    </a>
  </div>
</aside>

<!-- ===== MAIN ===== -->
<main class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title" id="topbarTitle"> Dashboard</div>
      <div class="topbar-subtitle" id="topbarSub">Kelola konten portfolio kamu</div>
    </div>
    <div class="topbar-actions">
      <span class="last-saved"> Terakhir disimpan: <?= htmlspecialchars($data['last_updated'] ?? '–') ?></span>
      <button class="btn-export" onclick="switchTab('export')"> Export & Deploy</button>
    </div>
  </div>

  <div class="content">

    <!-- ============================================================
         TAB: HOME
    ============================================================ -->
    <div class="tab-panel <?= $activeTab==='home'?'active':'' ?>" id="tab-home">

      <div class="home-greeting">
        <h1> Selamat datang, <?= htmlspecialchars($data['meta']['name'] ?? 'Admin') ?>!</h1>
        <p>Kelola konten portfolio kamu dari sini. Semua perubahan bisa langsung di-export ke GitHub Pages.</p>
      </div>

      <!-- Stats Overview -->
      <div class="home-stats">
        <div class="hstat accent">
          <div class="hstat-icon"></div>
          <div class="hstat-val"><?= $diagUploaded ?>/<?= $totalDiag ?></div>
          <div class="hstat-label">Diagram Terupload</div>
        </div>
        <div class="hstat green">
          <div class="hstat-icon">🟢</div>
          <div class="hstat-val"><?= $liveProjects ?></div>
          <div class="hstat-label">Proyek Live</div>
        </div>
        <div class="hstat teal">
          <div class="hstat-icon"></div>
          <div class="hstat-val"><?= $totalProj ?></div>
          <div class="hstat-label">Total Proyek</div>
        </div>
        <div class="hstat purple">
          <div class="hstat-icon"></div>
          <div class="hstat-val"><?= $totalSkills ?></div>
          <div class="hstat-label">Total Skills</div>
        </div>
      </div>

      <!-- Section Cards -->
      <div class="home-sections-title">Kelola Per Section</div>
      <div class="home-sections">

        <!-- Diagram Images -->
        <div class="hsec-card" onclick="switchTab('diagrams')">
          <div class="hsec-card-header">
            <div class="hsec-icon-wrap blue"></div>
            <span class="hsec-arrow">→</span>
          </div>
          <div class="hsec-title">Diagram Images</div>
          <div class="hsec-desc">Upload gambar asli BPMN, Use Case, ERD, dan Sequence Diagram kamu. Gambar akan tampil langsung di portfolio.</div>
          <div class="hsec-footer">
            <?php if ($diagUploaded === $totalDiag): ?>
              <span class="hsec-status ok"> Semua terupload (<?= $diagUploaded ?>/<?= $totalDiag ?>)</span>
            <?php else: ?>
              <span class="hsec-status warn"> <?= $diagUploaded ?>/<?= $totalDiag ?> terupload</span>
            <?php endif; ?>
            <button class="hsec-btn" onclick="event.stopPropagation();switchTab('diagrams')">Upload → </button>
          </div>
        </div>

        <!-- Projects -->
        <div class="hsec-card" onclick="switchTab('projects')">
          <div class="hsec-card-header">
            <div class="hsec-icon-wrap green"></div>
            <span class="hsec-arrow">→</span>
          </div>
          <div class="hsec-title">Projects</div>
          <div class="hsec-desc">Edit judul, deskripsi, tech stack, fitur unggulan, dan status tiap proyek portfolio kamu.</div>
          <div class="hsec-footer">
            <span class="hsec-status ok"> <?= $totalProj ?> proyek · <?= $liveProjects ?> live</span>
            <button class="hsec-btn" onclick="event.stopPropagation();switchTab('projects')">Edit → </button>
          </div>
        </div>

        <!-- Skills -->
        <div class="hsec-card" onclick="switchTab('skills')">
          <div class="hsec-card-header">
            <div class="hsec-icon-wrap amber"></div>
            <span class="hsec-arrow">→</span>
          </div>
          <div class="hsec-title">Skills &amp; Sertifikasi</div>
          <div class="hsec-desc">Tambah, hapus, atau ubah level skill kamu per kategori (Backend, Frontend, Database, System Analysis, dll).</div>
          <div class="hsec-footer">
            <span class="hsec-status info"> <?= $totalSkills ?> skills · <?= count($data['skills']) ?> kategori</span>
            <button class="hsec-btn" onclick="event.stopPropagation();switchTab('skills')">Kelola → </button>
          </div>
        </div>

        <!-- Info Umum -->
        <div class="hsec-card" onclick="switchTab('meta')">
          <div class="hsec-card-header">
            <div class="hsec-icon-wrap teal"></div>
            <span class="hsec-arrow">→</span>
          </div>
          <div class="hsec-title">Info Umum</div>
          <div class="hsec-desc">Edit nama, jabatan, email, nomor HP, LinkedIn, lokasi, GPA, dan status open to work yang tampil di portfolio.</div>
          <div class="hsec-footer">
            <span class="hsec-status info"> <?= htmlspecialchars($data['meta']['email'] ?? '–') ?></span>
            <button class="hsec-btn" onclick="event.stopPropagation();switchTab('meta')">Edit → </button>
          </div>
        </div>

        <!-- Export -->
        <div class="hsec-card" onclick="switchTab('export')" style="grid-column:1/-1;background:linear-gradient(135deg,rgba(37,99,235,0.08),rgba(13,148,136,0.05));border-color:rgba(37,99,235,0.25)">
          <div class="hsec-card-header">
            <div class="hsec-icon-wrap purple"></div>
            <span class="hsec-arrow">→</span>
          </div>
          <div class="hsec-title">Export &amp; Deploy ke GitHub Pages</div>
          <div class="hsec-desc">Setelah semua konten diupdate, generate ulang <code style="background:#0f172a;padding:0.1rem 0.4rem;border-radius:4px;font-size:0.78rem;color:#60a5fa">index.html</code> lalu push ke GitHub. Portfolio kamu langsung live!</div>
          <div class="hsec-footer">
            <span class="hsec-status info"> Terakhir disimpan: <?= htmlspecialchars($data['last_updated'] ?? '–') ?></span>
            <a href="export.php" target="_blank" class="hsec-btn" onclick="event.stopPropagation()"> Generate Sekarang → </a>
          </div>
        </div>

      </div>

      <!-- Quick Actions -->
      <div class="home-quick-actions">
        <div class="home-qa-title"> Quick Actions</div>
        <div class="home-qa-grid">
          <button class="qa-btn" onclick="switchTab('diagrams')">
            <div class="qa-icon"></div>
            <div class="qa-label">Upload Diagram</div>
            <div class="qa-sub">BPMN, Use Case, ERD, Sequence</div>
          </button>
          <button class="qa-btn" onclick="switchTab('projects')">
            <div class="qa-icon"></div>
            <div class="qa-label">Edit Project</div>
            <div class="qa-sub"><?= $totalProj ?> proyek tersedia</div>
          </button>
          <button class="qa-btn" onclick="switchTab('skills')">
            <div class="qa-icon"></div>
            <div class="qa-label">Tambah Skill</div>
            <div class="qa-sub"><?= $totalSkills ?> skills saat ini</div>
          </button>
          <button class="qa-btn" onclick="switchTab('meta')">
            <div class="qa-icon"></div>
            <div class="qa-label">Update Info</div>
            <div class="qa-sub">Nama, email, kontak</div>
          </button>
          <a href="export.php" target="_blank" class="qa-btn" style="text-decoration:none">
            <div class="qa-icon"></div>
            <div class="qa-label">Generate HTML</div>
            <div class="qa-sub">Export ke GitHub Pages</div>
          </a>
          <a href="../index.html" target="_blank" class="qa-btn" style="text-decoration:none">
            <div class="qa-icon"></div>
            <div class="qa-label">Preview Portfolio</div>
            <div class="qa-sub">Buka di tab baru</div>
          </a>
        </div>
      </div>

    </div>

    <!-- ============================================================
         TAB: DIAGRAMS
    ============================================================ -->
    <div class="tab-panel <?= $activeTab==='diagrams'?'active':'' ?>" id="tab-diagrams">
      <div class="panel-title"> Diagram Images</div>
      <div class="panel-sub">Upload gambar diagram asli kamu — BPMN, Use Case, ERD, Sequence (PNG/JPG/SVG/WebP, maks 10MB)</div>

      <div class="diagram-grid">
        <?php foreach ($data['diagrams'] as $diag): ?>
        <div class="diag-card" id="diag-card-<?= $diag['id'] ?>">
          <div class="diag-preview" onclick="triggerUpload('<?= $diag['id'] ?>')">
            <?php if (!empty($diag['image'])): ?>
              <img src="<?= htmlspecialchars($diag['image']) ?>" alt="<?= htmlspecialchars($diag['label']) ?>" id="diag-img-<?= $diag['id'] ?>"/>
            <?php else: ?>
              <div class="diag-preview-empty" id="diag-empty-<?= $diag['id'] ?>">
                <div class="empty-icon"></div>
                <span>Klik untuk upload</span>
              </div>
            <?php endif; ?>
            <div class="diag-preview-overlay">
              <div class="overlay-btn"> Upload Gambar</div>
              <?php if (!empty($diag['image'])): ?>
              <div class="overlay-btn danger" onclick="event.stopPropagation();deleteImage('<?= $diag['id'] ?>')"> Hapus</div>
              <?php endif; ?>
            </div>
          </div>
          <div class="diag-body">
            <div class="diag-label"><?= htmlspecialchars($diag['label']) ?></div>
            <div class="diag-title"><?= htmlspecialchars($diag['title']) ?></div>
            <div class="diag-status <?= !empty($diag['image'])?'has-img':'no-img' ?>" id="diag-status-<?= $diag['id'] ?>">
              <?= !empty($diag['image']) ? ' Gambar tersedia' : ' Belum ada gambar' ?>
            </div>

            <!-- Hidden file input -->
            <input type="file" id="file-<?= $diag['id'] ?>" accept="image/*" style="display:none"
                   onchange="uploadDiagram('<?= $diag['id'] ?>', this)"/>

            <!-- Upload progress -->
            <div class="upload-progress" id="progress-<?= $diag['id'] ?>">
              <div class="progress-bar"><div class="progress-fill" id="prog-fill-<?= $diag['id'] ?>"></div></div>
              <div class="progress-text" id="prog-text-<?= $diag['id'] ?>">Uploading…</div>
            </div>

            <div class="diag-actions">
              <button class="btn-sm btn-primary" onclick="triggerUpload('<?= $diag['id'] ?>')"> Upload</button>
              <button class="btn-sm btn-secondary" onclick="openEditDiag('<?= $diag['id'] ?>')"> Edit Info</button>
              <?php if (!empty($diag['image'])): ?>
              <button class="btn-sm btn-danger" onclick="deleteImage('<?= $diag['id'] ?>')"></button>
              <?php endif; ?>
            </div>

            <!-- Edit info form (collapsible) -->
            <div id="edit-diag-<?= $diag['id'] ?>" style="display:none;margin-top:1rem;padding-top:1rem;border-top:1px solid #334155">
              <div class="form-group" style="margin-bottom:0.75rem">
                <label>Judul Diagram</label>
                <input type="text" id="diag-title-<?= $diag['id'] ?>" value="<?= htmlspecialchars($diag['title']) ?>"/>
              </div>
              <div class="form-group" style="margin-bottom:0.75rem">
                <label>Deskripsi</label>
                <textarea id="diag-desc-<?= $diag['id'] ?>" rows="2"><?= htmlspecialchars($diag['description']) ?></textarea>
              </div>
              <div class="form-group" style="margin-bottom:0.75rem">
                <label>Highlights (satu per baris)</label>
                <textarea id="diag-hl-<?= $diag['id'] ?>" rows="3"><?= htmlspecialchars(implode("\n", $diag['highlights'])) ?></textarea>
              </div>
              <div style="display:flex;gap:0.5rem">
                <button class="btn-sm btn-primary" onclick="saveDiagInfo('<?= $diag['id'] ?>')"> Simpan</button>
                <button class="btn-sm btn-secondary" onclick="openEditDiag('<?= $diag['id'] ?>')">Batal</button>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ============================================================
         TAB: PROJECTS
    ============================================================ -->
    <div class="tab-panel <?= $activeTab==='projects'?'active':'' ?>" id="tab-projects">
      <div class="panel-title" style="display: flex; justify-content: space-between; align-items: center;">
        Projects
        <div>
            <button onclick="addProjectManual()" class="btn-success" style="padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; border: none; font-weight: 600; margin-right: 0.5rem;">➕ Tambah Manual</button>
            <a href="import.php" class="btn-primary" style="text-decoration: none; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem;">📁 Import ZIP</a>
        </div>
      </div>
      <div class="panel-sub">Edit informasi tiap proyek — judul, deskripsi, tech stack, fitur unggulan</div>

      <div class="project-list">
        <?php foreach ($data['projects'] as $proj): ?>
        <div class="proj-item" id="proj-item-<?= $proj['id'] ?>">
          <div class="proj-header" onclick="toggleProj('<?= $proj['id'] ?>')">
            <span class="proj-num"><?= htmlspecialchars($proj['number']) ?></span>
            <span class="proj-icon"><?= htmlspecialchars($proj['icon']) ?></span>
            <div class="proj-info">
              <div class="proj-name"><?= htmlspecialchars($proj['title']) ?></div>
              <div class="proj-tagline"><?= htmlspecialchars($proj['tagline']) ?></div>
            </div>
            <div class="proj-badges">
              <?php
                $bc = 'badge-done';
                if($proj['status']==='live') $bc='badge-live';
                elseif($proj['status']==='indev') $bc='badge-indev';
              ?>
              <span class="badge <?= $bc ?>"><?= htmlspecialchars($proj['status_label']) ?></span>
              <span class="badge" style="background:#1e293b;color:#64748b;border:1px solid #334155"><?= htmlspecialchars($proj['type']) ?></span>
            </div>
            <span class="proj-toggle" id="toggle-<?= $proj['id'] ?>">▾</span>
          </div>
          <div class="proj-body" id="proj-body-<?= $proj['id'] ?>">
            <div class="form-grid" style="margin-top:1rem">
              <div class="form-group">
                <label>Judul Proyek</label>
                <input type="text" id="p-title-<?= $proj['id'] ?>" value="<?= htmlspecialchars($proj['title']) ?>"/>
              </div>
              <div class="form-group">
                <label>Tagline / Subtitle</label>
                <input type="text" id="p-tagline-<?= $proj['id'] ?>" value="<?= htmlspecialchars($proj['tagline']) ?>"/>
              </div>
              <div class="form-group">
                <label>Tipe</label>
                <input type="text" id="p-type-<?= $proj['id'] ?>" value="<?= htmlspecialchars($proj['type']) ?>"/>
              </div>
              <div class="form-group">
                <label>Status Label</label>
                <input type="text" id="p-status-<?= $proj['id'] ?>" value="<?= htmlspecialchars($proj['status_label']) ?>"/>
              </div>
              <div class="form-group full">
                <label>Deskripsi</label>
                <textarea id="p-desc-<?= $proj['id'] ?>" rows="3"><?= htmlspecialchars($proj['description']) ?></textarea>
              </div>
              <div class="form-group full">
                <label>Tech Stack (pisah dengan koma)</label>
                <input type="text" id="p-tech-<?= $proj['id'] ?>" value="<?= htmlspecialchars(implode(', ', $proj['tech'])) ?>"/>
                <span class="form-hint">Contoh: PHP, Laravel, MySQL, JavaScript</span>
              </div>
              <div class="form-group full">
                <label>Fitur Unggulan (satu per baris)</label>
                <textarea id="p-feat-<?= $proj['id'] ?>" rows="4"><?= htmlspecialchars(implode("\n", $proj['features'])) ?></textarea>
              </div>
              <div class="form-group full">
                <label>Filter Tags (pisah dengan spasi)</label>
                <input type="text" id="p-tags-<?= $proj['id'] ?>" value="<?= htmlspecialchars($proj['tags']) ?>"/>
                <span class="form-hint">Contoh: live php laravel enterprise analysis</span>
              </div>
              <div class="form-group">
                <label>Link Eksternal (Opsional)</label>
                <input type="text" id="p-link-<?= $proj['id'] ?>" value="<?= htmlspecialchars($proj['link_url'] ?? '') ?>" placeholder="https://..."/>
                <span class="form-hint">Kosongkan jika tidak ada website live.</span>
              </div>
              <div class="form-group">
                <label>File Proyek (.zip, .pdf, .xlsx)</label>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                  <input type="text" id="p-attachment-<?= $proj['id'] ?>" value="<?= htmlspecialchars($proj['attachment'] ?? '') ?>" placeholder="Belum ada file" readonly style="background: #1e293b; color: #94a3b8;"/>
                  <button class="btn-sm btn-secondary" onclick="document.getElementById('file-upload-<?= $proj['id'] ?>').click()">Upload File</button>
                  <input type="file" id="file-upload-<?= $proj['id'] ?>" accept=".zip,.pdf,.xls,.xlsx" style="display:none;" onchange="uploadProjectFile('<?= $proj['id'] ?>', this)">
                </div>
                <!-- Progress Bar -->
                <div id="proj-prog-<?= $proj['id'] ?>" style="display:none; margin-top:0.5rem;">
                  <div style="height:4px; background:#334155; border-radius:2px; overflow:hidden;">
                    <div id="proj-fill-<?= $proj['id'] ?>" style="width:0%; height:100%; background:#2563eb; transition:0.2s;"></div>
                  </div>
                  <span id="proj-text-<?= $proj['id'] ?>" style="font-size:0.7rem; color:#94a3b8;">Uploading... 0%</span>
                </div>
                <span class="form-hint">Pengunjung bisa mendownload/melihat file ini.</span>
              </div>
            </div>
            <div class="form-actions">
              <button class="btn-sm btn-primary" onclick="saveProject('<?= $proj['id'] ?>')"> Simpan Perubahan</button>
              <span id="proj-save-msg-<?= $proj['id'] ?>" style="font-size:0.78rem;color:#4ade80;display:none"> Tersimpan!</span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ============================================================
         TAB: SKILLS
    ============================================================ -->
    <div class="tab-panel <?= $activeTab==='skills'?'active':'' ?>" id="tab-skills">
      <div class="panel-title"> Skills & Sertifikasi</div>
      <div class="panel-sub">Kelola kategori skill dan item skill kamu</div>

      <div class="card">
        <div class="card-title"><span class="card-title-icon"></span> Skill Categories</div>
        <div class="skill-cat-list" id="skillCatList">
          <?php foreach ($data['skills'] as $ci => $cat): ?>
          <div class="skill-cat-item" data-cat="<?= $ci ?>">
            <div class="skill-cat-header">
              <div class="skill-cat-name"><?= htmlspecialchars($cat['icon']) ?> <?= htmlspecialchars($cat['category']) ?></div>
            </div>
            <div class="skill-tags-editor" id="skill-tags-<?= $ci ?>">
              <?php foreach ($cat['items'] as $si => $skill): ?>
              <span class="skill-chip <?= htmlspecialchars($skill['level']) ?>" data-cat="<?= $ci ?>" data-si="<?= $si ?>">
                <?= htmlspecialchars($skill['name']) ?>
                <button class="remove-skill" onclick="removeSkill(<?= $ci ?>, <?= $si ?>)" title="Hapus">×</button>
              </span>
              <?php endforeach; ?>
            </div>
            <div class="add-skill-row">
              <input type="text" placeholder="Nama skill baru…" id="new-skill-<?= $ci ?>"/>
              <select id="new-level-<?= $ci ?>">
                <option value="expert">Expert</option>
                <option value="intermediate" selected>Intermediate</option>
                <option value="beginner">Beginner</option>
                <option value="ai-tag">AI Tool</option>
              </select>
              <button class="btn-sm btn-primary" onclick="addSkill(<?= $ci ?>)">+ Add</button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="form-actions" style="margin-top:1.25rem">
          <button class="btn-sm btn-success" onclick="saveSkills()"> Simpan Semua Skills</button>
          <span id="skills-save-msg" style="font-size:0.78rem;color:#4ade80;display:none"> Skills tersimpan!</span>
        </div>
      </div>
    </div>

    <!-- ============================================================
         TAB: META
    ============================================================ -->
    <div class="tab-panel <?= $activeTab==='meta'?'active':'' ?>" id="tab-meta">
      <div class="panel-title"> Info Umum</div>
      <div class="panel-sub">Informasi dasar yang tampil di hero, navbar, dan footer portfolio</div>

      <div class="card">
        <div class="card-title"><span class="card-title-icon"></span> Informasi Pribadi</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" id="meta-name" value="<?= htmlspecialchars($data['meta']['name']) ?>"/>
          </div>
          <div class="form-group">
            <label>GPA</label>
            <input type="text" id="meta-gpa" value="<?= htmlspecialchars($data['meta']['gpa']) ?>"/>
          </div>
          <div class="form-group full">
            <label>Title / Jabatan</label>
            <input type="text" id="meta-title" value="<?= htmlspecialchars($data['meta']['title']) ?>"/>
          </div>
          <div class="form-group full">
            <label>Tagline (Hero Desc)</label>
            <input type="text" id="meta-tagline" value="<?= htmlspecialchars($data['meta']['tagline']) ?>"/>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" id="meta-email" value="<?= htmlspecialchars($data['meta']['email']) ?>"/>
          </div>
          <div class="form-group">
            <label>Nomor HP / WhatsApp</label>
            <input type="text" id="meta-phone" value="<?= htmlspecialchars($data['meta']['phone']) ?>"/>
          </div>
          <div class="form-group full">
            <label>LinkedIn URL</label>
            <input type="url" id="meta-linkedin" value="<?= htmlspecialchars($data['meta']['linkedin']) ?>"/>
          </div>
          <div class="form-group">
            <label>Lokasi</label>
            <input type="text" id="meta-location" value="<?= htmlspecialchars($data['meta']['location']) ?>"/>
          </div>
          <div class="form-group">
            <label>Status (Open to Work, dll)</label>
            <input type="text" id="meta-status" value="<?= htmlspecialchars($data['meta']['status']) ?>"/>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn-sm btn-primary" onclick="saveMeta()"> Simpan Info Umum</button>
          <span id="meta-save-msg" style="font-size:0.78rem;color:#4ade80;display:none"> Tersimpan!</span>
        </div>
      </div>
    </div>

    <!-- ============================================================
         TAB: EXPORT
    ============================================================ -->
    <div class="tab-panel <?= $activeTab==='export'?'active':'' ?>" id="tab-export">
      <div class="panel-title"> Export & Deploy</div>
      <div class="panel-sub">Generate file HTML statis dari data terbaru, lalu push ke GitHub Pages</div>

      <div class="stats-bar">
        <div class="stat-card">
          <div class="stat-icon"></div>
          <div><div class="stat-val"><?= count(array_filter($data['diagrams'], fn($d)=>!empty($d['image']))) ?>/<?= count($data['diagrams']) ?></div><div class="stat-label">Diagram terupload</div></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon"></div>
          <div><div class="stat-val"><?= count($data['projects']) ?></div><div class="stat-label">Proyek aktif</div></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon"></div>
          <div><div class="stat-val"><?= array_sum(array_map(fn($c)=>count($c['items']), $data['skills'])) ?></div><div class="stat-label">Total skills</div></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon"></div>
          <div><div class="stat-val"><?= !empty($data['last_updated']) ? substr($data['last_updated'],11,5) : '–' ?></div><div class="stat-label">Terakhir disimpan</div></div>
        </div>
      </div>

      <div class="card">
        <div class="card-title"><span class="card-title-icon"></span> Generate Static HTML</div>
        <p style="font-size:0.875rem;color:#94a3b8;line-height:1.7;margin-bottom:1.25rem">
          Klik tombol di bawah untuk meng-generate ulang <code style="background:#0f172a;padding:0.1rem 0.4rem;border-radius:4px;font-family:'Fira Code',monospace;color:#60a5fa">index.html</code> dari data terbaru di dashboard. File akan otomatis ter-update dan siap di-push ke GitHub Pages.
        </p>
        <div style="background:#0f172a;border:1px solid #334155;border-radius:10px;padding:1rem;margin-bottom:1.25rem;font-family:'Fira Code',monospace;font-size:0.78rem;color:#94a3b8;line-height:1.8">
          <span style="color:#475569"># Workflow setelah generate:</span><br/>
          <span style="color:#4ade80">$</span> git add .<br/>
          <span style="color:#4ade80">$</span> git commit -m "Update portfolio content"<br/>
          <span style="color:#4ade80">$</span> git push origin main<br/>
          <span style="color:#475569"># → Otomatis live di GitHub Pages </span>
        </div>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap">
          <a href="export.php" target="_blank" class="btn-sm btn-success" style="font-size:0.85rem;padding:0.6rem 1.25rem">
             Generate index.html
          </a>
          <a href="../index.html" target="_blank" class="btn-sm btn-secondary" style="font-size:0.85rem;padding:0.6rem 1.25rem">
             Preview Hasil
          </a>
        </div>
      </div>

      <div class="card">
        <div class="card-title"><span class="card-title-icon"></span> File yang Akan di-Push ke GitHub</div>
        <div style="background:#0f172a;border-radius:8px;padding:1rem;font-family:'Fira Code',monospace;font-size:0.8rem;color:#94a3b8;line-height:2">
           portfolio-kyla/<br/>
          &nbsp;&nbsp;├──  <span style="color:#60a5fa">index.html</span> &nbsp;<span style="color:#475569">← di-generate dari admin</span><br/>
          &nbsp;&nbsp;├──  <span style="color:#60a5fa">style.css</span><br/>
          &nbsp;&nbsp;├──  <span style="color:#60a5fa">script.js</span><br/>
          &nbsp;&nbsp;├──  <span style="color:#f59e0b">assets/diagrams/</span> &nbsp;<span style="color:#475569">← gambar upload kamu</span><br/>
          &nbsp;&nbsp;├──  <span style="color:#f59e0b">data/</span> &nbsp;<span style="color:#ef4444">← jangan push! (tambah ke .gitignore)</span><br/>
          &nbsp;&nbsp;└──  <span style="color:#ef4444">admin/</span> &nbsp;<span style="color:#ef4444">← jangan push! (tambah ke .gitignore)</span>
        </div>
        <div style="margin-top:1rem;padding:0.75rem 1rem;background:rgba(220,38,38,0.08);border:1px solid rgba(220,38,38,0.2);border-radius:8px;font-size:0.8rem;color:#f87171">
           <strong>Penting:</strong> Pastikan folder <code>/admin</code> dan <code>/data</code> sudah masuk <code>.gitignore</code> agar password dan data privat tidak ter-push ke GitHub!
        </div>
      </div>
    </div>

  </div><!-- .content -->
</main>

<script>
// ============================================================
// SKILL DATA (for client-side manipulation)
// ============================================================
let skillData = <?= json_encode($data['skills'], JSON_UNESCAPED_UNICODE) ?>;

// ============================================================
// TAB SWITCHING
// ============================================================
function switchTab(tab) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById('tab-' + tab)?.classList.add('active');
  // Update active nav
  const navItems = document.querySelectorAll('.nav-item');
  navItems.forEach(n => {
    if (n.getAttribute('onclick')?.includes("'" + tab + "'")) n.classList.add('active');
  });
  const titles = {
    home:     [' Dashboard Home', 'Overview semua section portfolio kamu'],
    diagrams: [' Diagram Images', 'Upload gambar BPMN, Use Case, ERD, Sequence'],
    projects: [' Projects', 'Edit konten tiap proyek'],
    skills:   [' Skills & Sertifikasi', 'Kelola skill dan sertifikasi'],
    meta:     [' Info Umum', 'Informasi pribadi & kontak'],
    export:   [' Export & Deploy', 'Generate dan publish ke GitHub Pages'],
  };
  if (titles[tab]) {
    document.getElementById('topbarTitle').textContent = titles[tab][0];
    document.getElementById('topbarSub').textContent   = titles[tab][1];
  }
}

// ============================================================
// TOAST
// ============================================================
function showToast(msg, type='success') {
  const tc = document.getElementById('toastContainer');
  const t  = document.createElement('div');
  t.className = `toast ${type}`;
  t.innerHTML = (type==='success'?' ':' ') + msg;
  tc.appendChild(t);
  setTimeout(() => t.remove(), 3500);
}

// ============================================================
// DIAGRAM UPLOAD
// ============================================================
function triggerUpload(id) {
  document.getElementById('file-' + id).click();
}

function uploadDiagram(id, input) {
  const file = input.files[0];
  if (!file) return;

  const fd = new FormData();
  fd.append('action', 'upload_diagram');
  fd.append('diagram_id', id);
  fd.append('diagram_image', file);

  const prog = document.getElementById('progress-' + id);
  const fill = document.getElementById('prog-fill-' + id);
  const text = document.getElementById('prog-text-' + id);
  prog.style.display = 'block';

  const xhr = new XMLHttpRequest();
  xhr.upload.addEventListener('progress', e => {
    if (e.lengthComputable) {
      const pct = Math.round((e.loaded / e.total) * 100);
      fill.style.width = pct + '%';
      text.textContent = `Uploading… ${pct}%`;
    }
  });
  xhr.addEventListener('load', () => {
    prog.style.display = 'none';
    try {
      const res = JSON.parse(xhr.responseText);
      if (res.ok) {
        showToast(res.msg);
        updateDiagPreview(id, res.url);
      } else {
        showToast(res.msg, 'error');
      }
    } catch(e) { showToast('Response error', 'error'); }
    input.value = '';
  });
  xhr.addEventListener('error', () => { prog.style.display = 'none'; showToast('Upload gagal!', 'error'); });
  xhr.open('POST', 'actions.php');
  xhr.send(fd);
}

function updateDiagPreview(id, url) {
  const card = document.getElementById('diag-card-' + id);
  const preview = card.querySelector('.diag-preview');
  // Replace empty state with image
  const empty = document.getElementById('diag-empty-' + id);
  if (empty) empty.remove();
  let img = document.getElementById('diag-img-' + id);
  if (!img) {
    img = document.createElement('img');
    img.id = 'diag-img-' + id;
    preview.insertBefore(img, preview.firstChild);
  }
  img.src = url + '?t=' + Date.now();
  // Update status
  const status = document.getElementById('diag-status-' + id);
  if (status) { status.className = 'diag-status has-img'; status.textContent = ' Gambar tersedia'; }
}

function deleteImage(id) {
  if (!confirm('Hapus gambar ini?')) return;
  fetch('actions.php', { method:'POST', body: new URLSearchParams({ action:'upload_diagram', diagram_id:id }) })
    .then(r => r.json()).then(res => {
      if (res.ok) { showToast('Gambar dihapus'); location.reload(); }
      else showToast(res.msg, 'error');
    });
}

function openEditDiag(id) {
  const el = document.getElementById('edit-diag-' + id);
  el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

function saveDiagInfo(id) {
  const fd = new FormData();
  fd.append('action', 'update_diagram');
  fd.append('diagram_id', id);
  fd.append('title',       document.getElementById('diag-title-' + id).value);
  fd.append('description', document.getElementById('diag-desc-' + id).value);
  fd.append('highlights',  document.getElementById('diag-hl-' + id).value);
  fetch('actions.php', { method:'POST', body: fd })
    .then(r => r.json()).then(res => {
      res.ok ? showToast(res.msg) : showToast(res.msg, 'error');
      if (res.ok) openEditDiag(id);
    });
}

// ============================================================
// PROJECT EDITOR
// ============================================================
function toggleProj(id) {
  const body   = document.getElementById('proj-body-' + id);
  const toggle = document.getElementById('toggle-' + id);
  const open   = body.classList.toggle('open');
  toggle.classList.toggle('open', open);
}

function saveProject(id) {
  const fd = new FormData();
  fd.append('action',     'update_project');
  fd.append('project_id', id);
  fd.append('title',       document.getElementById('p-title-'   + id).value);
  fd.append('tagline',     document.getElementById('p-tagline-' + id).value);
  fd.append('type',        document.getElementById('p-type-'    + id).value);
  fd.append('status_label',document.getElementById('p-status-'  + id).value);
  fd.append('description', document.getElementById('p-desc-'    + id).value);
  fd.append('tech',        document.getElementById('p-tech-'    + id).value);
  fd.append('features',    document.getElementById('p-feat-'    + id).value);
  fd.append('tags',        document.getElementById('p-tags-'    + id).value);
  fd.append('link_url',    document.getElementById('p-link-'    + id).value);
  fd.append('attachment',  document.getElementById('p-attachment-'+ id).value);

  fetch('actions.php', { method:'POST', body: fd })
    .then(r => r.json()).then(res => {
      if (res.ok) {
        showToast(res.msg);
        const msg = document.getElementById('proj-save-msg-' + id);
        msg.style.display = 'inline';
        setTimeout(() => msg.style.display = 'none', 3000);
      } else showToast(res.msg, 'error');
    });
}

function addProjectManual() {
  if (!confirm('Buat proyek baru kosong?')) return;
  const fd = new FormData();
  fd.append('action', 'add_project');
  fetch('actions.php', { method:'POST', body: fd })
    .then(r => r.json()).then(res => {
      if (res.ok) {
        showToast(res.msg);
        setTimeout(() => location.reload(), 1000);
      } else showToast(res.msg, 'error');
    });
}

function uploadProjectFile(id, input) {
  const file = input.files[0];
  if (!file) return;

  const fd = new FormData();
  fd.append('action', 'upload_project_file');
  fd.append('project_id', id);
  fd.append('project_file', file);

  const prog = document.getElementById('proj-prog-' + id);
  const fill = document.getElementById('proj-fill-' + id);
  const text = document.getElementById('proj-text-' + id);
  prog.style.display = 'block';

  const xhr = new XMLHttpRequest();
  xhr.upload.addEventListener('progress', e => {
    if (e.lengthComputable) {
      const pct = Math.round((e.loaded / e.total) * 100);
      fill.style.width = pct + '%';
      text.textContent = `Uploading… ${pct}%`;
    }
  });
  xhr.addEventListener('load', () => {
    prog.style.display = 'none';
    try {
      const res = JSON.parse(xhr.responseText);
      if (res.ok) {
        showToast(res.msg);
        if (res.type === 'site') {
            document.getElementById('p-link-' + id).value = res.url;
            document.getElementById('p-attachment-' + id).value = '';
        } else {
            document.getElementById('p-attachment-' + id).value = res.url;
        }
      } else {
        showToast(res.msg, 'error');
      }
    } catch(e) { showToast('Response error', 'error'); }
    input.value = '';
  });
  xhr.addEventListener('error', () => { prog.style.display = 'none'; showToast('Upload gagal!', 'error'); });
  xhr.open('POST', 'actions.php');
  xhr.send(fd);
}

// ============================================================
// SKILLS EDITOR
// ============================================================
function removeSkill(ci, si) {
  skillData[ci].items.splice(si, 1);
  renderSkillCat(ci);
}

function addSkill(ci) {
  const nameEl  = document.getElementById('new-skill-' + ci);
  const levelEl = document.getElementById('new-level-' + ci);
  const name = nameEl.value.trim();
  if (!name) return;
  skillData[ci].items.push({ name, level: levelEl.value });
  nameEl.value = '';
  renderSkillCat(ci);
}

function renderSkillCat(ci) {
  const container = document.getElementById('skill-tags-' + ci);
  container.innerHTML = '';
  skillData[ci].items.forEach((skill, si) => {
    const span = document.createElement('span');
    span.className = `skill-chip ${skill.level}`;
    span.innerHTML = `${skill.name} <button class="remove-skill" onclick="removeSkill(${ci},${si})" title="Hapus">×</button>`;
    container.appendChild(span);
  });
}

function saveSkills() {
  const fd = new FormData();
  fd.append('action', 'update_skills');
  fd.append('skills', JSON.stringify(skillData));
  fetch('actions.php', { method:'POST', body: fd })
    .then(r => r.json()).then(res => {
      res.ok ? showToast(res.msg) : showToast(res.msg, 'error');
      if (res.ok) {
        const msg = document.getElementById('skills-save-msg');
        msg.style.display = 'inline';
        setTimeout(() => msg.style.display = 'none', 3000);
      }
    });
}

// ============================================================
// META SAVE
// ============================================================
function saveMeta() {
  const fd = new FormData();
  fd.append('action',   'update_meta');
  fd.append('name',     document.getElementById('meta-name').value);
  fd.append('title',    document.getElementById('meta-title').value);
  fd.append('tagline',  document.getElementById('meta-tagline').value);
  fd.append('email',    document.getElementById('meta-email').value);
  fd.append('phone',    document.getElementById('meta-phone').value);
  fd.append('linkedin', document.getElementById('meta-linkedin').value);
  fd.append('location', document.getElementById('meta-location').value);
  fd.append('gpa',      document.getElementById('meta-gpa').value);
  fd.append('status',   document.getElementById('meta-status').value);

  fetch('actions.php', { method:'POST', body: fd })
    .then(r => r.json()).then(res => {
      res.ok ? showToast(res.msg) : showToast(res.msg, 'error');
      if (res.ok) {
        const msg = document.getElementById('meta-save-msg');
        msg.style.display = 'inline';
        setTimeout(() => msg.style.display = 'none', 3000);
      }
    });
}

// ============================================================
// DRAG & DROP on drop zones
// ============================================================
document.querySelectorAll('.drop-zone').forEach(zone => {
  zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
  zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
  zone.addEventListener('drop', e => {
    e.preventDefault(); zone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) {
      const id = zone.dataset.diagId;
      const fakeInput = { files: [file] };
      uploadDiagram(id, { files: [file], value: '' });
    }
  });
});
</script>
</body>
</html>

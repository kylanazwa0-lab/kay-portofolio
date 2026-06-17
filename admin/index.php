<?php
// ============================================================
// index.php — Login page
// ============================================================
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

// Already logged in → redirect
if (isLoggedIn()) { header('Location: dashboard.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = trim($_POST['password'] ?? '');
    if (attemptLogin($user, $pass)) {
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login — Kyla Portfolio</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Inter',sans-serif;background:#0f172a;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1rem}
    .login-card{background:#1e293b;border:1px solid #334155;border-radius:20px;padding:2.5rem;width:100%;max-width:420px;box-shadow:0 25px 50px rgba(0,0,0,0.4)}
    .logo{text-align:center;margin-bottom:2rem}
    .logo-bracket{color:#2563eb}
    .logo h1{font-size:1.8rem;font-weight:800;color:#f8fafc;letter-spacing:-0.03em}
    .logo p{font-size:0.85rem;color:#64748b;margin-top:4px}
    .form-group{margin-bottom:1.25rem}
    .form-group label{display:block;font-size:0.8rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px}
    .form-group input{width:100%;padding:0.75rem 1rem;background:#0f172a;border:1.5px solid #334155;border-radius:10px;color:#f8fafc;font-size:0.95rem;font-family:inherit;outline:none;transition:0.2s}
    .form-group input:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.15)}
    .btn-login{width:100%;padding:0.8rem;background:linear-gradient(135deg,#2563eb,#0d9488);color:#fff;font-size:1rem;font-weight:700;border:none;border-radius:10px;cursor:pointer;transition:0.2s;margin-top:0.5rem}
    .btn-login:hover{opacity:0.9;transform:translateY(-1px)}
    .error{background:#450a0a;border:1px solid #7f1d1d;color:#fca5a5;padding:0.75rem 1rem;border-radius:8px;font-size:0.85rem;margin-bottom:1rem;text-align:center}
    .back-link{text-align:center;margin-top:1.5rem}
    .back-link a{font-size:0.82rem;color:#475569;text-decoration:none;transition:0.2s}
    .back-link a:hover{color:#2563eb}
    .shield{font-size:2.5rem;margin-bottom:0.5rem}
  </style>
</head>
<body>
  <div class="login-card">
    <div class="logo">
      <div class="shield">🔐</div>
      <h1><span class="logo-bracket">&lt;</span>Kyla<span class="logo-bracket">/&gt;</span> Admin</h1>
      <p>Portfolio Management Dashboard</p>
    </div>
    <?php if ($error): ?>
    <div class="error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="username" autocomplete="username" required/>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required/>
      </div>
      <button type="submit" class="btn-login">Masuk ke Dashboard →</button>
    </form>
    <div class="back-link">
      <a href="../index.html">← Kembali ke Portfolio</a>
    </div>
  </div>
</body>
</html>

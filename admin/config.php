<?php
// ============================================================
// Admin Config — Portfolio Kyla
// Jalankan HANYA di Laragon (localhost), jangan push file ini!
// ============================================================

define('ADMIN_USERNAME', 'kyla');
define('ADMIN_PASSWORD', 'kyla2026');   // ganti sesuai keinginan
define('SESSION_NAME',   'kyla_admin');
define('DATA_FILE',      __DIR__ . '/../data/portfolio.json');
define('UPLOAD_DIR',     __DIR__ . '/../assets/diagrams/');
define('UPLOAD_URL',     '../assets/diagrams/');

// Allowed image types
define('ALLOWED_TYPES', ['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml']);
define('MAX_FILE_SIZE',  10 * 1024 * 1024); // 10MB

// Timezone
date_default_timezone_set('Asia/Jakarta');

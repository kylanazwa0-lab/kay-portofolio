<?php
// ============================================================
// actions.php — Handle all AJAX POST actions (UPDATED with skills)
// ============================================================
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/data.php';

requireLogin();
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$data   = loadData();

function jsonResponse(bool $ok, string $msg, array $extra = []): void {
    echo json_encode(array_merge(['ok' => $ok, 'msg' => $msg], $extra));
    exit;
}

function handleImageUpload(string $fieldName, string $prefix): string|false {
    if (empty($_FILES[$fieldName]['name'])) return false;
    $file = $_FILES[$fieldName];
    if ($file['error'] !== UPLOAD_ERR_OK) return false;
    if ($file['size'] > MAX_FILE_SIZE)    return false;
    if (!in_array($file['type'], ALLOWED_TYPES)) return false;

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = $prefix . '_' . time() . '.' . $ext;
    $dest     = UPLOAD_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) return false;
    return UPLOAD_URL . $filename;
}

// ============================================================
// ACTION: Update Meta
// ============================================================
if ($action === 'update_meta') {
    $fields = ['name','title','tagline','email','phone','linkedin','location','gpa','status'];
    foreach ($fields as $f) {
        if (isset($_POST[$f])) $data['meta'][$f] = trim($_POST[$f]);
    }
    $data['last_updated'] = date('Y-m-d H:i:s');
    saveData($data) ? jsonResponse(true, 'Info umum berhasil disimpan!') : jsonResponse(false, 'Gagal menyimpan data.');
}

// ============================================================
// ACTION: Upload Diagram Image
// ============================================================
elseif ($action === 'upload_diagram') {
    $id = trim($_POST['diagram_id'] ?? '');
    if (!$id) jsonResponse(false, 'Diagram ID tidak valid.');

    $imgUrl = handleImageUpload('diagram_image', 'diag_' . $id);
    if (!$imgUrl) jsonResponse(false, 'Upload gagal. Pastikan file valid (PNG/JPG/SVG/WebP, maks 10MB).');

    foreach ($data['diagrams'] as &$diag) {
        if ($diag['id'] === $id) {
            // Delete old file
            if (!empty($diag['image'])) {
                $old = __DIR__ . '/' . $diag['image'];
                if (file_exists($old)) @unlink($old);
            }
            $diag['image'] = $imgUrl;
            break;
        }
    }
    unset($diag);
    $data['last_updated'] = date('Y-m-d H:i:s');
    saveData($data) ? jsonResponse(true, 'Gambar berhasil diupload!', ['url' => $imgUrl])
                    : jsonResponse(false, 'Upload OK tapi gagal simpan data.');
}

// ============================================================
// ACTION: Delete Diagram Image
// ============================================================
elseif ($action === 'delete_diagram_image') {
    $id = trim($_POST['diagram_id'] ?? '');
    foreach ($data['diagrams'] as &$diag) {
        if ($diag['id'] === $id) {
            if (!empty($diag['image'])) {
                $old = __DIR__ . '/' . $diag['image'];
                if (file_exists($old)) @unlink($old);
            }
            $diag['image'] = '';
            break;
        }
    }
    unset($diag);
    $data['last_updated'] = date('Y-m-d H:i:s');
    saveData($data) ? jsonResponse(true, 'Gambar dihapus.') : jsonResponse(false, 'Gagal menyimpan.');
}

// ============================================================
// ACTION: Update Diagram Info
// ============================================================
elseif ($action === 'update_diagram') {
    $id = trim($_POST['diagram_id'] ?? '');
    foreach ($data['diagrams'] as &$diag) {
        if ($diag['id'] === $id) {
            if (isset($_POST['title']))       $diag['title']       = trim($_POST['title']);
            if (isset($_POST['description'])) $diag['description'] = trim($_POST['description']);
            if (isset($_POST['highlights'])) {
                $diag['highlights'] = array_values(array_filter(array_map('trim', explode("\n", $_POST['highlights']))));
            }
            break;
        }
    }
    unset($diag);
    $data['last_updated'] = date('Y-m-d H:i:s');
    saveData($data) ? jsonResponse(true, 'Diagram info diupdate!') : jsonResponse(false, 'Gagal menyimpan.');
}

// ============================================================
// ACTION: Update Project
// ============================================================
elseif ($action === 'update_project') {
    $id = trim($_POST['project_id'] ?? '');
    foreach ($data['projects'] as &$proj) {
        if ($proj['id'] === $id) {
            $tf = ['title','tagline','description','type','status_label','tags','link_url','attachment'];
            foreach ($tf as $f) {
                if (isset($_POST[$f])) $proj[$f] = trim($_POST[$f]);
            }
            if (isset($_POST['tech'])) {
                $proj['tech'] = array_values(array_filter(array_map('trim', explode(',', $_POST['tech']))));
            }
            if (isset($_POST['features'])) {
                $proj['features'] = array_values(array_filter(array_map('trim', explode("\n", $_POST['features']))));
            }
            break;
        }
    }
    unset($proj);
    $data['last_updated'] = date('Y-m-d H:i:s');
    saveData($data) ? jsonResponse(true, 'Proyek berhasil diupdate!') : jsonResponse(false, 'Gagal menyimpan.');
}

// ============================================================
// ACTION: Add Project Manual
// ============================================================
elseif ($action === 'add_project') {
    $newId = 'proj-' . time();
    $newProj = [
        'id' => $newId,
        'number' => sprintf('%02d', count($data['projects']) + 1),
        'status' => 'live',
        'status_label' => 'Baru',
        'type' => 'Tipe Proyek',
        'title' => 'Proyek Baru',
        'tagline' => 'Tagline proyek',
        'description' => 'Deskripsi proyek...',
        'highlights' => [],
        'features' => [],
        'tech' => [],
        'tags' => '',
        'icon' => '📄',
        'link_url' => '',
        'attachment' => ''
    ];
    array_unshift($data['projects'], $newProj);
    // Recalculate numbering
    foreach ($data['projects'] as $idx => &$p) {
        $p['number'] = sprintf('%02d', $idx + 1);
    }
    unset($p);
    $data['last_updated'] = date('Y-m-d H:i:s');
    saveData($data) ? jsonResponse(true, 'Proyek baru ditambahkan!') : jsonResponse(false, 'Gagal menambahkan proyek.');
}

// ============================================================
// ACTION: Upload Project File
// ============================================================
elseif ($action === 'upload_project_file') {
    $id = trim($_POST['project_id'] ?? '');
    if (!$id) jsonResponse(false, 'Project ID tidak valid.');

    if (empty($_FILES['project_file']['name'])) jsonResponse(false, 'Tidak ada file.');
    $file = $_FILES['project_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) jsonResponse(false, 'Error saat upload.');
    if ($file['size'] > 50 * 1024 * 1024) jsonResponse(false, 'Maksimal ukuran 50MB.'); 

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['zip', 'pdf', 'xls', 'xlsx'];
    if (!in_array($ext, $allowed)) jsonResponse(false, 'Hanya menerima ZIP, PDF, XLS, XLSX.');

    $filesDir = __DIR__ . '/../assets/files/projects/';
    if (!is_dir($filesDir)) mkdir($filesDir, 0755, true);

    $filename = $id . '_' . time() . '.' . $ext;
    $dest = $filesDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) jsonResponse(false, 'Gagal memindahkan file upload.');

    $isSite = false;
    $finalUrl = '';

    if ($ext === 'zip') {
        $zip = new ZipArchive;
        if ($zip->open($dest) === TRUE) {
            $siteDirName = $id . '_site_' . time();
            $extractPath = $filesDir . $siteDirName;
            mkdir($extractPath, 0755, true);
            $zip->extractTo($extractPath);
            $zip->close();
            @unlink($dest); // remove original zip

            // Find index.php or index.html
            $candidates = [];
            $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($extractPath));
            foreach ($iter as $fileInfo) {
                if ($fileInfo->isFile()) {
                    $name = strtolower($fileInfo->getFilename());
                    if ($name === 'index.php' || $name === 'index.html') {
                        $candidates[] = $fileInfo->getPathname();
                    }
                }
            }

            if (!empty($candidates)) {
                usort($candidates, function($a, $b) {
                    $depthA = substr_count(str_replace('\\', '/', $a), '/');
                    $depthB = substr_count(str_replace('\\', '/', $b), '/');
                    if ($depthA === $depthB) {
                        $isPhpA = str_ends_with(strtolower($a), '.php');
                        $isPhpB = str_ends_with(strtolower($b), '.php');
                        if ($isPhpA && !$isPhpB) return -1;
                        if (!$isPhpA && $isPhpB) return 1;
                        return 0;
                    }
                    return $depthA - $depthB;
                });
                
                $bestMatch = $candidates[0];
                $relativePath = str_replace(str_replace('\\', '/', realpath(__DIR__ . '/../')), '', str_replace('\\', '/', $bestMatch));
                
                $isSite = true;
                $finalUrl = ltrim($relativePath, '/');
                $msg = 'Website berhasil di-extract!';
            } else {
                jsonResponse(false, 'Gagal: Tidak ada file index.php atau index.html di dalam ZIP.');
            }
        } else {
            jsonResponse(false, 'Gagal membaca file ZIP.');
        }
    } else {
        $finalUrl = 'assets/files/projects/' . $filename;
        $msg = 'File berhasil diupload!';
    }

    // Save to JSON
    foreach ($data['projects'] as &$proj) {
        if ($proj['id'] === $id) {
            if ($isSite) {
                $proj['link_url'] = $finalUrl;
                $proj['attachment'] = ''; // Clear attachment
            } else {
                if (!empty($proj['attachment'])) {
                    $old = __DIR__ . '/../' . $proj['attachment'];
                    if (file_exists($old)) @unlink($old);
                }
                $proj['attachment'] = $finalUrl;
            }
            break;
        }
    }
    unset($proj);
    $data['last_updated'] = date('Y-m-d H:i:s');
    saveData($data) ? jsonResponse(true, $msg, ['url' => $finalUrl, 'type' => $isSite ? 'site' : 'file'])
                    : jsonResponse(false, 'Upload berhasil tapi gagal update database.');
}

// ============================================================
// ACTION: Update Skills
// ============================================================
elseif ($action === 'update_skills') {
    $raw = $_POST['skills'] ?? '';
    $skills = json_decode($raw, true);
    if (!is_array($skills)) jsonResponse(false, 'Data skills tidak valid.');
    $data['skills']       = $skills;
    $data['last_updated'] = date('Y-m-d H:i:s');
    saveData($data) ? jsonResponse(true, 'Skills berhasil disimpan!') : jsonResponse(false, 'Gagal menyimpan.');
}

else {
    jsonResponse(false, 'Action tidak dikenal: ' . htmlspecialchars($action));
}

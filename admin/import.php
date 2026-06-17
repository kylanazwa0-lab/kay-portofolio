<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data.php';

// Cek login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['project_zip'])) {
    $zipFile = $_FILES['project_zip'];
    
    if ($zipFile['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($zipFile['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) === 'zip') {
            
            $zip = new ZipArchive();
            if ($zip->open($zipFile['tmp_name']) === TRUE) {
                // Buat folder temporary
                $tmpDir = __DIR__ . '/tmp_' . time();
                mkdir($tmpDir);
                
                $zip->extractTo($tmpDir);
                $zip->close();
                
                // Cek project.json
                $jsonPath = $tmpDir . '/project.json';
                if (file_exists($jsonPath)) {
                    $projectData = json_decode(file_get_contents($jsonPath), true);
                    
                    if (is_array($projectData) && isset($projectData['id'])) {
                        // Pindahkan gambar ke assets
                        $assetsDir = dirname(__DIR__) . '/assets/images/projects';
                        if (!is_dir($assetsDir)) {
                            mkdir($assetsDir, 0755, true);
                        }
                        
                        $files = scandir($tmpDir);
                        foreach($files as $file) {
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                                // copy ke assets/images/projects
                                $dest = $assetsDir . '/' . $projectData['id'] . '_' . $file;
                                copy($tmpDir . '/' . $file, $dest);
                                
                                // Jika di project.json image/icon menunjuk ke file ini, update pathnya
                                if (isset($projectData['image']) && $projectData['image'] == $file) {
                                    $projectData['image'] = 'assets/images/projects/' . $projectData['id'] . '_' . $file;
                                }
                                if (isset($projectData['icon']) && $projectData['icon'] == $file) {
                                    $projectData['icon'] = 'assets/images/projects/' . $projectData['id'] . '_' . $file;
                                }
                            }
                        }
                        
                        // Load existing data
                        $data = loadData();
                        
                        // Cek apakah ID sudah ada
                        $exists = false;
                        foreach ($data['projects'] as &$p) {
                            if ($p['id'] === $projectData['id']) {
                                $p = array_merge($p, $projectData); // Update jika ada
                                $exists = true;
                                break;
                            }
                        }
                        
                        if (!$exists) {
                            // Insert di urutan paling atas
                            array_unshift($data['projects'], $projectData);
                            
                            // Update numbering
                            foreach ($data['projects'] as $idx => &$p) {
                                $p['number'] = sprintf('%02d', $idx + 1);
                            }
                        }
                        
                        saveData($data);
                        $message = '<div style="color: green; padding: 10px; background: #e0ffe0; border-radius: 5px; margin-bottom: 20px;">Berhasil meng-import proyek: ' . htmlspecialchars($projectData['title']) . '</div>';
                    } else {
                        $message = '<div style="color: red;">File project.json tidak valid atau tidak memiliki "id".</div>';
                    }
                } else {
                    $message = '<div style="color: red;">File project.json tidak ditemukan di dalam ZIP.</div>';
                }
                
                // Cleanup tmp dir
                $files = scandir($tmpDir);
                foreach($files as $file) {
                    if($file != '.' && $file != '..') unlink($tmpDir . '/' . $file);
                }
                rmdir($tmpDir);
                
            } else {
                $message = '<div style="color: red;">Gagal membuka file ZIP.</div>';
            }
        } else {
            $message = '<div style="color: red;">Format file harus .zip</div>';
        }
    } else {
        $message = '<div style="color: red;">Terjadi error saat upload file.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Import ZIP - Portfolio Admin</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f4f4f9; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #333; }
        .form-group { margin-bottom: 20px; }
        input[type="file"] { display: block; margin-top: 10px; }
        button { background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #4338ca; }
        a { color: #4f46e5; text-decoration: none; display: inline-block; margin-top: 20px; }
        .hint { font-size: 14px; color: #666; margin-top: 10px; background: #f8f9fa; padding: 10px; border-left: 4px solid #4f46e5; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Import Project via ZIP</h2>
        <?= $message ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Pilih File ZIP (.zip)</label>
                <input type="file" name="project_zip" accept=".zip" required>
                <div class="hint">
                    ZIP harus berisi file <strong>project.json</strong>.<br>
                    Jika ada gambar (misal: <em>cover.jpg</em>), gambar tersebut akan otomatis dipindahkan ke folder <em>assets</em>.
                </div>
            </div>
            <button type="submit">Upload & Import</button>
        </form>
        <a href="index.php">&larr; Kembali ke Dashboard Admin</a>
    </div>
</body>
</html>

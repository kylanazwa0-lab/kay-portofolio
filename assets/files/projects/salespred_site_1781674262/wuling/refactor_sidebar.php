<?php

$dir = __DIR__ . '/application/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$navbarTemplate = "    <?php \$this->load->view('templates/navbar'); ?>\n";
$sidebarTemplate = "        <?php \$this->load->view('templates/sidebar'); ?>\n";

$changedFiles = [];

foreach ($files as $file) {
    if ($file->isDir()) continue;
    if ($file->getExtension() !== 'php') continue;
    
    $path = $file->getRealPath();
    
    // Skip the templates directory
    if (strpos($path, 'templates') !== false) continue;
    if (strpos($path, 'auth') !== false) continue; // Skip login page
    
    $content = file_get_contents($path);
    $originalContent = $content;
    
    // Replace navbar
    // It starts with <nav and ends with </nav>
    $content = preg_replace('/<nav\b[^>]*>.*?<\/nav>/s', trim($navbarTemplate), $content);
    
    // Replace sidebar
    // It starts with <aside and ends with </aside>
    $content = preg_replace('/<aside\b[^>]*>.*?<\/aside>/s', trim($sidebarTemplate), $content);
    
    if ($content !== $originalContent) {
        file_put_contents($path, $content);
        $changedFiles[] = $path;
    }
}

echo "Updated files:\n";
print_r($changedFiles);

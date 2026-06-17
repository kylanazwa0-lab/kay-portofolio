import os
import glob

directory = r'd:\laragon\www\wuling\application\views'
search_str = '<i class="fas fa-layer-group"></i> WULING<span>SYS</span>'
replace_str = '<img src="<?= base_url(\'assets/images/logo_transparent.png\'); ?>" alt="Wuling Logo" style="height: 32px; object-fit: contain;">'

for filepath in glob.glob(os.path.join(directory, '**/*.php'), recursive=True):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    if search_str in content:
        content = content.replace(search_str, replace_str)
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated {filepath}")

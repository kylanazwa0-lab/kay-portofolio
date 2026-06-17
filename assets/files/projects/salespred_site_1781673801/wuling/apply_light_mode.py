import os
import glob
import re

directory = r'd:\laragon\www\wuling\application\views'

head_script = "\n    <script>if(localStorage.getItem('theme') === 'light') document.documentElement.setAttribute('data-theme', 'light');</script>\n</head>"
body_script = "\n    <script src=\"<?= base_url('assets/js/theme.js'); ?>\"></script>\n</body>"
toggle_btn = '<button id="themeToggle" class="wl-btn wl-btn-sm" style="background: transparent; border: 1px solid var(--border); color: var(--text-primary); margin-right: 0.5rem;" title="Toggle Theme"><i class="fas fa-sun"></i></button>\n            <div class="wl-user-badge">'

for filepath in glob.glob(os.path.join(directory, '**/*.php'), recursive=True):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    modified = False
    
    if '</head>' in content and "localStorage.getItem('theme')" not in content:
        content = content.replace('</head>', head_script)
        modified = True
        
    if '</body>' in content and "assets/js/theme.js" not in content:
        content = content.replace('</body>', body_script)
        modified = True
        
    if '<div class="wl-user-badge">' in content and 'id="themeToggle"' not in content:
        content = content.replace('<div class="wl-user-badge">', toggle_btn)
        modified = True
        
    if modified:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated {filepath}")

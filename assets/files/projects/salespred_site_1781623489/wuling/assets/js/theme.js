// theme.js — Theme toggle + Sidebar collapse (robust version)

// ── Apply theme ASAP (before DOMContentLoaded) ──────────────────
(function () {
    var saved = localStorage.getItem('theme');
    if (saved === 'light') {
        document.documentElement.setAttribute('data-theme', 'light');
    }
})();

document.addEventListener('DOMContentLoaded', function () {

    // ── Theme Toggle ──────────────────────────────────────────────
    var themeToggle = document.getElementById('themeToggle');

    function getTheme() {
        return document.documentElement.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
    }

    function applyThemeIcon() {
        if (!themeToggle) return;
        themeToggle.innerHTML = getTheme() === 'light'
            ? '<i class="fas fa-moon"></i>'
            : '<i class="fas fa-sun"></i>';
    }

    applyThemeIcon();

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            if (getTheme() === 'dark') {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'dark');
            }
            applyThemeIcon();
        });
    }

    // ── Sidebar Collapse ──────────────────────────────────────────
    var sidebar      = document.querySelector('.wl-sidebar');
    var toggleBtn    = document.getElementById('sidebarToggle');
    var STORE_KEY    = 'sidebarCollapsed';

    if (sidebar && toggleBtn) {
        // Disable transitions momentarily during initial restore to prevent flash
        sidebar.style.transition = 'none';
        if (localStorage.getItem(STORE_KEY) === '1') {
            sidebar.classList.add('wl-sidebar--collapsed');
        }
        // Re-enable transition after a brief paint
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                sidebar.style.transition = '';
            });
        });

        // Single click handler — no duplicates possible
        toggleBtn.addEventListener('click', function () {
            var isNowCollapsed = sidebar.classList.toggle('wl-sidebar--collapsed');
            localStorage.setItem(STORE_KEY, isNowCollapsed ? '1' : '0');
        });
    }

    // ── Custom Select (FlyonUI style) ─────────────────────────────
    function initCustomSelects() {
        var selects = document.querySelectorAll('.wl-custom-select-init');
        selects.forEach(function(select) {
            if (select.dataset.customSelectInitialized) return;
            select.dataset.customSelectInitialized = "true";
            select.style.display = 'none';

            var wrapper = document.createElement('div');
            wrapper.className = 'wl-custom-select-wrapper';
            select.parentNode.insertBefore(wrapper, select);
            wrapper.appendChild(select);

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'wl-custom-select-btn';
            btn.setAttribute('aria-expanded', 'false');

            var btnText = document.createElement('span');
            var icon = document.createElement('i');
            icon.className = 'fas fa-chevron-down icon';

            var selectedOpt = select.options[select.selectedIndex];
            btnText.textContent = selectedOpt ? selectedOpt.text : 'Pilih...';

            btn.appendChild(btnText);
            btn.appendChild(icon);
            wrapper.appendChild(btn);

            var menu = document.createElement('div');
            menu.className = 'wl-custom-select-menu';

            Array.from(select.options).forEach(function(opt, index) {
                var item = document.createElement('div');
                item.className = 'wl-custom-select-option';
                if (opt.selected) item.classList.add('is-selected');
                
                item.textContent = opt.text;
                item.addEventListener('click', function() {
                    select.selectedIndex = index;
                    btnText.textContent = opt.text;
                    
                    var currentSelected = menu.querySelector('.is-selected');
                    if (currentSelected) currentSelected.classList.remove('is-selected');
                    item.classList.add('is-selected');
                    
                    btn.setAttribute('aria-expanded', 'false');
                    wrapper.classList.remove('is-open');

                    // Trigger change event for form submission if needed
                    var event = new Event('change', { bubbles: true });
                    select.dispatchEvent(event);
                });
                menu.appendChild(item);
            });

            wrapper.appendChild(menu);

            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                var isOpen = wrapper.classList.contains('is-open');
                
                // close all other open selects
                document.querySelectorAll('.wl-custom-select-wrapper.is-open').forEach(function(w) {
                    w.classList.remove('is-open');
                    w.querySelector('.wl-custom-select-btn').setAttribute('aria-expanded', 'false');
                });

                if (!isOpen) {
                    wrapper.classList.add('is-open');
                    btn.setAttribute('aria-expanded', 'true');
                }
            });
        });

        // Close select when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.wl-custom-select-wrapper')) {
                document.querySelectorAll('.wl-custom-select-wrapper.is-open').forEach(function(w) {
                    w.classList.remove('is-open');
                    w.querySelector('.wl-custom-select-btn').setAttribute('aria-expanded', 'false');
                });
            }
        });
    }

    initCustomSelects();
});

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> - Wuling System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">
    <style>
        .bulk-actions {
            display: none;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1rem;
            margin-bottom: 1rem;
            animation: wl-slideIn 0.25s ease;
        }
        .wl-checkbox {
            accent-color: var(--accent);
            width: 16px; height: 16px;
            cursor: pointer;
        }
    </style>

    <script>if(localStorage.getItem('theme') === 'light') document.documentElement.setAttribute('data-theme', 'light');</script>
</head>
<body>

    <!-- Navbar -->
    <?php $this->load->view('templates/navbar'); ?>

    <!-- Layout -->
    <div class="wl-layout">
        
        <!-- Sidebar -->
        <?php $this->load->view('templates/sidebar'); ?>

        <!-- Main Content -->
        <main class="wl-main">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="wl-alert wl-alert-success wl-fade-up">
                    <i class="fas fa-check-circle wl-alert-icon"></i>
                    <div class="wl-alert-body">
                        <div class="wl-alert-msg"><?= $this->session->flashdata('success'); ?></div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($this->session->flashdata('error')): ?>
                <div class="wl-alert wl-alert-danger wl-fade-up">
                    <i class="fas fa-exclamation-triangle wl-alert-icon"></i>
                    <div class="wl-alert-body">
                        <div class="wl-alert-msg"><?= $this->session->flashdata('error'); ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="wl-page-header wl-fade-up-2 wl-flex wl-items-center wl-justify-between">
                <div>
                    <h1 class="wl-page-title"><?= $title; ?></h1>
                    <p class="wl-page-subtitle">Kelola dan atur hak akses pengguna sistem.</p>
                </div>
                <div class="wl-flex wl-gap-2">
                    <a href="<?= site_url('users/add'); ?>" class="wl-btn wl-btn-primary">
                        <i class="fas fa-plus"></i> Tambah Pengguna
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="wl-stat-grid wl-fade-up-3">
                <div class="wl-stat-card">
                    <div class="wl-stat-icon blue"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="wl-stat-value"><?= isset($total_users) ? $total_users : count($users); ?></div>
                        <div class="wl-stat-label">Total Users</div>
                    </div>
                </div>
                <div class="wl-stat-card">
                    <div class="wl-stat-icon green"><i class="fas fa-user-check"></i></div>
                    <div>
                        <div class="wl-stat-value"><?= count(array_filter($users, function($u) { return $u->status == 'active'; })); ?></div>
                        <div class="wl-stat-label">Active Users</div>
                    </div>
                </div>
                <div class="wl-stat-card">
                    <div class="wl-stat-icon red"><i class="fas fa-user-times"></i></div>
                    <div>
                        <div class="wl-stat-value"><?= count(array_filter($users, function($u) { return $u->status == 'inactive'; })); ?></div>
                        <div class="wl-stat-label">Inactive Users</div>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="wl-search-wrap wl-fade-up-3">
                <form method="GET" action="<?= site_url('users'); ?>" class="wl-flex wl-gap-2" style="width: 100%; align-items: center; flex-wrap: wrap;">
                    <input type="text" class="wl-search-input" name="search" placeholder="Cari username, nama, role..." value="<?= isset($search_term) ? $search_term : ''; ?>" style="flex: 1; min-width: 200px;">
                    
                    <div style="display: flex; align-items: center; gap: 0.5rem; flex: 0 1 auto;">
                        <select name="role" style="background: var(--bg-surface); color: var(--text-primary); border: 1px solid var(--border); padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); outline: none; cursor: pointer; font-family: inherit; font-size: 0.9rem;">
                            <option value="">Semua Role</option>
                            <?php foreach ($roles as $key => $name): ?>
                                <option value="<?= $key ?>" <?= (isset($role_filter) && $role_filter == $key) ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.5rem; flex: 0 1 auto;">
                        <select name="status" style="background: var(--bg-surface); color: var(--text-primary); border: 1px solid var(--border); padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); outline: none; cursor: pointer; font-family: inherit; font-size: 0.9rem;">
                            <option value="">Semua Status</option>
                            <option value="active" <?= (isset($status_filter) && $status_filter == 'active') ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= (isset($status_filter) && $status_filter == 'inactive') ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>

                    <button type="submit" class="wl-btn wl-btn-primary"><i class="fas fa-search"></i> Filter</button>
                    
                    <?php if ((isset($search_term) && !empty($search_term)) || (isset($role_filter) && !empty($role_filter)) || (isset($status_filter) && !empty($status_filter))): ?>
                        <a href="<?= site_url('users'); ?>" class="wl-btn wl-btn-secondary">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Data Table -->
            <div class="wl-card wl-fade-up-4">
                <div class="wl-card-header">
                    <h2 class="wl-card-title">Daftar Pengguna</h2>
                    <button type="button" class="wl-btn wl-btn-secondary wl-btn-sm" onclick="toggleBulkActions()">
                        <i class="fas fa-tasks"></i> Aksi Massal
                    </button>
                </div>

                <!-- Bulk Actions Panel -->
                <div class="bulk-actions" id="bulkActions">
                    <form method="POST" action="<?= site_url('users/bulk_action'); ?>" onsubmit="return confirmBulkAction()">
                        <div class="wl-flex wl-items-center wl-gap-3 wl-justify-between">
                            <div class="wl-flex wl-gap-2 wl-items-center">
                                <select name="bulk_action" class="wl-select" required style="width: 200px;">
                                    <option value="">Pilih Aksi</option>
                                    <option value="activate">Aktifkan</option>
                                    <option value="deactivate">Nonaktifkan</option>
                                    <option value="delete">Hapus</option>
                                </select>
                                <button type="submit" class="wl-btn wl-btn-primary wl-btn-sm"><i class="fas fa-play"></i> Eksekusi</button>
                                <button type="button" class="wl-btn wl-btn-ghost wl-btn-sm" onclick="toggleBulkActions()">Batal</button>
                            </div>
                            <span class="wl-text-sm wl-text-muted"><span id="selectedCount">0</span> pengguna dipilih</span>
                        </div>
                    </form>
                </div>

                <div class="wl-table-wrapper">
                    <?php if (empty($users)): ?>
                        <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                            <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <p>Tidak ada data pengguna yang ditemukan.</p>
                        </div>
                    <?php else: ?>
                    <table class="wl-table">
                        <thead>
                            <tr>
                                <th class="bulk-col" style="display: none; width: 40px;"><input type="checkbox" class="wl-checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="bulk-col" style="display: none;"><input type="checkbox" name="selected_users[]" value="<?= $user->id; ?>" class="wl-checkbox user-checkbox" onchange="updateSelectedCount()"></td>
                                    <td>
                                        <strong><?= $user->username; ?></strong>
                                        <?php if ($user->id == $this->session->userdata('user_id')): ?>
                                            <span class="wl-badge wl-badge-blue" style="margin-left: 5px;">You</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><?= $user->full_name; ?></div>
                                        <div class="wl-text-xs wl-text-muted"><?= $user->email; ?></div>
                                    </td>
                                    <td>
                                        <?php 
                                            $role_map = [
                                                'administration_head' => ['Admin Head', 'wl-badge-blue'],
                                                'admin_bpkb' => ['BPKB', 'wl-badge-success'],
                                                'admin_sales' => ['Sales', 'wl-badge-info'],
                                                'operation_manager' => ['Ops Mgr', 'wl-badge-warning'],
                                                'c_level' => ['C-Level', 'wl-badge-danger']
                                            ];
                                            $r = isset($role_map[$user->role]) ? $role_map[$user->role] : ['Unknown', 'wl-badge-muted'];
                                        ?>
                                        <span class="wl-badge <?= $r[1]; ?>"><?= $r[0]; ?></span>
                                    </td>
                                    <td>
                                        <span class="wl-badge <?= $user->status == 'active' ? 'wl-badge-success' : 'wl-badge-danger'; ?>">
                                            <?= $user->status == 'active' ? 'Aktif' : 'Nonaktif'; ?>
                                        </span>
                                    </td>
                                    <td class="muted">
                                        <?= date('d M Y', strtotime($user->created_at)); ?>
                                    </td>
                                    <td>
                                        <div class="wl-dropdown" onclick="toggleDropdown(this, event)">
                                            <button type="button" class="wl-dropdown-toggle wl-btn wl-btn-secondary wl-btn-sm" aria-haspopup="menu" aria-expanded="false">
                                                Aksi
                                                <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 0.25rem;"></i>
                                            </button>
                                            <ul class="wl-dropdown-menu" role="menu">
                                                <li>
                                                    <a href="<?= site_url('users/edit/'.$user->id); ?>" class="wl-dropdown-item">
                                                        <span><i class="fas fa-edit" style="width: 16px; margin-right: 6px;"></i> Edit Pengguna</span>
                                                    </a>
                                                </li>
                                                <?php if ($user->id != $this->session->userdata('user_id')): ?>
                                                <li>
                                                    <a href="<?= site_url('users/delete/'.$user->id); ?>" class="wl-dropdown-item text-danger" onclick="return confirm('Hapus pengguna ini?')">
                                                        <span><i class="fas fa-trash" style="width: 16px; margin-right: 6px;"></i> Hapus</span>
                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if (isset($pagination) && $pagination): ?>
                    <div class="wl-pagination">
                        <?= $pagination; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/js/theme.js') ?>"></script>
    <script>
        // Custom Dropdown Logic
        function toggleDropdown(el, event) {
            event.stopPropagation();
            document.querySelectorAll('.wl-dropdown').forEach(dropdown => {
                if (dropdown !== el) dropdown.classList.remove('is-open', 'active');
            });
            el.classList.toggle('active');
            el.classList.toggle('is-open');
        }

        function selectOption(btn, inputName, value, text) {
            const dropdown = btn.closest('.wl-dropdown');
            dropdown.querySelector('.selected-text').innerText = text;
            dropdown.querySelectorAll('.wl-dropdown-item').forEach(i => i.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('input_' + inputName).value = value;
            dropdown.classList.remove('active');
            dropdown.classList.remove('is-open');
        }

        document.addEventListener('click', () => {
            document.querySelectorAll('.wl-dropdown').forEach(dropdown => {
                dropdown.classList.remove('active', 'is-open');
            });
        });

        // Bulk actions
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const countSpan = document.getElementById('selectedCount');
            if (countSpan) countSpan.textContent = checkboxes.length;
            
            const total = document.querySelectorAll('.user-checkbox').length;
            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                selectAll.checked = (checkboxes.length === total && total > 0);
                selectAll.indeterminate = (checkboxes.length > 0 && checkboxes.length < total);
            }
        }

        function toggleBulkActions() {
            const panel = document.getElementById('bulkActions');
            const bulkCols = document.querySelectorAll('.bulk-col');
            
            if (panel.style.display === 'none' || panel.style.display === '') {
                panel.style.display = 'block';
                bulkCols.forEach(col => col.style.display = 'table-cell');
            } else {
                panel.style.display = 'none';
                bulkCols.forEach(col => col.style.display = 'none');
                
                // uncheck all
                const selectAll = document.getElementById('selectAll');
                if (selectAll) selectAll.checked = false;
                document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
                updateSelectedCount();
            }
        }

        function confirmBulkAction() {
            const actionSelect = document.querySelector('select[name="bulk_action"]');
            const action = actionSelect ? actionSelect.value : '';
            const count = document.querySelectorAll('.user-checkbox:checked').length;
            
            if (!action) {
                alert('Pilih aksi terlebih dahulu');
                return false;
            }
            if (count === 0) {
                alert('Pilih minimal 1 pengguna');
                return false;
            }
            
            let actionText = '';
            if (action === 'activate') actionText = 'mengaktifkan';
            if (action === 'deactivate') actionText = 'menonaktifkan';
            if (action === 'delete') actionText = 'menghapus';
            
            return confirm(`Apakah Anda yakin ingin ${actionText} ${count} pengguna terpilih?`);
        }

        // Add hidden inputs on submit for bulk action form if necessary
        const bulkForm = document.querySelector('form[action*="bulk_action"]');
        if (bulkForm) {
            bulkForm.addEventListener('submit', function() {
                const checkboxes = document.querySelectorAll('.user-checkbox:checked');
                checkboxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_users[]';
                    input.value = cb.value;
                    this.appendChild(input);
                });
            });
        }
        
        // Auto-dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.wl-alert').forEach(a => {
                a.style.opacity = '0';
                setTimeout(() => a.style.display = 'none', 300);
            });
        }, 5000);
    </script>
</body>
</html>
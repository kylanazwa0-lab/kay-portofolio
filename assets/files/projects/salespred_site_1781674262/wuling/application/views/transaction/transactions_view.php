<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Management - Wuling System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/wuling.css'); ?>">

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
            <?php if ($this->session->flashdata('message')): ?>
                <div class="wl-alert wl-alert-success wl-fade-up">
                    <i class="fas fa-check-circle wl-alert-icon"></i>
                    <div class="wl-alert-body">
                        <div class="wl-alert-msg"><?= $this->session->flashdata('message'); ?></div>
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
                    <h1 class="wl-page-title">Transaction Management</h1>
                    <p class="wl-page-subtitle">Manage your sales transactions efficiently</p>
                </div>
            </div>

            <!-- Toolbar -->
            <div class="wl-card wl-fade-up-3 wl-mb-6" style="padding: 1rem;">
                <div class="wl-flex wl-justify-between wl-items-center">

                    <!-- Normal mode -->
                    <div id="normalModeControls" class="wl-flex wl-gap-2 wl-items-center">
                        <a href="<?= site_url('transaction/Transactions_form'); ?>" class="wl-btn wl-btn-primary">
                            <i class="fas fa-plus"></i> Tambah Transaksi
                        </a>
                        <button type="button" id="bulkSelectBtn" class="wl-btn" onclick="toggleBulkMode()"
                            style="background: transparent; border: 1px solid var(--border); color: var(--text-secondary); font-size: 0.875rem;">
                            <i class="fas fa-check-square"></i> Pilih
                        </button>
                    </div>

                    <!-- Bulk mode controls (hidden by default) -->
                    <div id="bulkActionControls" style="display: none;" class="wl-flex wl-items-center wl-gap-2">
                        <span id="selectedCount" style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-right: 0.25rem;">0 dipilih</span>
                        <button type="button" class="wl-btn wl-btn-danger wl-btn-sm" onclick="bulkDelete()" id="bulkDeleteBtn" disabled style="opacity:0.4;">
                            <i class="fas fa-trash"></i> Hapus Terpilih
                        </button>
                        <button type="button" class="wl-btn wl-btn-sm" onclick="toggleBulkMode()"
                            style="background: transparent; border: 1px solid var(--border); color: var(--text-muted);">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>

                </div>
            </div>

            <!-- Table Card -->
            <div class="wl-card wl-fade-up-4">
                <div style="overflow-x: auto;">
                    <table class="wl-table">
                        <thead>
                            <tr>
                                <th class="col-checkbox" style="width: 44px; text-align: center; display: none;">
                                    <input type="checkbox" id="selectAll" onchange="toggleAll(this)" style="accent-color: var(--accent);">
                                </th>
                                <th style="text-align:center;">ID</th>
                                <th>SL Date</th>
                                <th>Customer</th>
                                <th>Model</th>
                                <th style="text-align:center;">Price Net</th>
                                <th style="text-align:center;">Payment Type</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr style="vertical-align: middle;">
                                    <td class="col-checkbox" style="text-align: center; display: none;">
                                        <input type="checkbox" class="transaction-checkbox" value="<?= $transaction['id']; ?>" onchange="updateBulkActions()" style="accent-color: var(--accent);">
                                    </td>
                                    <td style="text-align:center; font-family: var(--font-mono); font-weight:700; font-size:0.8rem; color:var(--text-muted);"><?= str_pad($transaction['id'], 2, '0', STR_PAD_LEFT) ?></td>
                                    <td style="white-space:nowrap; font-size:0.875rem;"><?= date('d M Y', strtotime($transaction['sl_date'])); ?></td>
                                    <td style="font-weight:600; font-size:0.875rem;"><?= htmlspecialchars($transaction['customer']); ?></td>
                                    <td><i class="fas fa-car" style="color: var(--accent); margin-right:0.4rem;"></i><?= htmlspecialchars($transaction['model']); ?></td>
                                    <td style="text-align:center;">
                                        <span class="wl-badge wl-badge-success" style="font-family:var(--font-mono); font-weight:700;">Rp <?= number_format($transaction['price_net'], 0, ',', '.'); ?></span>
                                    </td>
                                    <td style="text-align:center;">
                                        <?php if(strtolower($transaction['tunai_kredit']) == 'tunai'): ?>
                                            <span class="wl-badge wl-badge-success"><i class="fas fa-money-bill wl-mr-1"></i> <?= $transaction['tunai_kredit']; ?></span>
                                        <?php else: ?>
                                            <span class="wl-badge wl-badge-warning"><i class="fas fa-credit-card wl-mr-1"></i> <?= $transaction['tunai_kredit']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <div class="wl-dropdown">
                                            <button type="button" class="wl-dropdown-trigger" onclick="toggleActionDropdown(this.parentElement, event)" aria-haspopup="true" aria-expanded="false" aria-label="Menu aksi">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="wl-dropdown-menu">
                                                <li><a class="wl-dropdown-item" href="<?= site_url('transaction/edit/' . $transaction['id']); ?>"><i class="fas fa-edit"></i> Edit Transaksi</a></li>
                                                <li style="border-top: 1px solid var(--border); margin-top: 0.25rem; padding-top: 0.25rem;">
                                                    <a class="wl-dropdown-item wl-dropdown-item-danger" href="<?= site_url('transaction/delete/' . $transaction['id']); ?>" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Dropdown toggle with fixed positioning to avoid overflow clipping
        function positionDropdown(el) {
            const trigger = el.querySelector('.wl-dropdown-trigger');
            const menu    = el.querySelector('.wl-dropdown-menu');
            if (!trigger || !menu) return;

            const rect = trigger.getBoundingClientRect();
            const menuW = 170;
            const leftPos = rect.right - menuW;

            menu.style.position = 'fixed';
            menu.style.top  = (rect.bottom + 6) + 'px';
            menu.style.left = Math.max(8, leftPos) + 'px';
            menu.style.right = 'auto';
            menu.style.zIndex = '9999';
        }

        function toggleActionDropdown(el, event) {
            event.stopPropagation();
            const isOpen = el.classList.contains('is-open');
            document.querySelectorAll('.wl-dropdown.is-open').forEach(d => {
                if (d !== el) {
                    d.classList.remove('is-open');
                    const btn = d.querySelector('.wl-dropdown-trigger');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            });
            if (!isOpen) {
                el.classList.add('is-open');
                positionDropdown(el);
                const trigger = el.querySelector('.wl-dropdown-trigger');
                if (trigger) trigger.setAttribute('aria-expanded', 'true');
            } else {
                el.classList.remove('is-open');
                const trigger = el.querySelector('.wl-dropdown-trigger');
                if (trigger) trigger.setAttribute('aria-expanded', 'false');
            }
        }

        document.addEventListener('click', function(e) {
            document.querySelectorAll('.wl-dropdown.is-open').forEach(d => {
                if (!d.contains(e.target)) {
                    d.classList.remove('is-open');
                    const btn = d.querySelector('.wl-dropdown-trigger');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                }
            });
        });

        window.addEventListener('scroll', () => {
            document.querySelectorAll('.wl-dropdown.is-open').forEach(d => positionDropdown(d));
        }, true);
        window.addEventListener('resize', () => {
            document.querySelectorAll('.wl-dropdown.is-open').forEach(d => d.classList.remove('is-open'));
        });
        // ── Bulk Select Mode ─────────────────────────────────────
        let bulkModeActive = false;

        function toggleBulkMode() {
            bulkModeActive = !bulkModeActive;

            // Tampilkan / sembunyikan kolom checkbox
            document.querySelectorAll('.col-checkbox').forEach(el => {
                el.style.display = bulkModeActive ? '' : 'none';
            });

            const normalControls = document.getElementById('normalModeControls');
            const bulkControls   = document.getElementById('bulkActionControls');

            if (bulkModeActive) {
                normalControls.style.display = 'none';
                bulkControls.style.display = 'flex';
                updateBulkActions();
            } else {
                // Reset semua checkbox
                document.querySelectorAll('.transaction-checkbox').forEach(cb => cb.checked = false);
                document.getElementById('selectAll').checked = false;
                normalControls.style.display = 'flex';
                bulkControls.style.display = 'none';
            }
        }

        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('.transaction-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
            updateBulkActions();
        }
        
        function updateBulkActions() {
            if (!bulkModeActive) return;
            const checkboxes   = document.querySelectorAll('.transaction-checkbox:checked');
            const selectedCount = document.getElementById('selectedCount');
            const selectAll    = document.getElementById('selectAll');
            const deleteBtn    = document.getElementById('bulkDeleteBtn');

            selectedCount.textContent = checkboxes.length + ' dipilih';

            // Enable/disable Hapus button based on selection
            if (deleteBtn) {
                deleteBtn.disabled    = checkboxes.length === 0;
                deleteBtn.style.opacity = checkboxes.length === 0 ? '0.4' : '1';
            }

            // Update select-all checkbox state
            const allCheckboxes = document.querySelectorAll('.transaction-checkbox');
            selectAll.checked       = allCheckboxes.length > 0 && checkboxes.length === allCheckboxes.length;
            selectAll.indeterminate = checkboxes.length > 0 && checkboxes.length < allCheckboxes.length;
        }
        
        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.transaction-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Pilih setidaknya satu transaksi untuk dihapus.');
                return;
            }
            
            if (confirm(`Apakah Anda yakin ingin menghapus ${checkboxes.length} transaksi yang dipilih? Tindakan ini tidak dapat dibatalkan.`)) {
                const ids = Array.from(checkboxes).map(cb => cb.value);
                
                // Create hidden form to submit all IDs at once
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.location.href; // Submit to current page
                form.style.display = 'none';
                
                // Add hidden input for bulk delete action
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'bulk_delete';
                actionInput.value = '1';
                form.appendChild(actionInput);
                
                // Add each selected ID as hidden input
                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>
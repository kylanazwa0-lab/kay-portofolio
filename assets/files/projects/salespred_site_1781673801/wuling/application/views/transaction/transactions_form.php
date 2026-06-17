<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> - Wuling System</title>
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
            <?php if (validation_errors()): ?>
                <div class="wl-alert wl-alert-danger wl-fade-up">
                    <i class="fas fa-exclamation-triangle wl-alert-icon"></i>
                    <div class="wl-alert-body">
                        <div class="wl-alert-msg"><?= validation_errors(); ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="wl-page-header wl-fade-up-2 wl-flex wl-items-center wl-justify-between">
                <div>
                    <h1 class="wl-page-title">Add Transaction</h1>
                    <p class="wl-page-subtitle">Masukkan data transaksi penjualan baru ke sistem.</p>
                </div>
                <div>
                    <a href="<?= site_url('transaction'); ?>" class="wl-btn wl-btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Form Card -->
            <div class="wl-card wl-fade-up-3">
                <div class="wl-card-header">
                    <h2 class="wl-card-title"><i class="fas fa-plus"></i> Form Transaksi Baru</h2>
                </div>

                <form method="post" action="<?= site_url('transaction/save'); ?>" id="transactionForm">
                    <div class="wl-grid-2">
                        
                        <div class="wl-form-group">
                            <label class="wl-label">SL Date <span class="wl-text-danger">*</span></label>
                            <input type="date" class="wl-input" name="sl_date" value="<?= set_value('sl_date'); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Customer <span class="wl-text-danger">*</span></label>
                            <input type="text" class="wl-input" name="customer" value="<?= set_value('customer'); ?>" placeholder="Enter customer name">
                        </div>
                        
                        <div class="wl-form-group" style="grid-column: 1 / -1;">
                            <label class="wl-label">Alamat</label>
                            <textarea class="wl-input" name="alamat" placeholder="Enter address"><?= set_value('alamat'); ?></textarea>
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Customer Phone <span class="wl-text-danger">*</span></label>
                            <input type="text" class="wl-input" name="cust_phone" value="<?= set_value('cust_phone'); ?>" placeholder="Enter phone number">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Sales Name <span class="wl-text-danger">*</span></label>
                            <select class="wl-select" name="sales_name">
                                <option value="">-- Select Sales --</option>
                                <?php foreach ($sales_users as $sales): ?>
                                    <option value="<?= $sales['full_name']; ?>" <?= set_select('sales_name', $sales['full_name']); ?>>
                                        <?= $sales['full_name']; ?> (<?= $sales['username']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">SPV <span class="wl-text-danger">*</span></label>
                            <select class="wl-select" name="spv">
                                <option value="">-- Select SPV --</option>
                                <?php foreach ($spv_users as $spv): ?>
                                    <option value="<?= $spv['full_name']; ?>" <?= set_select('spv', $spv['full_name']); ?>>
                                        <?= $spv['full_name']; ?> (<?= $spv['username']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Leasing</label>
                            <input type="text" class="wl-input" name="leasing" value="<?= set_value('leasing'); ?>" placeholder="Enter leasing company">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Insurance</label>
                            <input type="text" class="wl-input" name="insurance" value="<?= set_value('insurance'); ?>" placeholder="Enter insurance company">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Invoice No</label>
                            <input type="text" class="wl-input" name="inv_no" value="<?= set_value('inv_no'); ?>" placeholder="Enter invoice number">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Code</label>
                            <input type="text" class="wl-input" name="code" value="<?= set_value('code'); ?>" placeholder="Enter code">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Type</label>
                            <input type="text" class="wl-input" name="type" value="<?= set_value('type'); ?>" placeholder="Enter type">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Chassis</label>
                            <input type="text" class="wl-input" name="chassis" value="<?= set_value('chassis'); ?>" placeholder="Enter chassis number">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Price List</label>
                            <input type="number" class="wl-input" name="price_list" value="<?= set_value('price_list'); ?>" placeholder="0">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Discount</label>
                            <input type="number" class="wl-input" name="discount" value="<?= set_value('discount'); ?>" placeholder="0">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Price Net</label>
                            <input type="number" class="wl-input" name="price_net" value="<?= set_value('price_net'); ?>" placeholder="0">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">DP Amount</label>
                            <input type="number" class="wl-input" name="dp_amt" value="<?= set_value('dp_amt'); ?>" placeholder="0">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Leasing Amount</label>
                            <input type="number" class="wl-input" name="leasing_amt" value="<?= set_value('leasing_amt'); ?>" placeholder="0">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Tenor</label>
                            <input type="number" class="wl-input" name="tenor" value="<?= set_value('tenor'); ?>" placeholder="Enter tenor in months">
                        </div>
                        
                        <div class="wl-form-group" style="grid-column: 1 / -1;">
                            <label class="wl-label">Description 2</label>
                            <textarea class="wl-input" name="description_2" placeholder="Enter additional description"><?= set_value('description_2'); ?></textarea>
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">KTP No</label>
                            <input type="text" class="wl-input" name="ktp_no" value="<?= set_value('ktp_no'); ?>" placeholder="Enter KTP number">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Tunai/Kredit <span class="wl-text-danger">*</span></label>
                            <select class="wl-select" name="tunai_kredit">
                                <option value="">-- Select Type --</option>
                                <option value="Tunai" <?= set_select('tunai_kredit', 'Tunai'); ?>>Tunai</option>
                                <option value="Kredit" <?= set_select('tunai_kredit', 'Kredit'); ?>>Kredit</option>
                            </select>
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Model</label>
                            <input type="text" class="wl-input" name="model" value="<?= set_value('model'); ?>" placeholder="Enter model">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">DO Status</label>
                            <div class="wl-flex wl-items-center wl-gap-2">
                                <input type="checkbox" name="do_status" value="1" <?= set_checkbox('do_status', '1'); ?> style="accent-color: var(--primary);">
                                <label>Yes</label>
                            </div>
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Date (Day)</label>
                            <input type="number" class="wl-input" name="hari" value="<?= set_value('hari'); ?>" placeholder="Day" min="1" max="31">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Bulan</label>
                            <select class="wl-select" name="bulan">
                                <option value="">-- Select Month --</option>
                                <?php
                                $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                                foreach ($months as $m) {
                                    echo '<option value="'.$m.'" '.set_select('bulan', $m).'>'.$m.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Tahun</label>
                            <input type="number" class="wl-input" name="tahun" value="<?= set_value('tahun'); ?>" placeholder="Year" min="2000" max="2030">
                        </div>
                    </div>
                    
                    <div class="wl-flex wl-gap-2 wl-justify-end wl-mt-4" style="border-top: 1px solid var(--border); padding-top: 1rem;">
                        <button type="reset" class="wl-btn wl-btn-ghost">Reset</button>
                        <button type="submit" class="wl-btn wl-btn-primary" id="submitBtn"><i class="fas fa-save"></i> Save Transaction</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    // Prevent double form submission with enhanced UX
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        var submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        
        // Re-enable after 5 seconds as fallback
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Transaction';
        }, 5000);
    });

    // Auto-populate date fields from sl_date
    document.querySelector('input[name="sl_date"]').addEventListener('change', function() {
        var dateValue = this.value;
        if (dateValue) {
            var date = new Date(dateValue);
            var day = date.getDate();
            var month = date.toLocaleString('default', { month: 'long' });
            var year = date.getFullYear();
            
            document.querySelector('input[name="hari"]').value = day;
            document.querySelector('select[name="bulan"]').value = month;
            document.querySelector('input[name="tahun"]').value = year;
        }
    });
    </script>

    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>
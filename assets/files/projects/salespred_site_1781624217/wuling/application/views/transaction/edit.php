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
            <?php if(isset($success_message)): ?>
                <div class="wl-alert wl-alert-success wl-fade-up">
                    <i class="fas fa-check-circle wl-alert-icon"></i>
                    <div class="wl-alert-body">
                        <div class="wl-alert-msg"><?= $success_message; ?></div>
                    </div>
                </div>
            <?php endif; ?>
            
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
                    <h1 class="wl-page-title">Edit Transaction</h1>
                    <p class="wl-page-subtitle">Perbarui data transaksi penjualan yang sudah ada.</p>
                </div>
                <div>
                    <a href="<?= site_url('transaction'); ?>" class="wl-btn wl-btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Form Card -->
            <div class="wl-card wl-fade-up-3">
                <div class="wl-card-header wl-flex wl-justify-between wl-items-center">
                    <h2 class="wl-card-title"><i class="fas fa-edit"></i> Form Edit Transaksi #<?= $transaction['id']; ?></h2>
                    <a href="<?= site_url('transaction/delete/' . $transaction['id']); ?>" class="wl-btn wl-btn-danger wl-btn-sm" onclick="return confirm('Are you sure you want to delete this transaction?')"><i class="fas fa-trash"></i> Delete</a>
                </div>

                <form method="post" action="<?= site_url('transaction/update/' . $transaction['id']); ?>" id="transactionForm">
                    <input type="hidden" name="id" value="<?= $transaction['id']; ?>">
                    <div class="wl-grid-2">
                        
                        <div class="wl-form-group">
                            <label class="wl-label">SL Date</label>
                            <input type="date" class="wl-input" name="sl_date" value="<?= set_value('sl_date', $transaction['sl_date']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Customer</label>
                            <input type="text" class="wl-input" name="customer" value="<?= set_value('customer', $transaction['customer']); ?>">
                        </div>
                        
                        <div class="wl-form-group" style="grid-column: 1 / -1;">
                            <label class="wl-label">Alamat</label>
                            <textarea class="wl-input" name="alamat"><?= set_value('alamat', $transaction['alamat']); ?></textarea>
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Customer Phone</label>
                            <input type="text" class="wl-input" name="cust_phone" value="<?= set_value('cust_phone', $transaction['cust_phone']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Sales Name</label>
                            <input type="text" class="wl-input" name="sales_name" value="<?= set_value('sales_name', $transaction['sales_name']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">SPV</label>
                            <input type="text" class="wl-input" name="spv" value="<?= set_value('spv', $transaction['spv']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Leasing</label>
                            <input type="text" class="wl-input" name="leasing" value="<?= set_value('leasing', $transaction['leasing']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Insurance</label>
                            <input type="text" class="wl-input" name="insurance" value="<?= set_value('insurance', $transaction['insurance']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Invoice No</label>
                            <input type="text" class="wl-input" name="inv_no" value="<?= set_value('inv_no', $transaction['inv_no']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Code</label>
                            <input type="text" class="wl-input" name="code" value="<?= set_value('code', $transaction['code']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Type</label>
                            <input type="text" class="wl-input" name="type" value="<?= set_value('type', $transaction['type']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Chassis</label>
                            <input type="text" class="wl-input" name="chassis" value="<?= set_value('chassis', $transaction['chassis']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Price List</label>
                            <input type="number" class="wl-input" name="price_list" value="<?= set_value('price_list', $transaction['price_list']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Discount</label>
                            <input type="number" class="wl-input" name="discount" value="<?= set_value('discount', $transaction['discount']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Price Net</label>
                            <input type="number" class="wl-input" name="price_net" value="<?= set_value('price_net', $transaction['price_net']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">DP Amount</label>
                            <input type="number" class="wl-input" name="dp_amt" value="<?= set_value('dp_amt', $transaction['dp_amt']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Leasing Amount</label>
                            <input type="number" class="wl-input" name="leasing_amt" value="<?= set_value('leasing_amt', $transaction['leasing_amt']); ?>">
                        </div>
                        
                        <div class="wl-form-group" style="grid-column: 1 / -1;">
                            <label class="wl-label">Description 2</label>
                            <textarea class="wl-input" name="description_2"><?= set_value('description_2', $transaction['description_2']); ?></textarea>
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Tenor</label>
                            <input type="number" class="wl-input" name="tenor" value="<?= set_value('tenor', $transaction['tenor']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">KTP No</label>
                            <input type="text" class="wl-input" name="ktp_no" value="<?= set_value('ktp_no', $transaction['ktp_no']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">DO Status</label>
                            <div class="wl-flex wl-items-center wl-gap-2">
                                <input type="checkbox" name="do_status" value="1" <?= set_checkbox('do_status', '1', ($transaction['do_status'] == 1)); ?> style="accent-color: var(--primary);">
                                <label>Yes</label>
                            </div>
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Tunai/Kredit</label>
                            <select class="wl-select" name="tunai_kredit">
                                <option value="Tunai" <?= set_select('tunai_kredit', 'Tunai', ($transaction['tunai_kredit'] == 'Tunai')); ?>>Tunai</option>
                                <option value="Kredit" <?= set_select('tunai_kredit', 'Kredit', ($transaction['tunai_kredit'] == 'Kredit')); ?>>Kredit</option>
                            </select>
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Model</label>
                            <input type="text" class="wl-input" name="model" value="<?= set_value('model', $transaction['model']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Date (Day)</label>
                            <input type="number" class="wl-input" name="hari" value="<?= set_value('hari', $transaction['hari']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Bulan</label>
                            <input type="text" class="wl-input" name="bulan" value="<?= set_value('bulan', $transaction['bulan']); ?>">
                        </div>
                        
                        <div class="wl-form-group">
                            <label class="wl-label">Tahun</label>
                            <input type="number" class="wl-input" name="tahun" value="<?= set_value('tahun', $transaction['tahun']); ?>">
                        </div>
                    </div>
                    
                    <div class="wl-flex wl-gap-2 wl-justify-end wl-mt-4" style="border-top: 1px solid var(--border); padding-top: 1rem;">
                        <a href="<?= site_url('transaction/view/' . $transaction['id']); ?>" class="wl-btn wl-btn-secondary">View Details</a>
                        <button type="submit" class="wl-btn wl-btn-primary" id="submitBtn"><i class="fas fa-save"></i> Update</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    // Prevent double form submission
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        var submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        
        // Re-enable after 3 seconds as fallback
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update';
        }, 3000);
    });

    // Auto calculate price net when price list or discount changes
    function calculatePriceNet() {
        var priceList = parseFloat(document.querySelector('input[name="price_list"]').value) || 0;
        var discount = parseFloat(document.querySelector('input[name="discount"]').value) || 0;
        var priceNet = priceList - discount;
        document.querySelector('input[name="price_net"]').value = priceNet;
    }

    document.querySelector('input[name="price_list"]').addEventListener('input', calculatePriceNet);
    document.querySelector('input[name="discount"]').addEventListener('input', calculatePriceNet);

    // Auto format date inputs
    document.addEventListener('DOMContentLoaded', function() {
        var dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(function(input) {
            if (input.value && input.value.length === 8) {
                // Convert YYYYMMDD to YYYY-MM-DD format if needed
                var dateValue = input.value;
                if (dateValue.indexOf('-') === -1) {
                    input.value = dateValue.substring(0,4) + '-' + dateValue.substring(4,6) + '-' + dateValue.substring(6,8);
                }
            }
        });
    });
    </script>

    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>
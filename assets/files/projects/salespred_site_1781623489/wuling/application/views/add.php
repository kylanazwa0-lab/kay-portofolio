<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <style>
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 150px; }
        input, select, textarea { width: 300px; padding: 5px; }
        .error { color: red; }
    </style>

    <script>if(localStorage.getItem('theme') === 'light') document.documentElement.setAttribute('data-theme', 'light');</script>
</head>
<body>
    <h2>Add Transaction</h2>
    <?php echo validation_errors('<div class="error">', '</div>'); ?>
    <form method="post" action="<?php echo site_url('transaction/save'); ?>">
        <div class="form-group">
            <label>SL Date</label>
            <input type="date" name="sl_date" value="<?php echo set_value('sl_date'); ?>">
        </div>
        <div class="form-group">
            <label>Customer</label>
            <input type="text" name="customer" value="<?php echo set_value('customer'); ?>">
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat"><?php echo set_value('alamat'); ?></textarea>
        </div>
        <div class="form-group">
            <label>Customer Phone</label>
            <input type="text" name="cust_phone" value="<?php echo set_value('cust_phone'); ?>">
        </div>
        <div class="form-group">
            <label>Sales Name</label>
            <input type="text" name="sales_name" value="<?php echo set_value('sales_name'); ?>">
        </div>
        <div class="form-group">
            <label>SPV</label>
            <input type="text" name="spv" value="<?php echo set_value('spv'); ?>">
        </div>
        <div class="form-group">
            <label>Leasing</label>
            <input type="text" name="leasing" value="<?php echo set_value('leasing'); ?>">
        </div>
        <div class="form-group">
            <label>Insurance</label>
            <input type="text" name="insurance" value="<?php echo set_value('insurance'); ?>">
        </div>
        <div class="form-group">
            <label>Invoice No</label>
            <input type="text" name="inv_no" value="<?php echo set_value('inv_no'); ?>">
        </div>
        <div class="form-group">
            <label>Code</label>
            <input type="text" name="code" value="<?php echo set_value('code'); ?>">
        </div>
        <div class="form-group">
            <label>Type</label>
            <input type="text" name="type" value="<?php echo set_value('type'); ?>">
        </div>
        <div class="form-group">
            <label>Chassis</label>
            <input type="text" name="chassis" value="<?php echo set_value('chassis'); ?>">
        </div>
        <div class="form-group">
            <label>Price List</label>
            <input type="number" name="price_list" value="<?php echo set_value('price_list'); ?>">
        </div>
        <div class="form-group">
            <label>Discount</label>
            <input type="number" name="discount" value="<?php echo set_value('discount'); ?>">
        </div>
        <div class="form-group">
            <label>Price Net</label>
            <input type="number" name="price_net" value="<?php echo set_value('price_net'); ?>">
        </div>
        <div class="form-group">
            <label>DP Amount</label>
            <input type="number" name="dp_amt" value="<?php echo set_value('dp_amt'); ?>">
        </div>
        <div class="form-group">
            <label>Leasing Amount</label>
            <input type="number" name="leasing_amt" value="<?php echo set_value('leasing_amt'); ?>">
        </div>
        <div class="form-group">
            <label>Description 2</label>
            <textarea name="description_2"><?php echo set_value('description_2'); ?></textarea>
        </div>
        <div class="form-group">
            <label>Tenor</label>
            <input type="number" name="tenor" value="<?php echo set_value('tenor'); ?>">
        </div>
        <div class="form-group">
            <label>KTP No</label>
            <input type="text" name="ktp_no" value="<?php echo set_value('ktp_no'); ?>">
        </div>
        <div class="form-group">
            <label>DO Status</label>
            <input type="checkbox" name="do_status" value="1" <?php echo set_checkbox('do_status', '1'); ?>> Yes
        </div>
        <div class="form-group">
            <label>Tunai/Kredit</label>
            <select name="tunai_kredit">
                <option value="Tunai" <?php echo set_select('tunai_kredit', 'Tunai'); ?>>Tunai</option>
                <option value="Kredit" <?php echo set_select('tunai_kredit', 'Kredit'); ?>>Kredit</option>
            </select>
        </div>
        <div class="form-group">
            <label>Model</label>
            <input type="text" name="model" value="<?php echo set_value('model'); ?>">
        </div>
        <div class="form-group">
            <label>Date (Day)</label>
            <input type="number" name="date_day" value="<?php echo set_value('date_day'); ?>">
        </div>
        <div class="form-group">
            <label>Month</label>
            <input type="text" name="month" value="<?php echo set_value('month'); ?>">
        </div>
        <div class="form-group">
            <label>Year</label>
            <input type="number" name="year" value="<?php echo set_value('year'); ?>">
        </div>
        <div class="form-group">
            <input type="submit" value="Save">
            <a href="<?php echo site_url('transaction'); ?>">Cancel</a>
        </div>
    </form>

    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>
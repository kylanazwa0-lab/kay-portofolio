<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .btn { padding: 5px 10px; margin: 5px; }
    </style>

    <script>if(localStorage.getItem('theme') === 'light') document.documentElement.setAttribute('data-theme', 'light');</script>
</head>
<body>
    <h2>Transaction List</h2>
    <a href="<?php echo site_url('transaction/add'); ?>" class="btn">Add New Transaction</a>
    <table>
        <tr>
            <th>No</th>
            <th>SL Date</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Model</th>
            <th>Tunai/Kredit</th>
            <th>Price Net</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($transactions as $transaction): ?>
        <tr>
            <td><?php echo $transaction['id']; ?></td>
            <td><?php echo $transaction['sl_date']; ?></td>
            <td><?php echo $transaction['customer']; ?></td>
            <td><?php echo $transaction['cust_phone']; ?></td>
            <td><?php echo $transaction['model']; ?></td>
            <td><?php echo $transaction['tunai_kredit']; ?></td>
            <td><?php echo number_format($transaction['price_net'], 0, ',', '.'); ?></td>
            <td>
                <a href="<?php echo site_url('transaction/edit/' . $transaction['id']); ?>">Edit</a> |
                <a href="<?php echo site_url('transaction/delete/' . $transaction['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
</html>
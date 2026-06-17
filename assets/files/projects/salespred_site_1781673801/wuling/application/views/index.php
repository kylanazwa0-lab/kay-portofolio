<!DOCTYPE html>
    <html lang="in">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Import Excel</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    
    <script>if(localStorage.getItem('theme') === 'light') document.documentElement.setAttribute('data-theme', 'light');</script>
</head>
    <body>
        <?php $this->load->view('templates/navbar'); ?>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Data Mahasiswa
                    </div>
                    <div class="card-body">
                        <?= $this->session->flashdata('message');?>
                        <a href="<?= site_url('import/create') ?>" class="btn btn-primary mb-3">Import</a>
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Nim</th>
                                <th>Nama</th>
                                <th>Angkatan</th>
                            </tr>
                            <?php if (count($mahasiswa) > 0) {
                                    foreach ($mahasiswa as $row): ?>
                                    <tr>
                                        <td><?= $row->nim ?></td>
                                        <td><?= $row->nama ?></td>
                                        <td><?= $row->angkatan ?></td>
                                    </tr>
                                <?php endforeach ?>
                           <?php }else{ ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data</td>
                                </tr>
                        <?php } ?>
                           
                        </table>
                    </div>
                    <div class="card-footer">
                        Page
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>
</body>
    </html>
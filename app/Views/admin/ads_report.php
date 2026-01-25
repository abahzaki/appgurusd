<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Laporan Performa Iklan & ROI</h1>

    <?php if(session()->getFlashdata('msg')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('msg') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Input Biaya Iklan (Harian)</h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('adsreport/store') ?>" method="post" class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Total Ad Spend (Rp)</label>
                    <input type="number" name="amount" class="form-control" placeholder="Contoh: 150000" required>
                </div>
                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-2"></i> Simpan Biaya
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="border-left: 5px solid #f6c23e;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Ad Spend (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_biaya, 0, ',', '.') ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-wallet2 fa-2x text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-left: 5px solid #36b9cc;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sales via Ads</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_sales ?> User</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cart-check fa-2x text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-left: 5px solid #1cc88a;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Omset Iklan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($omset_ads, 0, ',', '.') ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin fa-2x text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <?php 
                $warna_roi = ($roi > 0) ? 'primary' : 'danger'; // Biru jika untung, Merah jika rugi
                $warna_border = ($roi > 0) ? '#4e73df' : '#e74a3b';
            ?>
            <div class="card shadow h-100 py-2" style="border-left: 5px solid <?= $warna_border ?>;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-<?= $warna_roi ?> text-uppercase mb-1">ROI (Return On Investment)</div>
                            <div class="h5 mb-0 font-weight-bold text-<?= $warna_roi ?>">
                                <?= number_format($roi, 1) ?>%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-percent fa-2x text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Pengeluaran Iklan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Platform</th>
                            <th>Biaya (IDR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($history_biaya)): ?>
                            <tr><td colspan="3" class="text-center">Belum ada data biaya. Input di atas dulu yuk!</td></tr>
                        <?php else: ?>
                            <?php foreach($history_biaya as $row): ?>
                            <tr>
                                <td><?= date('d F Y', strtotime($row['tanggal'])) ?></td>
                                <td><span class="badge bg-primary"><?= $row['platform'] ?></span></td>
                                <td>Rp <?= number_format($row['amount'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>
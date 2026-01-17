<?= $this->extend('layout/template'); ?> 
<?= $this->section('content'); ?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Manajemen User (Guru)</h1>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold"><i class="bi bi-people-fill me-2"></i>Daftar Pengguna Aplikasi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Nama Lengkap</th>
                            <th width="25%">Email</th>
                            <th width="15%">Status</th>
                            <th width="15%">Masa Aktif (Expired)</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($users as $u) : ?>
                        <tr>
                            <td class="text-center align-middle"><?= $i++; ?></td>
                            <td class="align-middle">
                                <strong><?= $u['nama_lengkap']; ?></strong><br>
                                <span class="badge bg-secondary rounded-pill"><?= ucfirst($u['role']); ?></span>
                            </td>
                            <td class="align-middle"><?= $u['email']; ?></td>
                            
                            <td class="text-center align-middle">
                                <?php 
                                    $today = date('Y-m-d');
                                    if ($u['is_active'] == 0) {
                                        // Belum Aktif (Kuning)
                                        echo '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Belum Aktif</span>';
                                    } elseif ($u['expired_date'] != null && $u['expired_date'] < $today) {
                                        // Expired (Merah)
                                        echo '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Expired</span>';
                                    } else {
                                        // Aktif (Hijau)
                                        echo '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Aktif</span>';
                                    }
                                ?>
                            </td>

                            <td class="align-middle">
                                <?php if($u['expired_date']): ?>
                                    <i class="bi bi-calendar-event text-primary"></i> <?= date('d M Y', strtotime($u['expired_date'])); ?>
                                    
                                    <?php 
                                        $sisaHari = (strtotime($u['expired_date']) - time()) / (60 * 60 * 24);
                                        if($sisaHari < 0) echo '<br><small class="text-danger fw-bold">(Sudah Habis)</small>';
                                        elseif($sisaHari < 30) echo '<br><small class="text-warning fw-bold">(< 1 Bulan)</small>';
                                    ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <?php if ($u['is_active'] == 0) : ?>
                                        <a href="<?= base_url('user/activate/' . $u['id']) ?>" class="btn btn-success btn-sm" onclick="return confirm('Yakin ingin mengaktifkan user ini selama 1 Semester?')" title="Aktifkan">
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                    <?php else : ?>
                                        <a href="<?= base_url('user/deactivate/' . $u['id']) ?>" class="btn btn-secondary btn-sm" onclick="return confirm('Matikan akses user ini?')" title="Non-Aktifkan">
                                            <i class="bi bi-pause-fill"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= base_url('user/resetpassword/' . $u['id']) ?>" 
                                       class="btn btn-info btn-sm text-white" 
                                       onclick="return confirm('Reset password user ini menjadi 123456?')"
                                       title="Reset Password (123456)">
                                       <i class="bi bi-key-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>
<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h3 class="text-primary"><i class="bi bi-robot"></i> Bank Soal AI</h3>
            <p class="text-muted mb-0">Riwayat generasi soal otomatis Anda.</p>
        </div>
        <a href="<?= base_url('asesmen/baru') ?>" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Buat Soal Baru (AI)
        </a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Soal Tersimpan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle" id="dataTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Judul / Materi</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas / Fase</th>
                            <th>Jenis</th>
                            <th>Tanggal Dibuat</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($riwayat)): ?>
                            <?php $no=1; foreach($riwayat as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td>
                                    <span class="fw-bold text-dark"><?= $row->judul ?></span>
                                </td>
                                <td><?= $row->nama_mapel ?></td>
                                <td>
                                    <?= $row->nama_kelas ?> <br>
                                    <span class="badge bg-info text-dark" style="font-size: 0.7rem;">Fase <?= $row->fase ?></span>
                                </td>
                                <td>
                                    <?php if($row->jenis_ujian == 'UH'): ?>
                                        <span class="badge bg-success">Formatif (UH)</span>
                                    <?php elseif($row->jenis_ujian == 'STS'): ?>
                                        <span class="badge bg-warning text-dark">STS</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">SAS</span>
                                    <?php endif; ?>
                                </td>
                                <td><small class="text-muted"><?= date('d M Y H:i', strtotime($row->tanggal_buat)) ?></small></td>
                                <td class="text-center">
                                    <a href="<?= base_url('asesmen/lihat/' . $row->id) ?>" class="btn btn-sm btn-warning text-white" title="Edit / Cetak">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="<?= base_url('asesmen/delete/' . $row->id) ?>" class="btn btn-sm btn-danger onclick-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus soal ini?');">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada riwayat soal. Silakan klik "Buat Soal Baru".
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
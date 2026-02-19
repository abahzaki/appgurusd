<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bank Program Semester (Promes)</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white border-bottom-info">
            <h6 class="m-0 font-weight-bold text-info"><i class="bi bi-calendar-range me-2"></i>Daftar Promes Siap Cetak</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas / Fase</th>
                            <th>Tahun Ajar</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($history)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="mb-3"><i class="bi bi-calendar-x display-4 text-muted"></i></div>
                                    <h5 class="text-muted">Belum ada data Promes.</h5>
                                    <p class="small text-muted">Promes otomatis dibuat setelah Anda menyusun ATP.</p>
                                    <a href="<?= base_url('cetak_atp') ?>" class="btn btn-primary btn-sm shadow-sm">
                                        <i class="bi bi-diagram-3-fill me-1"></i> Susun ATP Sekarang
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no=1; foreach($history as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="fw-bold"><?= $row['nama_mapel'] ?></td>
                                <td><?= $row['nama_kelas'] ?> <span class="badge bg-info text-dark">Fase <?= $row['fase'] ?></span></td>
                                <td><?= $row['tahun_ajar'] ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('cetak_atp/download_promes/'.$row['id']) ?>" 
                                       class="btn btn-info btn-sm text-dark fw-bold shadow-sm" title="Download Word Promes">
                                        <i class="bi bi-file-earmark-word-fill me-1"></i> Download Promes
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 text-gray-800">Input STS & SAS</h1>
            <p class="mb-0 text-muted">Sumatif Tengah Semester & Akhir Semester</p>
        </div>
        <a href="<?= base_url('nilai') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('nilai/simpan_ujian') ?>" method="post">
        <input type="hidden" name="kelas_id" value="<?= $kelas_id ?>">
        <input type="hidden" name="mapel_id" value="<?= $mapel_id ?>">

        <div class="card shadow mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead class="bg-primary text-white text-center align-middle">
                            <tr>
                                <th width="5%">No</th>
                                <th class="text-start">Nama Siswa</th>
                                <th width="15%">Nilai STS<br><small>(Tengah Sem)</small></th>
                                <th width="15%">Nilai SAS<br><small>(Akhir Sem)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($siswa as $s): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="fw-bold"><?= $s['nama_lengkap'] ?></td>
                                
                                <?php 
                                    $val_sts = isset($nilai_map[$s['id']]['sts']) ? $nilai_map[$s['id']]['sts'] : "";
                                    $val_sas = isset($nilai_map[$s['id']]['sas']) ? $nilai_map[$s['id']]['sas'] : "";
                                ?>
                                
                                <td class="p-1">
                                    <input type="number" name="sts[<?= $s['id'] ?>]" class="form-control text-center fw-bold text-primary" value="<?= $val_sts ?>" placeholder="0">
                                </td>
                                <td class="p-1">
                                    <input type="number" name="sas[<?= $s['id'] ?>]" class="form-control text-center fw-bold text-danger" value="<?= $val_sas ?>" placeholder="0">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white sticky-bottom">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success btn-lg shadow">
                        <i class="bi bi-save"></i> SIMPAN NILAI UJIAN
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
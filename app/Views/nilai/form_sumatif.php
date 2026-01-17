<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 text-gray-800">Form Nilai Sumatif</h1>
            <p class="mb-0 text-muted">Silakan isi nilai (0-100) pada kolom TP yang tersedia.</p>
        </div>
        <a href="<?= base_url('nilai') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('nilai/simpan') ?>" method="post">
        <input type="hidden" name="kelas_id" value="<?= $kelas_id ?>">
        <input type="hidden" name="mapel_id" value="<?= $mapel_id ?>">

        <div class="card shadow mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead class="bg-primary text-white text-center align-middle">
                            <tr>
                                <th rowspan="2" width="5%">No</th>
                                <th rowspan="2" width="25%" class="text-start">Nama Siswa</th>
                                <th colspan="<?= count($list_tp) ?>">Tujuan Pembelajaran (TP)</th>
                            </tr>
                            <tr>
                                <?php foreach($list_tp as $tp): ?>
                                    <th width="100px" title="<?= $tp['deskripsi_tp'] ?>" data-bs-toggle="tooltip">
                                        <?= $tp['kode_tp'] ?> <br>
                                        <small style="font-size:9px; font-weight:normal;">(Arahkan Mouse)</small>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($siswa as $s): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="fw-bold"><?= $s['nama_lengkap'] ?></td>
                                
                                <?php foreach($list_tp as $tp): ?>
                                    <?php 
                                        // Cek apakah sudah ada nilainya?
                                        $nilai_lama = "";
                                        if(isset($nilai_map[$s['id']][$tp['id']])) {
                                            $nilai_lama = $nilai_map[$s['id']][$tp['id']];
                                        }
                                    ?>
                                    <td class="p-1">
                                        <input type="number" 
                                               name="nilai[<?= $s['id'] ?>][<?= $tp['id'] ?>]" 
                                               class="form-control text-center" 
                                               value="<?= $nilai_lama ?>" 
                                               min="0" max="100" placeholder="-">
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white sticky-bottom" style="z-index: 99;">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success btn-lg shadow">
                        <i class="bi bi-save"></i> SIMPAN SEMUA NILAI
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

<?= $this->endSection() ?>
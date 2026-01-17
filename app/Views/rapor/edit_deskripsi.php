<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Edit Deskripsi: <?= $siswa['nama_lengkap'] ?></h1>
        <a href="<?= base_url('rapor?kelas_id='.$siswa['kelas_id']) ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('rapor/simpan_deskripsi') ?>" method="post">
        <input type="hidden" name="siswa_id" value="<?= $siswa['id'] ?>">

        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 fw-bold"><i class="bi bi-pencil-square"></i> Koreksi Deskripsi Capaian Kompetensi</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> <b>Petunjuk:</b><br>
                    - Kolom <b>"Saran Sistem"</b> adalah kalimat otomatis.<br>
                    - Kolom <b>"Edit Manual"</b> adalah kalimat yang akan dicetak di rapor.<br>
                    - Jika kolom "Edit Manual" <b>DIKOSONGKAN</b>, maka rapor akan menggunakan Saran Sistem.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="20%">Mata Pelajaran</th>
                                <th width="40%">Saran Otomatis (System)</th>
                                <th width="40%">Edit Manual (Guru)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($list_deskripsi as $d): ?>
                            <tr>
                                <td class="fw-bold"><?= $d['nama_mapel'] ?></td>
                                <td class="text-muted fst-italic bg-light">
                                    <?= $d['deskripsi_auto'] ?>
                                </td>
                                <td>
                                    <input type="hidden" name="mapel_id[]" value="<?= $d['mapel_id'] ?>">
                                    <textarea name="deskripsi_custom[]" class="form-control" rows="3" placeholder="Tulis deskripsi manual disini jika ingin mengubah..."><?= $d['deskripsi_custom'] ?></textarea>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> SIMPAN PERUBAHAN</button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
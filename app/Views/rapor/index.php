<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Cetak Rapor Siswa</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="" method="get">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label class="fw-bold">Pilih Kelas</label>
                        <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach($kelas as $k): ?>
                                <option value="<?= $k['id'] ?>" <?= ($kelas_id == $k['id']) ? 'selected' : '' ?>>
                                    <?= $k['nama_kelas'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if($kelas_id && !empty($siswa)): ?>
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 fw-bold">Daftar Siswa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($siswa as $s): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $s['nisn'] ?></td>
                            <td><?= $s['nama_lengkap'] ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('rapor/edit_deskripsi/'.$s['id']) ?>" target="_blank" class="btn btn-success btn-sm me-1">
                                    <i class="bi bi-pencil-square"></i> EDIT DESKRIPSI
                                </a>

                                <a href="<?= base_url('rapor/cetak/'.$s['id']) ?>" target="_blank" class="btn btn-warning btn-sm fw-bold">
                                    <i class="bi bi-printer-fill"></i> CETAK RAPOR
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
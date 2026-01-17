<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid mb-5">
    <h1 class="h3 mb-4 text-gray-800">Input Ekstrakurikuler</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="" method="get" class="row align-items-center">
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
            </form>
        </div>
    </div>

    <?php if($kelas_id && !empty($siswa)): ?>
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 fw-bold">Daftar Siswa & Kegiatan Ekskul</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Nama Siswa</th>
                            <th>Kegiatan Ekstrakurikuler yang Diikuti</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($siswa as $s): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="fw-bold"><?= $s['nama_lengkap'] ?></td>
                            <td>
                                <?php if(isset($data_ekskul[$s['id']])): ?>
                                    <ul class="list-group mb-2">
                                    <?php foreach($data_ekskul[$s['id']] as $eks): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                            <div>
                                                <strong><?= $eks['nama_ekskul'] ?>:</strong> 
                                                <span class="badge bg-success"><?= $eks['predikat'] ?></span>
                                                <br><small class="text-muted"><?= $eks['keterangan'] ?></small>
                                            </div>
                                            <a href="<?= base_url('ekskul/hapus/'.$eks['id']) ?>" 
                                               class="btn btn-xs btn-danger rounded-circle" 
                                               onclick="return confirm('Hapus ekskul ini?')" title="Hapus">
                                               <i class="bi bi-x"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <span class="text-muted fst-italic small">Belum ada data ekskul.</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalEkskul<?= $s['id'] ?>">
                                    <i class="bi bi-plus-circle"></i> Tambah
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEkskul<?= $s['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Tambah Ekskul: <?= $s['nama_lengkap'] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?= base_url('ekskul/simpan') ?>" method="post">
                                        <div class="modal-body">
                                            <input type="hidden" name="siswa_id" value="<?= $s['id'] ?>">
                                            <input type="hidden" name="kelas_id" value="<?= $kelas_id ?>">

                                            <div class="mb-3">
                                                <label class="form-label">Nama Ekstrakurikuler</label>
                                                <select name="nama_ekskul" class="form-select" required>
                                                    <option value="Pramuka">Pramuka (Wajib)</option>
                                                    <option value="Futsal">Futsal</option>
                                                    <option value="Tari">Seni Tari</option>
                                                    <option value="Pencak Silat">Pencak Silat</option>
                                                    <option value="Drumband">Drumband</option>
                                                    <option value="BTQ">BTQ (Baca Tulis Quran)</option>
                                                    <option value="Lainnya">Lainnya...</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Predikat</label>
                                                <select name="predikat" class="form-select">
                                                    <option value="Sangat Baik">Sangat Baik</option>
                                                    <option value="Baik">Baik</option>
                                                    <option value="Cukup">Cukup</option>
                                                    <option value="Kurang">Kurang</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Keterangan</label>
                                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Ananda sangat aktif dan disiplin..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
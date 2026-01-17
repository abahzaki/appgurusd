<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Edit Data Siswa</h1>
        <a href="<?= base_url('datamaster/siswa') ?>" class="btn btn-secondary">Kembali</a>
    </div>

    <form action="<?= base_url('datamaster/siswa/update/'.$s['id']) ?>" method="post">
        
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Identitas Pribadi</h6></div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">NIS</label>
                                <input type="text" name="nis" class="form-control" value="<?= $s['nis'] ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">NISN</label>
                                <input type="text" name="nisn" class="form-control" value="<?= $s['nisn'] ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="<?= $s['nama_lengkap'] ?>" required>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label fw-bold text-primary">
                                <i class="bi bi-house-door-fill"></i> Kelas Saat Ini
                            </label>
                        <select name="kelas_id" class="form-select border-primary fw-bold">
                            <option value="0">-- Belum Masuk Kelas --</option>
                        <?php foreach($kelas as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($k['id'] == $s['kelas_id']) ? 'selected' : '' ?>>
                        <?= $k['nama_kelas'] ?> (Fase <?= $k['fase'] ?>)
                        </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Pindahkan siswa ke kelas lain melalui menu ini.</small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select">
                                    <option value="L" <?= $s['jenis_kelamin']=='L'?'selected':'' ?>>Laki-laki</option>
                                    <option value="P" <?= $s['jenis_kelamin']=='P'?'selected':'' ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Agama</label>
                                <input type="text" name="agama" class="form-control" value="<?= $s['agama'] ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control" value="<?= $s['tempat_lahir'] ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Tgl Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control" value="<?= $s['tanggal_lahir'] ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Alamat Siswa</h6></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Alamat Jalan</label>
                            <textarea name="alamat_peserta_didik" class="form-control" rows="2"><?= $s['alamat_peserta_didik'] ?></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6"><label class="small">Desa</label><input type="text" name="desa" class="form-control form-control-sm" value="<?= $s['desa'] ?>"></div>
                            <div class="col-6"><label class="small">Kecamatan</label><input type="text" name="kecamatan" class="form-control form-control-sm" value="<?= $s['kecamatan'] ?>"></div>
                        </div>
                        <div class="row">
                            <div class="col-6"><label class="small">Kota/Kab</label><input type="text" name="kota" class="form-control form-control-sm" value="<?= $s['kota'] ?>"></div>
                            <div class="col-6"><label class="small">Propinsi</label><input type="text" name="propinsi" class="form-control form-control-sm" value="<?= $s['propinsi'] ?>"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-success">Data Orang Tua</h6></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" value="<?= $s['nama_ayah'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" class="form-control" value="<?= $s['pekerjaan_ayah'] ?>">
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" value="<?= $s['nama_ibu'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" class="form-control" value="<?= $s['pekerjaan_ibu'] ?>">
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-warning">Data Wali (Opsional)</h6></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Wali</label>
                            <input type="text" name="nama_wali" class="form-control" value="<?= $s['nama_wali'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon / WA</label>
                            <input type="text" name="no_telephone" class="form-control" value="<?= $s['no_telephone'] ?>">
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 btn-lg">SIMPAN PERUBAHAN</button>
            </div>
        </div>

    </form>
</div>

<?= $this->endSection() ?>
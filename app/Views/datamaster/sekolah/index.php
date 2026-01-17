<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid mb-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Identitas Sekolah</h1>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('datamaster/sekolah/update') ?>" method="post">
        
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Profil Instansi</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="nama_sekolah" class="form-control fw-bold" value="<?= $s['nama_sekolah'] ?? '' ?>" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">NPSN</label>
                                <input type="text" name="npsn" class="form-control" value="<?= $s['npsn'] ?? '' ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">NSS (Opsional)</label>
                                <input type="text" name="nss" class="form-control" value="<?= $s['nss'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap (Jalan)</label>
                            <textarea name="alamat_sekolah" class="form-control" rows="2"><?= $s['alamat_sekolah'] ?? '' ?></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Kelurahan/Desa</label>
                                <input type="text" name="kelurahan" class="form-control" value="<?= $s['kelurahan'] ?? '' ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Kecamatan</label>
                                <input type="text" name="kecamatan" class="form-control" value="<?= $s['kecamatan'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Kabupaten/Kota</label>
                                <input type="text" name="kabupaten_kota" class="form-control" value="<?= $s['kabupaten_kota'] ?? '' ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Provinsi</label>
                                <input type="text" name="provinsi" class="form-control" value="<?= $s['provinsi'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" name="kode_pos" class="form-control" value="<?= $s['kode_pos'] ?? '' ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="telepon" class="form-control" value="<?= $s['telepon'] ?? '' ?>">
                            </div>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $s['email'] ?? '' ?>">
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="text" name="website" class="form-control" value="<?= $s['website'] ?? '' ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Kepala Sekolah</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info small">
                            Data ini akan muncul di kolom tanda tangan Raport.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Kepala Sekolah</label>
                            <input type="text" name="nama_kepsek" class="form-control" value="<?= $s['nama_kepsek'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip_kepsek" class="form-control" value="<?= $s['nip_kepsek'] ?? '' ?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 shadow">
                    <i class="bi bi-save"></i> SIMPAN IDENTITAS SEKOLAH
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
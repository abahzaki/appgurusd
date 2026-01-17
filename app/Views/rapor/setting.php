<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Pengaturan Rapor (Control Panel)</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 fw-bold"><i class="bi bi-gear-fill"></i> Setting Identitas Rapor</h6>
                </div>
                <div class="card-body">
                    
                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('rapor/update_setting') ?>" method="post">
                        <input type="hidden" name="id" value="<?= $setting['id'] ?? '' ?>">

                        <div class="mb-3">
                            <label class="fw-bold">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" value="<?= $setting['tahun_ajaran'] ?? '' ?>" required>
                            <small class="text-muted">Contoh: 2025/2026</small>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Semester Aktif</label>
                            <select name="semester" class="form-select">
                                <?php $sem = $setting['semester'] ?? '1'; ?>
                                <option value="1" <?= ($sem == '1') ? 'selected' : '' ?>>Semester 1 (Ganjil)</option>
                                <option value="2" <?= ($sem == '2') ? 'selected' : '' ?>>Semester 2 (Genap)</option>
                            </select>
                            <small class="text-danger fw-bold">*Jika Semester 2 dipilih, Kotak Kenaikan/Kelulusan akan muncul di rapor.</small>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Tanggal Titimangsa Rapor</label>
                            <input type="date" name="tanggal_rapor" class="form-control" value="<?= $setting['tanggal_rapor'] ?? '' ?>" required>
                            <small class="text-muted">Tanggal ini akan muncul di atas tanda tangan Wali Kelas.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold">Kota Penerbitan</label>
                            <input type="text" name="kota_terbit" class="form-control" value="<?= $setting['kota_terbit'] ?? '' ?>" placeholder="Contoh: Jakarta">
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> SIMPAN PENGATURAN
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
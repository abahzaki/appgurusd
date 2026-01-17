<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Input Asesmen Sumatif (Per TP)</h1>

    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            <form action="<?= base_url('nilai/form') ?>" method="get">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label class="fw-bold">Pilih Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach($kelas as $k): ?>
                                <option value="<?= $k['id'] ?>"><?= $k['nama_kelas'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="fw-bold">Pilih Mata Pelajaran</label>
                        <select name="mapel_id" class="form-select" required>
                            <option value="">-- Pilih Mapel --</option>
                            <?php foreach($mapel as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    
                    <div class="col-md-4 d-flex gap-2">
    <button type="submit" formaction="<?= base_url('nilai/form') ?>" class="btn btn-danger w-100">
        <i class="bi bi-grid-3x3"></i> Nilai TP (Harian)
    </button>
    
    <button type="submit" formaction="<?= base_url('nilai/form_ujian') ?>" class="btn btn-primary w-100">
        <i class="bi bi-pen"></i> Nilai STS & SAS
    </button>
</div>


    
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> <strong>Petunjuk:</strong><br>
        Menu ini digunakan untuk menginput nilai harian berdasarkan <b>Tujuan Pembelajaran (TP)</b>.<br>
        Pastikan Anda sudah menginput Data TP di menu <u>Data Master > Tujuan Pembelajaran</u>.
    </div>
</div>

<?= $this->endSection() ?>
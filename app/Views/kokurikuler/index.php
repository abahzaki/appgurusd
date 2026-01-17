<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Projek Kokurikuler (P5)</h1>

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

    <?php if($kelas_id): ?>
    
    <div class="mb-3">
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Buat Projek Baru
        </button>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="row">
        <?php foreach($list_projek as $p): ?>
        <div class="col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Projek Kokurikuler
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $p['nama_projek'] ?></div>
                            <p class="text-muted small mt-2"><?= $p['deskripsi'] ?></p>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('kokurikuler/nilai/'.$p['id']) ?>" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50"><i class="bi bi-pencil-square"></i></span>
                                <span class="text">Input Nilai</span>
                            </a>
                            <br>
                            <a href="<?= base_url('kokurikuler/hapus_projek/'.$p['id']) ?>" onclick="return confirm('Hapus projek ini? Nilai siswa akan ikut terhapus.')" class="btn btn-danger btn-sm mt-2 w-100">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($list_projek)): ?>
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-folder-x display-4"></i>
                <p>Belum ada projek dibuat di kelas ini.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Buat Projek Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('kokurikuler/tambah_projek') ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="kelas_id" value="<?= $kelas_id ?>">
                        <div class="mb-3">
                            <label class="form-label">Nama/Tema Projek</label>
                            <input type="text" name="nama_projek" class="form-control" placeholder="Contoh: Gaya Hidup Berkelanjutan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi kegiatan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>
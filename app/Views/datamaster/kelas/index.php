<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Data Kelas & Wali Kelas</h1>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Kelas
        </button>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Fase</th>
                            <th>Wali Kelas</th>
                            <th>NIP</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($kelas as $k): ?>
                        <tr>
                            <td class="fw-bold"><?= $k['nama_kelas'] ?></td>
                            <td><span class="badge bg-info text-dark">Fase <?= $k['fase'] ?></span></td>
                            <td><?= $k['wali_kelas'] ?></td>
                            <td><?= $k['nip_wali'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEdit<?= $k['id'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="<?= base_url('datamaster/kelas/delete/'.$k['id']) ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Hapus kelas ini?')">
                                   <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEdit<?= $k['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title">Edit Kelas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?= base_url('datamaster/kelas/update/'.$k['id']) ?>" method="post">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Kelas</label>
                                                <input type="text" name="nama_kelas" class="form-control" value="<?= $k['nama_kelas'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Fase</label>
                                                <select name="fase" class="form-select">
                                                    <option value="A" <?= $k['fase']=='A'?'selected':'' ?>>Fase A (Kelas 1-2)</option>
                                                    <option value="B" <?= $k['fase']=='B'?'selected':'' ?>>Fase B (Kelas 3-4)</option>
                                                    <option value="C" <?= $k['fase']=='C'?'selected':'' ?>>Fase C (Kelas 5-6)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Guru Kelas</label>
                                                <input type="text" name="wali_kelas" class="form-control" value="<?= $k['wali_kelas'] ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">NIP Guru</label>
                                                <input type="text" name="nip_wali" class="form-control" value="<?= $k['nip_wali'] ?>">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Kelas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('datamaster/kelas/store') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas (Misal: Kelas 1A)</label>
                        <input type="text" name="nama_kelas" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fase</label>
                        <select name="fase" class="form-select">
                            <option value="A">Fase A (Kelas 1-2)</option>
                            <option value="B">Fase B (Kelas 3-4)</option>
                            <option value="C">Fase C (Kelas 5-6)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Guru Kelas</label>
                        <input type="text" name="wali_kelas" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIP Guru</label>
                        <input type="text" name="nip_wali" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
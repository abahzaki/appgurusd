<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Mata Pelajaran (Mapel)</h1>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Mapel
        </button>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No. Urut</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Kode</th>
                            <th>Kelompok</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($mapel as $m): ?>
                        <tr>
                            <td class="text-center"><?= $m['no_urut'] ?></td>
                            <td class="fw-bold"><?= $m['nama_mapel'] ?></td>
                            <td><?= $m['kode_mapel'] ?></td>
                            <td>
                                <?php if($m['kelompok'] == 'A'): ?>
                                    <span class="badge bg-primary">Kelompok A (Wajib)</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Kelompok B (Mulok)</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $m['id'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="<?= base_url('datamaster/mapel/delete/'.$m['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus Mapel ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEdit<?= $m['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title">Edit Mapel</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?= base_url('datamaster/mapel/update/'.$m['id']) ?>" method="post">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Nama Mapel</label>
                                                <input type="text" name="nama_mapel" class="form-control" value="<?= $m['nama_mapel'] ?>" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label>Kode (Singkatan)</label>
                                                    <input type="text" name="kode_mapel" class="form-control" value="<?= $m['kode_mapel'] ?>">
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label>No. Urut Rapor</label>
                                                    <input type="number" name="no_urut" class="form-control" value="<?= $m['no_urut'] ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label>Kelompok</label>
                                                <select name="kelompok" class="form-select">
                                                    <option value="A" <?= $m['kelompok']=='A'?'selected':'' ?>>A (Wajib)</option>
                                                    <option value="B" <?= $m['kelompok']=='B'?'selected':'' ?>>B (Mulok/Pilihan)</option>
                                                </select>
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
                <h5 class="modal-title">Tambah Mapel Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('datamaster/mapel/store') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Mapel</label>
                        <input type="text" name="nama_mapel" class="form-control" required placeholder="Contoh: Bahasa Madura">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>Kode (Singkatan)</label>
                            <input type="text" name="kode_mapel" class="form-control" placeholder="MADURA">
                        </div>
                        <div class="col-6 mb-3">
                            <label>No. Urut Rapor</label>
                            <input type="number" name="no_urut" class="form-control" value="10">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Kelompok</label>
                        <select name="kelompok" class="form-select">
                            <option value="A">A (Wajib)</option>
                            <option value="B" selected>B (Mulok/Pilihan)</option>
                        </select>
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
<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bank Tujuan Pembelajaran (TP)</h1>
        
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalBuatTP">
            <i class="bi bi-plus-circle me-2"></i> Susun TP Baru
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white border-bottom-primary">
            <h6 class="m-0 font-weight-bold text-primary">Daftar TP Tersimpan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light text-center text-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="35%">Mata Pelajaran</th>
                            <th width="20%">Fase / Kelas</th>
                            <th width="20%">Jumlah TP</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($riwayat)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder2-open display-4 d-block mb-3"></i>
                                    Belum ada data TP yang disusun.<br>
                                    Silakan klik tombol <b>"Susun TP Baru"</b> di pojok kanan atas.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no=1; foreach($riwayat as $row): ?>
                            
                            <?php 
                                // --- LOGIKA PENYELAMAT FASE ---
                                // Ambil fase yang valid (Entah dari DB atau dari Data Guru)
                                $fase_fix = !empty($row['fase']) ? $row['fase'] : ($kelas_guru['fase'] ?? 'A');
                            ?>
                            
                            <tr>
                                <td class="text-center align-middle"><?= $no++ ?></td>
                                <td class="align-middle font-weight-bold text-dark">
                                    <?= $row['nama_mapel'] ?>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge bg-info text-dark border border-dark">Fase <?= $fase_fix ?></span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge bg-secondary"><?= $row['jumlah_tp'] ?> Butir</span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group" role="group">
                                        <form action="<?= base_url('cetak_tp/susun') ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="mapel_id" value="<?= $row['mapel_id'] ?>">
                                            <input type="hidden" name="fase" value="<?= $fase_fix ?>">
                                            
                                            <button type="submit" class="btn btn-warning btn-sm text-dark shadow-sm" title="Edit TP">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                        </form>

                                        <a href="<?= base_url('cetak_tp/delete/'.$row['mapel_id']) ?>" 
                                           class="btn btn-danger btn-sm text-white shadow-sm ms-1" 
                                           onclick="return confirm('PERINGATAN!\n\nApakah Anda yakin ingin MENGHAPUS seluruh TP untuk Mapel ini?')"
                                           title="Hapus Data">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBuatTP" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="bi bi-file-earmark-plus-fill me-2"></i> Susun TP Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="<?= base_url('cetak_tp/susun') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    
                    <div class="alert alert-info small mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i> 
                        Silakan pilih Mata Pelajaran. Fase akan otomatis disesuaikan dengan kelas Anda.
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Mata Pelajaran</label>
                        <select name="mapel_id" class="form-select" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            <?php foreach($mapel as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Fase</label>
                        
                        <?php if(!empty($kelas_guru) && !empty($kelas_guru['fase'])): ?>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-check-circle-fill"></i></span>
                                <input type="text" class="form-control bg-light text-success font-weight-bold" 
                                       value="Fase <?= $kelas_guru['fase'] ?> (Kelas <?= $kelas_guru['nama_kelas'] ?>)" readonly>
                            </div>
                            <input type="hidden" name="fase" value="<?= $kelas_guru['fase'] ?>">
                            <small class="text-muted fst-italic mt-1 d-block">Otomatis disesuaikan dengan data kelas Anda.</small>
                        
                        <?php else: ?>
                            <select name="fase" class="form-select border-warning" required>
                                <option value="">-- Pilih Fase Manual --</option>
                                <option value="A">Fase A (Kelas 1-2)</option>
                                <option value="B">Fase B (Kelas 3-4)</option>
                                <option value="C">Fase C (Kelas 5-6)</option>
                            </select>
                            <small class="text-warning fw-bold mt-1 d-block">
                                <i class="bi bi-exclamation-triangle"></i> Anda belum disetting sebagai Wali Kelas. Harap pilih manual.
                            </small>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary shadow">
                        Lanjut <i class="bi bi-arrow-right-circle-fill ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
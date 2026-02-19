<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bank ATP (Alur Tujuan Pembelajaran)</h1>
        
        <?php if($kelas): ?>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalGenerate">
            <i class="bi bi-stars me-2"></i> Generate ATP (AI)
        </button>
        <?php else: ?>
            <div class="alert alert-warning py-1"><small>Anda belum diatur sebagai Wali Kelas.</small></div>
        <?php endif; ?>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white"><h6 class="m-0 font-weight-bold text-primary">Riwayat ATP Tersimpan</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-light"><tr><th>No</th><th>Mapel</th><th>Kelas</th><th>Dibuat</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php if(empty($history)): ?>
                            <tr><td colspan="5" class="text-center">Belum ada ATP. Silakan Generate.</td></tr>
                        <?php else: ?>
                            <?php $no=1; foreach($history as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="fw-bold"><?= $row['nama_mapel'] ?></td>
                                <td><?= $row['nama_kelas'] ?></td>
                                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('cetak_atp/download/'.$row['id']) ?>" class="btn btn-success btn-sm"><i class="bi bi-download"></i> Word</a>
                                    <a href="<?= base_url('cetak_atp/download_promes/'.$row['id']) ?>" class="btn btn-primary btn-sm" title="Download Program Semester">
                                        <i class="bi bi-calendar-week"></i> Promes
                                    </a>
                                    <a href="<?= base_url('cetak_atp/delete/'.$row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
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

<div class="modal fade" id="modalGenerate" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-stars"></i> Generate ATP Otomatis</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="<?= base_url('cetak_atp/generate_ai') ?>" method="post" id="formGenerateATP">
                <div class="modal-body">
                    <?php if(empty($mapel_tersedia)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-circle text-warning display-4 mb-3"></i>
                            <h5 class="text-gray-800">Belum Ada Data TP!</h5>
                            <p class="small text-muted mb-3">
                                Anda belum menyimpan Tujuan Pembelajaran (TP) untuk mapel apapun.<br>
                                AI membutuhkan data TP sebagai bahan baku.
                            </p>
                            <a href="<?= base_url('cetak_tp') ?>" class="btn btn-warning shadow-sm">
                                <i class="bi bi-arrow-left"></i> Kembali ke Menu Cetak TP
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-info-circle-fill"></i> 
                            AI akan menganalisis TP yang tersimpan dan memecahnya menjadi langkah pembelajaran kecil.
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Pilih Mata Pelajaran</label>
                            <select name="mapel_id" class="form-control" required>
                                <option value="">-- Pilih Mapel --</option>
                                <?php foreach($mapel_tersedia as $m): ?>
                                    <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary shadow">
                                <i class="bi bi-magic me-2"></i> Mulai Proses AI
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#formGenerateATP').on('submit', function() {
            // Tutup Modal Bootstrap dulu
            $('#modalGenerate').modal('hide');

            // Tampilkan Loading SweetAlert
            Swal.fire({
                title: 'AI Sedang Bekerja...',
                html: 'Mohon tunggu, AI sedang membedah TP menjadi Sub-ATP.<br><small class="text-muted">Proses ini memakan waktu 5-15 detik.</small>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
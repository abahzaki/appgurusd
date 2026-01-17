<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<style>
    /* CSS Tambahan agar tabel bisa digeser ke samping dengan rapi */
    .table-responsive {
        overflow-x: auto;
    }
    .table th, .table td {
        white-space: nowrap; /* Teks tidak akan turun baris (memanjang) */
        vertical-align: middle;
        font-size: 0.85rem; /* Ukuran font sedikit dikecilkan biar muat */
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Data Siswa (Master Lengkap)</h1>
        <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
            <i class="bi bi-file-earmark-spreadsheet"></i> Import Excel
        </button>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center bg-primary sticky-left">AKSI</th> 
                            <th>KELAS</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>NAMA LENGKAP</th>
                            <th>L/P</th>
                            <th>TEMPAT LAHIR</th>
                            <th>TGL LAHIR</th>
                            <th>AGAMA</th>
                            <th>PENDIDIKAN SEBELUMNYA</th>
                            <th>ALAMAT SISWA</th>
                            <th>DESA</th>
                            <th>KECAMATAN</th>
                            <th>KOTA/KAB</th>
                            <th>PROPINSI</th>
                            <th>NAMA AYAH</th>
                            <th>PEKERJAAN AYAH</th>
                            <th>NAMA IBU</th>
                            <th>PEKERJAAN IBU</th>
                            <th>NAMA WALI</th>
                            <th>PEKERJAAN WALI</th>
                            <th>ALAMAT WALI</th>
                            <th>NO TELP</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($siswa as $s): ?>
                        <tr>
                            <td class="text-center sticky-left bg-white">
                                <div class="btn-group">
                                    <a href="<?= base_url('datamaster/siswa/edit/'.$s['id']) ?>" class="btn btn-xs btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <a href="<?= base_url('datamaster/siswa/delete/'.$s['id']) ?>" class="btn btn-xs btn-danger" onclick="return confirm('Yakin hapus?')" title="Hapus"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>

                            <td>
                        <?php if($s['nama_kelas']): ?>
                        <span class="badge bg-info text-dark"><?= $s['nama_kelas'] ?></span>
                        <br><small class="text-muted">Fase <?= $s['fase'] ?></small>
                            <?php else: ?>
                                <span class="badge bg-secondary text-white-50" style="font-size: 0.7em;">Belum Masuk Kelas</span>
                                <?php endif; ?>
                            </td>
                            
                            <td><?= $s['nis'] ?></td>
                            <td><?= $s['nisn'] ?></td>
                            <td class="fw-bold"><?= $s['nama_lengkap'] ?></td>
                            <td><?= $s['jenis_kelamin'] ?></td>
                            <td><?= $s['tempat_lahir'] ?></td>
                            <td><?= $s['tanggal_lahir'] ?></td>
                            <td><?= $s['agama'] ?></td>
                            <td><?= $s['pendidikan_sebelumnya'] ?></td>
                            <td><?= $s['alamat_peserta_didik'] ?></td>
                            <td><?= $s['desa'] ?></td>
                            <td><?= $s['kecamatan'] ?></td>
                            <td><?= $s['kota'] ?></td>
                            <td><?= $s['propinsi'] ?></td>
                            <td><?= $s['nama_ayah'] ?></td>
                            <td><?= $s['pekerjaan_ayah'] ?></td>
                            <td><?= $s['nama_ibu'] ?></td>
                            <td><?= $s['pekerjaan_ibu'] ?></td>
                            <td><?= $s['nama_wali'] ?></td>
                            <td><?= $s['pekerjaan_wali'] ?></td>
                            <td><?= $s['alamat_wali'] ?></td>
                            <td><?= $s['no_telephone'] ?></td>
                            <td>
                                <?php if($s['status_aktif'] == 1): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-2 text-muted small">
                <i class="bi bi-info-circle"></i> Geser tabel ke kanan untuk melihat data lengkap.
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Import Data Siswa (Format Baru)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('datamaster/siswa/import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info small">
                        <strong>Urutan Kolom Excel (A-V):</strong><br>
                        NIS, NISN, Nama, JK, Tmpt Lahir, Tgl Lahir, Agama, Pend. Sblm, Alamat, Desa, Kec, Kota, Prov, Ayah, Ibu, Pek. Ayah, Pek. Ibu, Wali, Pek. Wali, Alamat Wali, Telp, Status, Nama Kelas.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Excel (.xlsx)</label>
                        <input type="file" name="file_excel" class="form-control" required accept=".xlsx, .xls">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
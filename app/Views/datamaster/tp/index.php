<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Tujuan Pembelajaran (TP)</h1>
                <div class="d-flex gap-2">
                    <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalBankTP">
                        <i class="bi bi-cloud-download"></i> Ambil dari Bank TP
                    </button>
                    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                         <i class="bi bi-plus-circle"></i> Tambah Manual
                    </button>
                </div>
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
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>Kode TP</th>
                            <th>Deskripsi Tujuan Pembelajaran</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tp as $item): ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?= $item['nama_kelas'] ?></span></td>
                            <td class="fw-bold"><?= $item['nama_mapel'] ?></td>
                            <td class="text-center"><?= $item['kode_tp'] ?></td>
                            <td><?= $item['deskripsi_tp'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $item['id'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="<?= base_url('datamaster/tp/delete/'.$item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus TP ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEdit<?= $item['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title">Edit TP</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?= base_url('datamaster/tp/update/'.$item['id']) ?>" method="post">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label>Pilih Mapel</label>
                                                    <select name="mapel_id" class="form-select">
                                                        <?php foreach($mapel as $m): ?>
                                                            <option value="<?= $m['id'] ?>" <?= $m['id']==$item['mapel_id']?'selected':'' ?>><?= $m['nama_mapel'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label>Pilih Kelas</label>
                                                    <select name="kelas_id" class="form-select">
                                                        <?php foreach($kelas as $k): ?>
                                                            <option value="<?= $k['id'] ?>" <?= $k['id']==$item['kelas_id']?'selected':'' ?>><?= $k['nama_kelas'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label>Kode TP (Misal: TP.1)</label>
                                                <input type="text" name="kode_tp" class="form-control" value="<?= $item['kode_tp'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Deskripsi (Maks 100 karakter disarankan)</label>
                                                <textarea name="deskripsi_tp" class="form-control" rows="3" required><?= $item['deskripsi_tp'] ?></textarea>
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
                <h5 class="modal-title">Tambah TP Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('datamaster/tp/store') ?>" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>Pilih Mapel</label>
                            <select name="mapel_id" class="form-select">
                                <?php foreach($mapel as $m): ?>
                                    <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label>Pilih Kelas</label>
                            <select name="kelas_id" class="form-select">
                                <?php foreach($kelas as $k): ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama_kelas'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Kode TP (Misal: TP.1)</label>
                        <input type="text" name="kode_tp" class="form-control" required placeholder="TP.1">
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi (Misal: Peserta didik mampu...)</label>
                        <textarea name="deskripsi_tp" class="form-control" rows="3" required placeholder="Peserta didik mampu melakukan operasi hitung penjumlahan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBankTP" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-robot"></i> Generator TP Otomatis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('datamaster/tp/proses_ambil') ?>" method="post">
                <div class="modal-body">
                    <div class="alert alert-info small">
                        Pilih Kelas & Mapel, lalu <b>centang</b> TP yang ingin digunakan.
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label>Pilih Kelas Tujuan</label>
                            <select name="kelas_id" id="bank_kelas_id" class="form-select" required>
                                <option value="">- Pilih Kelas -</option>
                                <?php foreach($kelas as $k): ?>
                                    <option value="<?= $k['id'] ?>" data-fase="<?= $k['fase'] ?>">
                                        <?= $k['nama_kelas'] ?> (Fase <?= $k['fase'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label>Pilih Mapel</label>
                            <select name="mapel_id" id="bank_mapel_id" class="form-select" required>
                                <option value="">- Pilih Mapel -</option>
                                <?php foreach($mapel as $m): ?>
                                    <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" id="btnCariTP" class="btn btn-secondary w-100">Cari</button>
                        </div>
                    </div>

                    <div class="table-responsive border rounded" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="10%" class="text-center">Pilih</th>
                                    <th width="20%">Kode</th>
                                    <th>Deskripsi TP</th>
                                </tr>
                            </thead>
                            <tbody id="daftar_tp_bank">
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Silakan pilih Kelas & Mapel lalu klik Cari.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-download"></i> Simpan TP Terpilih
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnCariTP').addEventListener('click', function() {
        var kelasSelect = document.getElementById('bank_kelas_id');
        var mapelId = document.getElementById('bank_mapel_id').value;
        
        // Ambil Fase dari atribut data-fase di option kelas
        var fase = kelasSelect.options[kelasSelect.selectedIndex].getAttribute('data-fase');

        if(!fase || !mapelId) {
            alert('Mohon pilih Kelas dan Mapel terlebih dahulu!');
            return;
        }

        // Tampilkan Loading
        document.getElementById('daftar_tp_bank').innerHTML = '<tr><td colspan="3" class="text-center">Sedang mencari data...</td></tr>';

        // Panggil Controller via Fetch API
        fetch('<?= base_url('datamaster/tp/get_referensi') ?>?mapel_id=' + mapelId + '&fase=' + fase)
        .then(response => response.text())
        .then(data => {
            document.getElementById('daftar_tp_bank').innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('daftar_tp_bank').innerHTML = '<tr><td colspan="3" class="text-center text-danger">Gagal mengambil data.</td></tr>';
        });
    });
</script>

<?= $this->endSection() ?>
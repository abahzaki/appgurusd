<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid mb-5">
    <h1 class="h3 mb-4 text-gray-800">Catatan Wali Kelas & Absensi</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="" method="get">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label class="fw-bold">Pilih Kelas</label>
                        <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Pilih Kelas --</option>
                            <?php 
                                $nama_kelas_aktif = ""; // Variabel penampung nama kelas
                                foreach($kelas as $k): 
                                    if($kelas_id == $k['id']) {
                                        $nama_kelas_aktif = $k['nama_kelas'];
                                    }
                            ?>
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

    <?php 
        // LOGIKA PENENTU KELAS AKHIR (KELAS 6)
        // Kita cek apakah nama kelas mengandung angka '6' atau romawi 'VI'
        $is_kelas_akhir = false;
        if($nama_kelas_aktif) {
            $nama_upper = strtoupper($nama_kelas_aktif);
            if(strpos($nama_upper, 'VI') !== false || strpos($nama_upper, '6') !== false) {
                $is_kelas_akhir = true;
            }
        }
    ?>

    <?php if($kelas_id && !empty($siswa)): ?>
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if($is_kelas_akhir): ?>
        <div class="alert alert-info py-2 small">
            <i class="bi bi-info-circle"></i> Terdeteksi sebagai <b>Kelas Akhir (Kelas 6)</b>. Opsi kenaikan diset menjadi <b>Lulus / Tidak Lulus</b>.
        </div>
    <?php endif; ?>

    <form action="<?= base_url('catatan/simpan') ?>" method="post">
        <input type="hidden" name="kelas_id" value="<?= $kelas_id ?>">

        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold">Input Data Akhir Semester</h6>
                <button type="submit" class="btn btn-warning btn-sm fw-bold text-dark">
                    <i class="bi bi-save"></i> SIMPAN SEMUA DATA
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th width="3%" rowspan="2">No</th>
                                <th width="20%" rowspan="2">Nama Siswa</th>
                                <th colspan="3">Absensi (Hari)</th>
                                <th rowspan="2">Status Akhir</th>
                                <th width="30%" rowspan="2">Catatan Wali Kelas</th>
                            </tr>
                            <tr>
                                <th width="7%">Sakit</th>
                                <th width="7%">Izin</th>
                                <th width="7%">Alpha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($siswa as $s): ?>
                            <?php 
                                $d = isset($catatan_map[$s['id']]) ? $catatan_map[$s['id']] : [];
                                $status_saat_ini = $d['status_naik'] ?? '';
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="fw-bold"><?= $s['nama_lengkap'] ?></td>
                                
                                <td class="p-1">
                                    <input type="number" name="data[<?= $s['id'] ?>][sakit]" class="form-control text-center" value="<?= $d['sakit'] ?? 0 ?>">
                                </td>
                                <td class="p-1">
                                    <input type="number" name="data[<?= $s['id'] ?>][izin]" class="form-control text-center" value="<?= $d['izin'] ?? 0 ?>">
                                </td>
                                <td class="p-1">
                                    <input type="number" name="data[<?= $s['id'] ?>][alpha]" class="form-control text-center" value="<?= $d['alpha'] ?? 0 ?>">
                                </td>

                                <td class="p-1">
                                    <select name="data[<?= $s['id'] ?>][status_naik]" class="form-select text-center fw-bold text-primary">
                                        
                                        <?php if($is_kelas_akhir): ?>
                                            <option value="Lulus" <?= ($status_saat_ini == 'Lulus' || $status_saat_ini == '') ? 'selected' : '' ?>>Lulus</option>
                                            <option value="Tidak Lulus" <?= ($status_saat_ini == 'Tidak Lulus') ? 'selected' : '' ?>>Tidak Lulus</option>
                                        
                                        <?php else: ?>
                                            <option value="Naik Kelas" <?= ($status_saat_ini == 'Naik Kelas' || $status_saat_ini == '') ? 'selected' : '' ?>>Naik Kelas</option>
                                            <option value="Tinggal Kelas" <?= ($status_saat_ini == 'Tinggal Kelas') ? 'selected' : '' ?>>Tinggal Kelas</option>
                                        
                                        <?php endif; ?>

                                    </select>
                                </td>

                                <td class="p-2">
                                    <textarea name="data[<?= $s['id'] ?>][catatan]" class="form-control" rows="2" placeholder="Tulis pesan motivasi..."><?= $d['catatan'] ?? '' ?></textarea>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <button type="submit" class="btn btn-primary w-100 btn-lg shadow">
                    <i class="bi bi-save"></i> SIMPAN PERUBAHAN
                </button>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
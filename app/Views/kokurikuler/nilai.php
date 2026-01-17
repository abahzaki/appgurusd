<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<style>
    /* Agar tabel bisa di-scroll horizontal tapi nama siswa tetap diam (Sticky) */
    .table-responsive { overflow-x: auto; }
    .sticky-col { 
        position: sticky; left: 0; background-color: #fff; z-index: 10; 
        border-right: 2px solid #ddd;
    }
    .min-w-150 { min-width: 150px; }
</style>

<div class="container-fluid mb-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Skor: <?= $projek['nama_projek'] ?></h1>
        <a href="<?= base_url('kokurikuler?kelas_id='.$projek['kelas_id']) ?>" class="btn btn-secondary btn-sm shadow-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <form action="<?= base_url('kokurikuler/simpan_nilai') ?>" method="post">
        <input type="hidden" name="kokurikuler_id" value="<?= $projek['id'] ?>">

        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Lembar Penilaian Projek</h6>
                <button type="submit" class="btn btn-warning fw-bold text-dark"><i class="bi bi-save"></i> SIMPAN NILAI</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" style="font-size: 0.9rem;">
                        <thead class="table-dark text-center">
                            <tr>
                                <th rowspan="2" class="align-middle sticky-col" style="z-index: 20;">Nama Siswa</th>
                                <?php foreach($dimensi as $d): ?>
                                    <th class="min-w-150 py-3"><?= $d['nama_dimensi'] ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($siswa as $s): ?>
                            <tr>
                                <td class="sticky-col fw-bold text-nowrap align-middle">
                                    <?= $s['nama_lengkap'] ?>
                                </td>

                                <?php foreach($dimensi as $d): ?>
                                    <?php 
                                        // Ambil nilai lama
                                        $val = $nilai_map[$s['id']][$d['id']] ?? 0;
                                    ?>
                                    <td class="p-1">
                                        <select name="skor[<?= $s['id'] ?>][<?= $d['id'] ?>]" class="form-select form-select-sm border-0 text-center fw-bold" 
                                                style="background-color: <?= ($val > 0) ? '#e8f5e9' : '#fff' ?>;">
                                            <option value="0" class="text-muted">-</option>
                                            <option value="1" <?= ($val==1)?'selected':'' ?> style="color:red;">BB (Belum)</option>
                                            <option value="2" <?= ($val==2)?'selected':'' ?> style="color:orange;">MB (Mulai)</option>
                                            <option value="3" <?= ($val==3)?'selected':'' ?> style="color:blue;">BSH (Sesuai)</option>
                                            <option value="4" <?= ($val==4)?'selected':'' ?> style="color:green;">SB (Sangat)</option>
                                        </select>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-center">
                <small class="text-muted">BB = Belum Berkembang | MB = Mulai Berkembang | BSH = Berkembang Sesuai Harapan | SB = Sangat Berkembang</small>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
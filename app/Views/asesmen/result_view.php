<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .nav-tabs .nav-link { background-color: #f1f3f5; color: #495057; border: 1px solid #dee2e6; margin-right: 4px; font-weight: 500; }
    .nav-tabs .nav-link.active { background-color: #fff !important; color: #0d6efd !important; border-top: 3px solid #0d6efd; border-bottom-color: transparent; font-weight: bold; }
    .editable-input { border: 1px dashed #ced4da; background: #fafafa; width: 100%; padding: 5px; border-radius: 4px; }
    .editable-input:focus { border: 1px solid #86b7fe; outline: 0; background: #fff; }
    .form-label-sm { font-size: 0.85rem; color: #6c757d; margin-bottom: 2px; }
</style>

<div class="container-fluid px-4">
    <form action="<?= base_url('asesmen/updateResult/' . $id_asesmen) ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h3 class="text-primary"><i class="bi bi-file-earmark-text"></i> Editor Soal AI</h3>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('asesmen') ?>">Bank Soal</a></li>
                    <li class="breadcrumb-item active"><?= $judul ?></li>
                </ol>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('asesmen') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
                <a href="<?= base_url('asesmen/exportWord/' . $id_asesmen) ?>" class="btn btn-success"><i class="bi bi-file-word"></i> Export Word</a>
            </div>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs" id="editorTab" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#soal" type="button">Edit Naskah Soal</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#kisi" type="button">Edit Kisi-Kisi</button></li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    
                    <div class="tab-pane fade show active" id="soal" role="tabpanel">
                        <?php if(!empty($data['soal'])): ?>
                            <?php foreach ($data['soal'] as $index => $row): ?>
                                <div class="card mb-3 border-0 shadow-sm bg-light">
                                    <div class="card-body">
                                        <div class="d-flex gap-3">
                                            <div class="fw-bold pt-2 fs-5 text-secondary"><?= $row['nomor'] ?>.</div>
                                            <div class="w-100">
                                                <input type="hidden" name="soal_nomor[]" value="<?= $row['nomor'] ?>">
                                                <input type="hidden" name="soal_bentuk[]" value="<?= $row['bentuk'] ?>">
                                                
                                                <label class="form-label-sm">Pertanyaan:</label>
                                                <textarea name="soal_tanya[]" class="editable-input mb-2" rows="2"><?= $row['pertanyaan'] ?></textarea>
                                                
                                                <?php if ($row['bentuk'] == 'PG' && !empty($row['opsi'])): ?>
                                                    <div class="row g-2">
                                                        <?php foreach (['A','B','C','D'] as $opt): ?>
                                                            <div class="col-md-6"><div class="input-group input-group-sm"><span class="input-group-text fw-bold"><?= $opt ?></span><input type="text" name="soal_opsi_<?= strtolower($opt) ?>[]" class="form-control" value="<?= $row['opsi'][$opt] ?? '' ?>"></div></div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <input type="hidden" name="soal_opsi_a[]" value=""><input type="hidden" name="soal_opsi_b[]" value=""><input type="hidden" name="soal_opsi_c[]" value=""><input type="hidden" name="soal_opsi_d[]" value="">
                                                <?php endif; ?>

                                                <div class="mt-2 d-flex align-items-center gap-2">
                                                    <label class="form-label-sm fw-bold mb-0 text-success">Kunci:</label>
                                                    <input type="text" name="soal_kunci[]" class="form-control form-control-sm w-25 border-success" value="<?= $row['kunci'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="kisi" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Capaian Pembelajaran (CP)</th> <th width="20%">TP (Kode & Deskripsi)</th>
                                        <th width="15%">Materi</th>
                                        <th width="30%">Indikator Soal</th>
                                        <th width="10%">Bentuk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($data['kisi_kisi'])): ?>
                                        <?php foreach ($data['kisi_kisi'] as $index => $k): ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="text" name="kisi_nomor[]" class="form-control form-control-sm text-center" value="<?= $k['nomor_soal'] ?>">
                                            </td>
                                            <td>
                                                <textarea name="kisi_cp[]" class="form-control form-control-sm" rows="4" placeholder="Tulis CP disini..."><?= $k['cp'] ?? '-' ?></textarea>
                                            </td>
                                            <td>
                                                <input type="text" name="kisi_tp_kode[]" class="form-control form-control-sm mb-1 fw-bold" value="<?= $k['tp_kode'] ?>">
                                                <textarea name="kisi_tp_desc[]" class="form-control form-control-sm" rows="3"><?= $k['tp_deskripsi'] ?></textarea>
                                            </td>
                                            <td>
                                                <textarea name="kisi_materi[]" class="form-control form-control-sm" rows="3"><?= $k['materi'] ?></textarea>
                                            </td>
                                            <td>
                                                <textarea name="kisi_indikator[]" class="form-control form-control-sm" rows="3"><?= $k['indikator_soal'] ?></textarea>
                                            </td>
                                            <td>
                                                <input type="text" name="kisi_bentuk[]" class="form-control form-control-sm text-center" value="<?= $k['bentuk_soal'] ?>">
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
        </div>
    </form>
</div>
<?= $this->endSection(); ?>
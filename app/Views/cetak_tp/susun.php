<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Susun TP: <?= $mapel['nama_mapel'] ?> (Fase <?= $fase ?>)</h1>
        <a href="<?= base_url('cetak_tp') ?>" class="btn btn-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="<?= base_url('cetak_tp/simpan_dan_export') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="mapel_id" value="<?= $mapel['id'] ?>">
        <input type="hidden" name="fase" value="<?= $fase ?>">

        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-warning text-dark">
                <h6 class="m-0 font-weight-bold"><i class="bi bi-info-circle-fill me-2"></i> Petunjuk Pengisian:</h6>
                <small class="d-block mt-1">
                    1. <b>Centang (✔)</b> Tujuan Pembelajaran (TP) dari referensi sistem yang sesuai dengan rencana Anda.<br>
                    2. Jika ingin menambahkan TP buatan sendiri, ketik pada kolom <b>"TP Mandiri"</b> (Gunakan tombol Enter untuk memisahkan poin).
                </small>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="bg-light text-dark text-center">
                            <tr>
                                <th style="width: 20%; vertical-align: middle;">Elemen</th>
                                <th style="width: 35%; vertical-align: middle;">Capaian Pembelajaran (CP)</th>
                                <th style="width: 45%; vertical-align: middle;">Pilih / Ketik Tujuan Pembelajaran (TP)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data_cp)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-danger font-italic py-4">
                                        <i class="bi bi-exclamation-triangle"></i> Data CP belum tersedia untuk Mapel/Fase ini.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($data_cp as $cp): ?>
                                <tr>
                                    <td class="font-weight-bold align-middle bg-light">
                                        <?= $cp['elemen'] ?>
                                    </td>

                                    <td class="align-middle text-justify small">
                                        <?= $cp['deskripsi_cp'] ?>
                                    </td>

                                    <td class="align-middle">
                                        
                                        <div class="card border-left-primary shadow-sm mb-3">
                                            <div class="card-header py-1 bg-light">
                                                <small class="font-weight-bold text-primary">A. Referensi Sistem:</small>
                                            </div>
                                            <div class="card-body p-2" style="max-height: 250px; overflow-y: auto;">
                                                <?php $tp_tersedia = false; ?>

                                                <?php foreach($data_tp as $tp): ?>
                                                    <?php 
                                                        // --- LOGIKA EDIT: CEK APAKAH SUDAH TERSIMPAN ---
                                                        // Menggunakan $saved_tp yang dikirim dari Controller
                                                        $isChecked = (isset($saved_tp) && in_array($tp['deskripsi_tp'], $saved_tp)) ? 'checked' : '';
                                                        $labelClass = $isChecked ? 'fw-bold text-primary' : 'text-dark';
                                                    ?>
                                                    
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" 
                                                               id="tp_<?= $cp['id'] ?>_<?= $tp['id'] ?>" 
                                                               name="pilihan_tp[<?= $cp['id'] ?>][]" 
                                                               value="<?= htmlspecialchars($tp['deskripsi_tp']) ?>"
                                                               <?= $isChecked ?>>
                                                        
                                                        <label class="form-check-label small <?= $labelClass ?>" for="tp_<?= $cp['id'] ?>_<?= $tp['id'] ?>" style="cursor: pointer;">
                                                            <?= $tp['deskripsi_tp'] ?>
                                                        </label>
                                                    </div>
                                                    <?php $tp_tersedia = true; ?>
                                                <?php endforeach; ?>

                                                <?php if(!$tp_tersedia): ?>
                                                    <div class="small text-muted font-italic text-center">Tidak ada referensi TP tersedia.</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="card border-left-success shadow-sm">
                                            <div class="card-header py-1 bg-light">
                                                <small class="font-weight-bold text-success">B. TP Mandiri (Opsional):</small>
                                            </div>
                                            <div class="card-body p-2">
                                                <textarea name="tp_mandiri[<?= $cp['id'] ?>]" 
                                                          class="form-control form-control-sm border-0 bg-light" 
                                                          rows="3" 
                                                          placeholder="Ketik TP rumusan sendiri di sini... (Tekan Enter untuk baris baru)"></textarea>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white text-end">
                <button type="submit" class="btn btn-success btn-lg shadow-sm px-4">
                    <i class="bi bi-save2-fill me-2"></i> Simpan & Download TP
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
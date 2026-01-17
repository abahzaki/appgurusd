<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Editor Modul Ajar</h1>
        <div>
            <a href="<?= base_url('modulajar') ?>" class="btn btn-secondary btn-sm shadow-sm mr-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="<?= base_url('modulajar/word/' . $modul['id']) ?>" class="btn btn-primary btn-sm shadow-sm">
                <i class="bi bi-file-earmark-word-fill"></i> Export Word
            </a>
        </div>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill mr-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill mr-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('modulajar/update/' . $modul['id']) ?>" method="post" id="formEditor">
        <?= csrf_field(); ?>
        
        <div class="card shadow mb-3 border-left-primary">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Materi Pokok</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($modul['materi']) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-book-half fa-2x text-gray-300" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="utama-tab" data-toggle="tab" href="#utama" role="tab">
                    <i class="bi bi-layers-fill mr-1"></i> 1. Modul Utama
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="materi-tab" data-toggle="tab" href="#materi" role="tab">
                    <i class="bi bi-file-earmark-text-fill mr-1"></i> 2. Materi & LKPD
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="asesmen-tab" data-toggle="tab" href="#asesmen" role="tab">
                    <i class="bi bi-clipboard-check-fill mr-1"></i> 3. Asesmen & Soal
                </a>
            </li>
        </ul>

        <div class="tab-content pt-4" id="myTabContent">
            
            <div class="tab-pane fade show active" id="utama" role="tabpanel">
                
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Komponen Inti Modul</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Tujuan Pembelajaran</label>
                            <textarea name="tujuan_pembelajaran" class="form-control" rows="3"><?= esc($modul['tujuan_pembelajaran']) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Identifikasi Murid</label>
                            <textarea name="identifikasi_murid" class="form-control" rows="3"><?= esc($modul['identifikasi_murid']) ?></textarea>
                            <small class="text-muted">Jelaskan keragaman kebutuhan belajar murid (Kesiapan, Minat, Profil Belajar).</small>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Pemahaman Bermakna & Pertanyaan Pemantik</label>
                            <textarea name="pemahaman_bermakna" class="form-control" rows="3"><?= $modul['pemahaman_bermakna'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Langkah Kegiatan Inti (Deep Learning)</label>
                            <textarea id="summernote_kegiatan" name="kegiatan_inti"><?= $modul['kegiatan_inti'] ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 border-left-warning shadow h-100 py-3 bg-white">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    <i class="bi bi-stars"></i> AI Assistant (Tahap 2)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Generate Lampiran Otomatis</div>
                                <small class="text-muted">Klik tombol ini jika ingin AI membuatkan <b>LKPD, Soal Pilihan Ganda, Uraian, dan Rubrik</b> secara instan. (Data lama di Tab 2 & 3 akan tertimpa).</small>
                            </div>
                            <div class="col-auto">
                                <button type="button" onclick="submitGenerateLampiran()" class="btn btn-warning text-dark font-weight-bold shadow-sm">
                                    <i class="bi bi-magic mr-1"></i> Generate Lampiran
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="materi" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">Bahan Bacaan (Utama)</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="summernote_materi" name="media_pembelajaran"><?= $modul['media_pembelajaran'] ?></textarea>
                    </div>
                </div>
                
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-info text-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">Lampiran LKPD & Materi Tambahan</h6>
                        <span class="badge badge-light text-info">Hasil Generate AI</span>
                    </div>
                    <div class="card-body">
                        <label class="font-weight-bold">Lembar Kerja Peserta Didik (LKPD):</label>
                        <textarea id="summernote_lkpd" name="lampiran_lkpd"><?= $modul['lampiran_lkpd'] ?></textarea>
                        <br>
                        <label class="font-weight-bold">Materi Bacaan Tambahan:</label>
                        <textarea id="summernote_lampiran_materi" name="lampiran_materi"><?= $modul['lampiran_materi'] ?></textarea>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="asesmen" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-success text-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">Instrumen Penilaian</h6>
                        <span class="badge badge-light text-success">Sumatif & Formatif</span>
                    </div>
                    <div class="card-body">
                        <label class="font-weight-bold">Ringkasan Teknik Asesmen (Utama):</label>
                        <textarea id="summernote_asesmen" name="asesmen_sumatif"><?= $modul['asesmen_sumatif'] ?></textarea>
                        <hr>
                        <label class="font-weight-bold text-success">Lampiran Soal Lengkap & Kunci Jawaban (Hasil AI):</label>
                        <textarea id="summernote_lampiran_asesmen" name="lampiran_asesmen"><?= $modul['lampiran_asesmen'] ?></textarea>
                    </div>
                </div>
            </div>

        </div>

        <div style="height: 100px;"></div>

        <div class="card shadow-lg fixed-bottom" style="left: 225px; bottom: 0; border-radius: 0; z-index: 1000;">
            <div class="card-body p-2 bg-light d-flex justify-content-between align-items-center">
                <div class="ml-3 text-muted small font-italic d-none d-md-block">
                    <i class="bi bi-info-circle-fill"></i> Jangan lupa simpan perubahan Anda.
                </div>
                <button type="submit" class="btn btn-success font-weight-bold shadow-sm px-4 mr-3 ml-auto">
                    <i class="bi bi-floppy-fill mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>

    </form>

    <form id="formGenerateLampiran" action="<?= base_url('modulajar/generate_lampiran/' . $modul['id']) ?>" method="post" style="display:none;">
        <?= csrf_field(); ?>
    </form>

</div>

<script>
    $(document).ready(function() {
        // Inisialisasi Summernote dengan Toolbar yang disederhanakan
        var toolbarSimple = [
            ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['table', 'link']],
            ['view', ['fullscreen', 'codeview']]
        ];

        $('#summernote_kegiatan').summernote({ height: 300, toolbar: toolbarSimple });
        $('#summernote_materi').summernote({ height: 200, toolbar: toolbarSimple });
        $('#summernote_lkpd').summernote({ height: 300, toolbar: toolbarSimple });
        $('#summernote_lampiran_materi').summernote({ height: 200, toolbar: toolbarSimple });
        $('#summernote_asesmen').summernote({ height: 150, toolbar: toolbarSimple });
        $('#summernote_lampiran_asesmen').summernote({ height: 300, toolbar: toolbarSimple });
    });

    // Fungsi Trigger Generate Lampiran dengan Konfirmasi
    function submitGenerateLampiran() {
        Swal.fire({
            title: 'Generate Lampiran?',
            text: "AI akan membuatkan LKPD, Soal, dan Rubrik baru. Data lama di kolom lampiran akan tertimpa.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f6c23e', 
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Buatkan Sekarang!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan Loading Screen
                Swal.fire({
                    title: 'AI Sedang Bekerja...',
                    html: 'Sedang meracik soal HOTS dan LKPD menarik...<br><b>Mohon tunggu 1-3 menit.</b>',
                    imageUrl: 'https://media.giphy.com/media/l0HlMahmsb5nScE5a/giphy.gif',
                    imageWidth: 100,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                // Submit Form Hidden
                document.getElementById('formGenerateLampiran').submit();
            }
        })
    }
</script>

<?= $this->endSection(); ?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-3 px-md-4 py-4">

    <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-left: 5px solid #ffc107 !important;">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="bi bi-exclamation-triangle-fill fs-2 text-warning"></i>
            </div>
            <div>
                <h5 class="alert-heading fw-bold mb-1">Perhatian: Lengkapi Data Master Terlebih Dahulu!</h5>
                <p class="mb-0 small">
                    Sebelum memulai pembuatan Modul Ajar, Soal, dan Rapor mohon pastikan Anda telah mengisi menu 
                    <b>Data Master</b> (Identitas Sekolah, Data Kelas, Mata Pelajaran & Siswa). 
                </p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 overflow-hidden" style="background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); color: white; border-radius: 15px;">
                <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center position-relative">
                    <div style="z-index: 2;">
                        <h1 class="fw-bold display-6 fs-3 fs-md-1">Selamat Bertugas, <?= session()->get('nama') ?? 'Bapak/Ibu Guru' ?>!</h1>
                        <p class="lead mb-0 opacity-75 fs-6 fs-md-5" style="line-height: 1.4;">
                            Platform ini hadir menyederhanakan administrasi, agar Anda bisa fokus menginspirasi siswa.
                        </p>
                    </div>
                    <i class="bi bi-person-workspace position-absolute end-0 me-5 opacity-25 d-none d-md-block" style="font-size: 8rem; top: -20px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center mb-3">
        <h5 class="fw-bold text-gray-800 m-0"><i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Administrasi Pembelajaran</h5>
        <div class="flex-grow-1 border-bottom ms-3"></div>
    </div>

    <div class="row g-3 mb-5">
        
        <div class="col-lg-4 col-md-12">
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-3 p-3 text-center me-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-bullseye fs-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark">Cetak TP</h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill mb-2" style="font-size: 0.7rem;">Langkah 1</span>
                            <p class="text-muted small mb-3">
                                Susun Tujuan Pembelajaran (TP) dari referensi sistem atau mandiri.
                            </p>
                            <a href="<?= base_url('cetak_tp') ?>" class="btn btn-primary btn-sm w-100 rounded-pill shadow-sm py-2 fw-bold">
                                <i class="bi bi-plus-circle me-2"></i> Susun TP
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 text-info rounded-3 p-3 text-center me-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-diagram-3-fill fs-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark">Cetak ATP (AI)</h5>
                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill mb-2" style="font-size: 0.7rem;">Langkah 2</span>
                            <p class="text-muted small mb-3">
                                Gunakan AI untuk memecah TP menjadi Alur (ATP) yang runtut & detail.
                            </p>
                            <a href="<?= base_url('cetak_atp') ?>" class="btn btn-info text-white btn-sm w-100 rounded-pill shadow-sm py-2 fw-bold">
                                <i class="bi bi-stars me-2"></i> Generate ATP
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 text-success rounded-3 p-3 text-center me-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-calendar-week-fill fs-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark">Cetak Promes</h5>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill mb-2" style="font-size: 0.7rem;">Langkah 3</span>
                            <p class="text-muted small mb-3">
                                Ubah data ATP menjadi Program Semester (Promes) siap cetak.
                            </p>
                            <a href="<?= base_url('cetak_promes') ?>" class="btn btn-success btn-sm w-100 rounded-pill shadow-sm py-2 fw-bold">
                                <i class="bi bi-printer me-2"></i> Cetak Promes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="d-flex align-items-center mb-3">
        <h5 class="fw-bold text-gray-800 m-0"><i class="bi bi-grid-fill me-2 text-warning"></i>Fitur Unggulan Lainnya</h5>
        <div class="flex-grow-1 border-bottom ms-3"></div>
    </div>

    <div class="row g-3 g-md-4 justify-content-center">

        <div class="col-lg-4 col-md-12">
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-3 p-3 text-center me-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-robot fs-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark">Modul Ajar</h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill mb-2" style="font-size: 0.7rem;">AI Powered</span>
                            <p class="text-muted small mb-3">
                                Buat Modul Ajar terstruktur & kreatif dalam hitungan detik.
                            </p>
                            <a href="<?= base_url('modulajar') ?>" class="btn btn-primary btn-sm w-100 rounded-pill shadow-sm py-2 fw-bold">
                                <i class="bi bi-magic me-2"></i> Buat Modul
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning rounded-3 p-3 text-center me-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-pencil-square fs-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark">Generator Soal</h5>
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill mb-2" style="font-size: 0.7rem;">New Fitur</span>
                            <p class="text-muted small mb-3">
                                Buat Soal UH, STS, & SAS otomatis lengkap dengan Kisi-kisi.
                            </p>
                            <a href="<?= base_url('asesmen') ?>" class="btn btn-warning text-dark btn-sm w-100 rounded-pill shadow-sm py-2 fw-bold">
                                <i class="bi bi-lightning-charge me-2"></i> Buat Soal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 text-success rounded-3 p-3 text-center me-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-file-earmark-spreadsheet fs-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1 text-dark">E-Raport</h5>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill mb-2" style="font-size: 0.7rem;">Resmi</span>
                            <p class="text-muted small mb-3">
                                Kelola nilai & cetak rapor sesuai regulasi Kurikulum Merdeka.
                            </p>
                            <a href="<?= base_url('rapor/setting') ?>" class="btn btn-success btn-sm w-100 rounded-pill shadow-sm py-2 fw-bold">
                                <i class="bi bi-printer me-2"></i> Input Nilai
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-5">
        <div class="col-12 text-center opacity-50">
            <small class="fst-italic">"Teknologi di tangan guru hebat akan menjadi transformasional."</small>
        </div>
    </div>

</div>

<style>
    /* Efek Hover Halus */
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
        transition: all 0.3s ease;
    }
</style>

<?= $this->endSection() ?>
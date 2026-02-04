<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-3 px-md-4 py-4">

    <div class="row mb-4 mb-md-5">
        <div class="col-12">
            <div class="card shadow-sm border-0 overflow-hidden" style="background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); color: white; border-radius: 15px;">
                <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center position-relative">
                    <div style="z-index: 2;">
                        <h1 class="fw-bold display-6 fs-3 fs-md-1">Selamat Bertugas, <?= $nama_user ?? 'Bapak/Ibu Guru' ?>!</h1>
                        <p class="lead mb-0 opacity-75 fs-6 fs-md-5" style="line-height: 1.4;">
                            Platform ini hadir menyederhanakan administrasi, agar Anda bisa fokus menginspirasi siswa.
                        </p>
                    </div>
                    <i class="bi bi-person-workspace position-absolute end-0 me-5 opacity-25 d-none d-md-block" style="font-size: 8rem; top: -20px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-md-4 justify-content-center">

        <div class="col-lg-4 col-md-12">
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px; transition: all 0.3s;">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start text-center text-sm-start">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-3 p-3 text-center mb-3 mb-sm-0" style="width: 60px; height: 60px;">
                            <i class="bi bi-robot fs-2"></i>
                        </div>
                        <div class="ms-0 ms-sm-3 flex-grow-1 w-100">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center align-items-sm-start mb-2">
                                <h5 class="fw-bold mb-1 mb-sm-0 text-dark">Modul Ajar</h5>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill" style="font-size: 0.7rem;">AI Powered</span>
                            </div>
                            <p class="text-muted small mb-3">
                                Buat Modul Ajar terstruktur & kreatif dalam hitungan detik.
                            </p>
                            <a href="<?= base_url('modulajar') ?>" class="btn btn-primary btn-sm w-100 rounded-pill shadow-sm py-2">
                                <i class="bi bi-magic me-2"></i> Buat Modul
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px; transition: all 0.3s;">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start text-center text-sm-start">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning rounded-3 p-3 text-center mb-3 mb-sm-0" style="width: 60px; height: 60px;">
                            <i class="bi bi-pencil-square fs-2"></i>
                        </div>
                        <div class="ms-0 ms-sm-3 flex-grow-1 w-100">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center align-items-sm-start mb-2">
                                <h5 class="fw-bold mb-1 mb-sm-0 text-dark">Generator Soal</h5>
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill" style="font-size: 0.7rem;">New Fitur</span>
                            </div>
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
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px; transition: all 0.3s;">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start text-center text-sm-start">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 text-success rounded-3 p-3 text-center mb-3 mb-sm-0" style="width: 60px; height: 60px;">
                            <i class="bi bi-file-earmark-spreadsheet fs-2"></i>
                        </div>
                        <div class="ms-0 ms-sm-3 flex-grow-1 w-100">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center align-items-sm-start mb-2">
                                <h5 class="fw-bold mb-1 mb-sm-0 text-dark">E-Raport</h5>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill" style="font-size: 0.7rem;">Resmi</span>
                            </div>
                            <p class="text-muted small mb-3">
                                Kelola nilai & cetak rapor sesuai regulasi Kurikulum Merdeka.
                            </p>
                            <a href="<?= base_url('rapot') ?>" class="btn btn-success btn-sm w-100 rounded-pill shadow-sm py-2">
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
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
</style>

<?= $this->endSection() ?>
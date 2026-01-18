<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid p-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pusat Bantuan & Tutorial</h1>
    </div>

    <div class="alert alert-info border-left-info shadow-sm" role="alert">
        <h4 class="alert-heading">
            <i class="bi bi-info-circle-fill me-2"></i> Bingung Cara Pakai?
        </h4>
        <p class="mb-0">Pilih topik di bawah ini untuk melihat panduan langkah demi langkah dan video tutorial penggunaan aplikasi.</p>
    </div>

    <h5 class="font-weight-bold text-primary mt-4 mb-3">A. Aplikasi Modul Ajar (AI)</h5>
    <div class="row">
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100 hover-top">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-primary text-white me-3">
                            <i class="bi bi-robot fs-5"></i>
                        </div>
                        <h6 class="m-0 font-weight-bold text-dark">Generate Modul AI</h6>
                    </div>
                    <p class="small text-muted">Cara membuat modul ajar otomatis hanya dengan mengetik topik.</p>
                    <a href="<?= base_url('tutorial/read/cara-buat-modul'); ?>" class="btn btn-sm btn-outline-primary stretched-link">
                        Lihat Panduan <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100 hover-top">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-warning text-white me-3">
                            <i class="bi bi-file-earmark-word fs-5"></i>
                        </div>
                        <h6 class="m-0 font-weight-bold text-dark">Export ke Word</h6>
                    </div>
                    <p class="small text-muted">Cara download dan edit hasil modul ajar di Microsoft Word.</p>
                    <a href="#" class="btn btn-sm btn-outline-primary stretched-link">
                        Lihat Panduan <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <hr>

    <h5 class="font-weight-bold text-success mt-4 mb-3">B. Aplikasi E-Raport</h5>
    <div class="row">
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100 hover-top">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-success text-white me-3">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </div>
                        <h6 class="m-0 font-weight-bold text-dark">Input Nilai Siswa</h6>
                    </div>
                    <p class="small text-muted">Panduan mengisi nilai TP, LM, dan Ekstrakurikuler.</p>
                    <a href="#" class="btn btn-sm btn-outline-success stretched-link">
                        Lihat Panduan <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100 hover-top">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-info text-white me-3">
                            <i class="bi bi-printer fs-5"></i>
                        </div>
                        <h6 class="m-0 font-weight-bold text-dark">Cetak Raport</h6>
                    </div>
                    <p class="small text-muted">Cara print raport agar pas kertas A4 (Setting CSS Print).</p>
                    <a href="#" class="btn btn-sm btn-outline-success stretched-link">
                        Lihat Panduan <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

<style>
    .hover-top { transition: transform 0.2s; }
    .hover-top:hover { transform: translateY(-5px); }
    .icon-circle { 
        width: 45px; height: 45px; /* Saya perbesar sedikit biar pas */
        border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; 
    }
</style>

<?= $this->endSection(); ?>
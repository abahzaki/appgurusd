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

        <div class="col-lg-6 col-md-12">
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px; transition: all 0.3s;">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start text-center text-sm-start">
                        
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-3 p-3 text-center mb-3 mb-sm-0" style="width: 70px; height: 70px;">
                            <i class="bi bi-robot fs-2"></i>
                        </div>
                        
                        <div class="ms-0 ms-sm-4 flex-grow-1 w-100">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center align-items-sm-start mb-2">
                                <h4 class="fw-bold mb-2 mb-sm-0 text-dark">Modul Ajar Cerdas</h4>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    <i class="bi bi-stars me-1"></i> AI Powered
                                </span>
                            </div>
                            <p class="text-muted mb-4">
                                Asisten AI (Deep Learning) siap membantu Anda merancang Modul Ajar yang terstruktur, kreatif, dan personal dalam hitungan detik.
                            </p>
                            <a href="<?= base_url('modulajar') ?>" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">
                                <i class="bi bi-magic me-2"></i> Buat Modul Sekarang
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 15px; transition: all 0.3s;">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start text-center text-sm-start">
                        
                        <div class="flex-shrink-0 bg-success bg-opacity-10 text-success rounded-3 p-3 text-center mb-3 mb-sm-0" style="width: 70px; height: 70px;">
                            <i class="bi bi-file-earmark-spreadsheet fs-2"></i>
                        </div>
                        
                        <div class="ms-0 ms-sm-4 flex-grow-1 w-100">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center align-items-sm-start mb-2">
                                <h4 class="fw-bold mb-2 mb-sm-0 text-dark">E-Raport Merdeka</h4>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i> SK BSKAP 046
                                </span>
                            </div>
                            <p class="text-muted mb-4">
                                Kelola penilaian hasil belajar siswa dengan tenang. Format rapor otomatis disesuaikan dengan regulasi Kurikulum Merdeka terbaru.
                            </p>
                            <a href="<?= base_url('rapot') ?>" class="btn btn-success btn-lg w-100 rounded-pill shadow-sm">
                                <i class="bi bi-printer me-2"></i> Input & Cetak Nilai
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
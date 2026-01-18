<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid p-4">

    <a href="<?= base_url('tutorial'); ?>" class="btn btn-secondary btn-sm mb-4">
        <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Tutorial
    </a>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Panduan: Cara Generate Modul Ajar AI</h6>
        </div>
        <div class="card-body">
            
            <div class="ratio ratio-16x9 mb-4" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                <iframe 
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                    src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                    title="YouTube video player" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>

            <hr>

            <div class="tutorial-content text-dark mt-4">
                <h4>Langkah-langkah:</h4>
                <p>Berikut adalah cara menggunakan fitur AI Generator:</p>
                <ol>
                    <li class="mb-2">Klik menu <strong>Modul Ajar</strong> di sidebar sebelah kiri.</li>
                    <li class="mb-2">Klik tombol biru bertuliskan <strong>"Tambah Baru"</strong>.</li>
                    <li class="mb-2">Pada kolom topik, ketik materi yang ingin diajarkan. Contoh: <em>"Sifat-sifat Cahaya untuk kelas 5 SD"</em>.</li>
                    <li class="mb-2">Klik tombol <strong>Generate AI</strong> dan tunggu sekitar 10-20 detik.</li>
                    <li class="mb-2">Selesai! Modul ajar Anda sudah jadi dan bisa langsung diedit atau di-export ke Word.</li>
                </ol>

                <div class="alert alert-warning mt-4 d-flex align-items-center" role="alert">
                    <i class="bi bi-lightbulb-fill fs-4 me-3"></i>
                    <div>
                        <strong>Tips:</strong> Semakin detail topik yang Anda ketik, semakin bagus hasil yang diberikan oleh AI.
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<?= $this->endSection(); ?>
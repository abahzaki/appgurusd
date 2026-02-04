<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'GuruApp' ?> - Trendi Media</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; overflow-x: hidden; }
        
        /* SIDEBAR STYLE */
        .sidebar {
            width: 260px; height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%); color: white;
            transition: all 0.3s;
            overflow-y: auto;          /* Mengaktifkan fungsi scroll */
            scrollbar-width: none;     /* Menyembunyikan batang scroll di Firefox */
            -ms-overflow-style: none;  /* Menyembunyikan batang scroll di IE/Edge */
        }

        .sidebar::-webkit-scrollbar { display: none; }

        .sidebar-header { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu { padding: 20px 10px; }
        .nav-link { 
            color: rgba(255,255,255,0.7); margin-bottom: 5px; border-radius: 8px; padding: 12px 15px; 
            display: flex; align-items: center; text-decoration: none; transition: 0.2s; cursor: pointer;
        }
        .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.1); color: #fff; transform: translateX(5px); }
        .nav-link i { margin-right: 12px; font-size: 1.2rem; width: 25px; text-align: center; }
        
        /* Submenu Style */
        .submenu-link {
            font-size: 0.9rem; padding-left: 50px !important; color: rgba(255,255,255,0.5);
        }
        .submenu-link:hover { color: #fff; transform: translateX(5px); }

        /* CONTENT AREA */
        .main-content { margin-left: 260px; transition: all 0.3s; min-height: 100vh; display: flex; flex-direction: column; }
        .topbar { background: white; padding: 10px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; height: 70px; }
        .content-body { padding: 30px; flex: 1; }
        .footer { background: white; padding: 15px 30px; text-align: center; font-size: 0.85rem; color: #888; border-top: 1px solid #eee; }

        /* RESPONSIVE (HP) */
        @media (max-width: 768px) {
            .sidebar { margin-left: -260px; }
            .sidebar.active { margin-left: 0; }
            .main-content { margin-left: 0; }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; }
            .sidebar-overlay.active { display: block; }
        }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header d-flex align-items-center gap-3">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px; font-weight:bold;">T</div>
            <div>
                <h5 class="m-0 fw-bold">Trendi App</h5>
                <small class="text-white-50 text-uppercase"><?= session()->get('role') == 'admin' ? 'Administrator' : 'Guru SD Pro' ?></small>
            </div>
        </div>

        <div class="sidebar-menu">
            <?php $role = session()->get('role'); ?>
            <?php $uri = service('uri'); ?>

            <?php if ($role == 'admin') : ?>
                <small class="text-uppercase text-white-50 fw-bold ms-2 mb-2 d-block" style="font-size: 0.7rem;">Administrator</small>
                
                <a href="<?= base_url('dashboard') ?>" class="nav-link <?= ($uri->getSegment(1) == 'dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
                
                <a href="<?= base_url('user') ?>" class="nav-link <?= ($uri->getSegment(1) == 'user') ? 'active' : '' ?>">
                    <i class="bi bi-people-fill"></i> Manajemen User
                </a>

                <a class="nav-link <?= ($uri->getSegment(1) == 'adsreport') ? 'active' : ''; ?>" href="<?= base_url('adsreport'); ?>">
                    <i class="bi bi-graph-up-arrow"></i> Laporan Iklan (ROI)
                </a>
                
                <hr class="border-secondary my-4">
            <?php endif; ?>


            <?php if ($role == 'guru') : ?>

                <small class="text-uppercase text-white-50 fw-bold ms-2 mb-2 d-block" style="font-size: 0.7rem;">Menu Utama</small>
                
                <a href="<?= base_url('dashboard') ?>" class="nav-link <?= ($uri->getSegment(1) == 'dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>

                <a class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#collapseMaster" role="button" aria-expanded="false" aria-controls="collapseMaster">
                    <div class="d-flex align-items-center"><i class="bi bi-database-fill"></i> Data Master</div>
                    <i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i>
                </a>
                
                <div class="collapse <?= ($uri->getSegment(1) == 'datamaster') ? 'show' : '' ?>" id="collapseMaster">
                    <div class="d-flex flex-column">
                        <a href="<?= base_url('datamaster/sekolah') ?>" class="nav-link submenu-link <?= ($uri->getSegment(2) == 'sekolah') ? 'text-white' : '' ?>">Identitas Sekolah</a>
                        <a href="<?= base_url('datamaster/kelas') ?>" class="nav-link submenu-link <?= ($uri->getSegment(2) == 'kelas') ? 'text-white' : '' ?>">Data Kelas</a>
                        <a href="<?= base_url('datamaster/mapel') ?>" class="nav-link submenu-link <?= ($uri->getSegment(2) == 'mapel') ? 'text-white' : '' ?>">Mata Pelajaran</a>
                        <a href="<?= base_url('datamaster/siswa') ?>" class="nav-link submenu-link <?= ($uri->getSegment(2) == 'siswa') ? 'text-white' : '' ?>">Data Siswa</a>
                        <a href="<?= base_url('datamaster/tp') ?>" class="nav-link submenu-link <?= ($uri->getSegment(2) == 'tp') ? 'text-white' : '' ?>">Tujuan Pembelajaran</a>
                    </div>
                </div>

                <hr class="sidebar-divider" style="border-color: rgba(255,255,255,0.1);">
                
                <small class="text-uppercase text-white-50 fw-bold ms-2 mb-2 mt-2 d-block" style="font-size: 0.7rem;">Penilaian</small>
                
                <a href="<?= base_url('asesmen') ?>" class="nav-link <?= ($uri->getSegment(1) == 'asesmen') ? 'active' : '' ?>">
                    <i class="bi bi-magic"></i> Generator Soal
                </a>
                
                <?php 
                    // Cek apakah sedang buka menu raport
                    $isRaportOpen = in_array($uri->getSegment(1), ['nilai', 'ekskul', 'kokurikuler', 'catatan', 'rapor']);
                ?>

                <a class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#collapseRaport" role="button" aria-expanded="false" aria-controls="collapseRaport">
                    <div class="d-flex align-items-center"><i class="bi bi-journal-check"></i> E-Raport</div>
                    <i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i>
                </a>

                <div class="collapse <?= $isRaportOpen ? 'show' : '' ?>" id="collapseRaport">
                    <div class="d-flex flex-column">
                        <a href="<?= base_url('rapor/setting') ?>" class="nav-link submenu-link <?= ($uri->getSegment(2) == 'setting') ? 'text-white' : '' ?>">
                           Pengaturan Rapor
                        </a>

                        <a href="<?= base_url('nilai') ?>" class="nav-link submenu-link <?= ($uri->getSegment(1) == 'nilai') ? 'text-white' : '' ?>">
                            Input Nilai
                        </a>

                        <a href="<?= base_url('ekskul') ?>" class="nav-link submenu-link <?= ($uri->getSegment(1) == 'ekskul') ? 'text-white' : '' ?>">
                            Input Ekskul
                        </a>

                        <a href="<?= base_url('kokurikuler') ?>" class="nav-link submenu-link <?= ($uri->getSegment(1) == 'kokurikuler') ? 'text-white' : '' ?>">
                            Projek Kokurikuler
                        </a>

                        <a href="<?= base_url('catatan') ?>" class="nav-link submenu-link <?= ($uri->getSegment(1) == 'catatan') ? 'text-white' : '' ?>">
                            Catatan & Absensi
                        </a>

                        <a href="<?= base_url('rapor') ?>" class="nav-link submenu-link <?= ($uri->getSegment(1) == 'rapor' && $uri->getSegment(2) != 'setting') ? 'text-white' : '' ?>">
                            Cetak Rapor
                        </a>
                    </div>
                </div>

                <hr class="sidebar-divider" style="border-color: rgba(255,255,255,0.1);">

                <small class="text-uppercase text-white-50 fw-bold ms-2 mb-2 mt-2 d-block" style="font-size: 0.7rem;">Administrasi</small>

                <a href="<?= base_url('modulajar') ?>" class="nav-link <?= ($uri->getSegment(1) == 'modulajar') ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-text"></i> Modul Ajar
                </a>

                <hr class="sidebar-divider" style="border-color: rgba(255,255,255,0.1);">

                <small class="text-uppercase text-white-50 fw-bold ms-2 mb-2 mt-2 d-block" style="font-size: 0.7rem;">Extra</small>

                <a class="nav-link <?= ($uri->getSegment(1) == 'tutorial') ? 'active' : '' ?>" href="<?= base_url('tutorial') ?>">
                    <i class="bi bi-question-circle"></i> Panduan & Tutorial
                </a>
                
                <a href="<?= base_url('user/profile') ?>" class="nav-link <?= ($uri->getSegment(2) == 'profile') ? 'active' : '' ?>">
                    <i class="bi bi-person-circle"></i> Profil Saya
                </a>
                
                <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>

            <?php endif; // END IF GURU ?>

            <?php if ($role == 'admin') : ?>
                <a href="<?= base_url('user/profile') ?>" class="nav-link <?= ($uri->getSegment(2) == 'profile') ? 'active' : '' ?>">
                    <i class="bi bi-person-circle"></i> Profil Saya
                </a>
                
                <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            <?php endif; ?>

        </div>
    </nav>

    <div class="main-content">
        <header class="topbar">
            <button class="btn btn-light d-md-none" id="sidebarToggle"><i class="bi bi-list fs-4"></i></button>
            <h5 class="m-0 fw-bold text-primary d-none d-md-block"><?= $title ?? 'Aplikasi Guru' ?></h5>
            
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block" style="line-height: 1.2;">
                    <small class="d-block text-muted" style="font-size: 10px;">Login Sebagai</small>
                    <span class="fw-bold d-block text-dark"><?= session()->get('nama') ?></span>
                    
                    <?php 
                        // --- LOGIKA AMBIL MASA AKTIF ---
                        $db = \Config\Database::connect();
                        $idUser = session()->get('id');
                        $user = $db->table('users')->select('expired_date')->where('id', $idUser)->get()->getRowArray();
                        $tglExp = $user['expired_date'] ?? null;
                    ?>

                    <?php if($tglExp): ?>
                        <?php 
                            $sisaHari = (strtotime($tglExp) - time()) / (60 * 60 * 24);
                            
                            // Logika Warna
                            $warna = 'text-success'; // Aman (Hijau)
                            if($sisaHari < 0) { $warna = 'text-muted'; } // Expired
                            elseif($sisaHari < 7) { $warna = 'text-danger'; } // < 7 Hari (Merah)
                            elseif($sisaHari < 30) { $warna = 'text-warning'; } // < 30 Hari (Kuning)
                            
                            $tglIndo = date('d M Y', strtotime($tglExp));
                        ?>
                        <small class="<?= $warna ?> fw-bold" style="font-size: 11px;">
                            <i class="bi bi-clock-history"></i> Aktif s.d <?= $tglIndo ?>
                        </small>
                    <?php else: ?>
                         <small class="text-muted" style="font-size: 11px;">Free Access</small>
                    <?php endif; ?>

                </div>
                
                <div class="position-relative">
                    <img src="https://ui-avatars.com/api/?name=<?= session()->get('nama') ?>&background=0D6EFD&color=fff" class="rounded-circle shadow-sm" width="40" height="40" alt="User">
                     <span class="position-absolute bottom-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle"></span>
                </div>
            </div>

        </header>

        <div class="content-body">
            <?= $this->renderSection('content') ?>
        </div>

        <footer class="footer">
            &copy; 2026 Trendi Media Digital. Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk Guru Indonesia.
        </footer>
    </div>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        });
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('active');
            document.getElementById('sidebarOverlay').classList.remove('active');
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>

    <style>
    /* Paksa Modal tampil di paling depan */
    .modal-backdrop { z-index: 1040 !important; opacity: 0.5 !important; }
    .modal { z-index: 1050 !important; }
    .modal-content { background-color: #fff !important; z-index: 1060 !important; }
    </style>

    <script>
    // Pindahkan Modal ke Body agar tidak tertutup Sidebar
    document.addEventListener("DOMContentLoaded", function(){
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal){
            document.body.appendChild(modal);
        });
    });
    </script>

</body>
</html>
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- HALAMAN DEPAN (REDIRECT LOGIN) ---
$routes->get('/', function() {
    return redirect()->to('login');
});

// =========================================================================
// 1. AUTHENTICATION (LOGIN & REGISTER)
// =========================================================================
$routes->get('login', 'Auth::login');
$routes->post('login/process', 'Auth::process');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::register');
$routes->post('auth/process_register', 'Auth::process_register');
$routes->get('auth/success', 'Auth::success');

// TEMPORARY: Helper Buat Password (Hapus jika sudah production)
$routes->get('buat-password', function() {
    echo password_hash('admin123', PASSWORD_DEFAULT);
});


// =========================================================================
// 2. DASHBOARD & USER PROFILE (DILINDUNGI AUTH)
// =========================================================================
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // DASHBOARD UTAMA
    $routes->get('dashboard', 'Dashboard::index');

    // PROFIL SAYA
    $routes->get('user/profile', 'UserController::profile');
    $routes->post('user/updatePassword', 'UserController::updatePassword');
    
    // MANAJEMEN USER (ADMIN ONLY - Sebaiknya ditambah filter role admin)
    $routes->get('user', 'UserController::index');
    $routes->get('user/activate/(:num)', 'UserController::activate/$1');
    $routes->get('user/deactivate/(:num)', 'UserController::deactivate/$1');
    $routes->get('user/resetpassword/(:num)', 'UserController::resetPassword/$1');
    
    // LAPORAN ADS (ADMIN)
    $routes->get('adsreport', 'AdsReport::index');
    $routes->post('adsreport/store', 'AdsReport::store_cost');
});


// =========================================================================
// 3. FITUR UTAMA GURU (MODUL AJAR & ASESMEN)
// =========================================================================
$routes->group('', ['filter' => 'auth'], function($routes) {

    // --- MODUL AJAR (RPP) ---
    $routes->get('modulajar', 'ModulAjar::index');              // List
    $routes->get('modulajar/create', 'ModulAjar::create');      // Form
    $routes->post('modulajar/store', 'ModulAjar::store');       // Proses
    $routes->get('modulajar/edit/(:num)', 'ModulAjar::edit/$1'); // Edit
    $routes->post('modulajar/update/(:num)', 'ModulAjar::update/$1'); // Update
    $routes->get('modulajar/delete/(:num)', 'ModulAjar::delete/$1'); // Hapus
    
    $routes->get('modulajar/cetak/(:num)', 'ModulAjar::cetak/$1');  // PDF
    $routes->get('modulajar/word/(:num)', 'ModulAjar::word/$1');    // Word
    $routes->post('modulajar/generate_lampiran/(:num)', 'ModulAjar::generateLampiran/$1'); // AI Lampiran

    // --- GENERATOR SOAL (ASESMEN AI) ---
    $routes->group('asesmen', function($routes) {
        $routes->get('/', 'AsesmenController::index');
        $routes->get('baru', 'AsesmenController::create');
        $routes->post('generate', 'AsesmenController::generate');
        $routes->get('lihat/(:num)', 'AsesmenController::viewResult/$1');
        $routes->get('delete/(:num)', 'AsesmenController::delete/$1');
        $routes->post('updateResult/(:num)', 'AsesmenController::updateResult/$1');
        $routes->get('exportWord/(:num)', 'AsesmenController::exportWord/$1');
        
        // Helper AJAX Asesmen
        $routes->get('get-existing-tp/(:num)', 'AsesmenController::getExistingTp/$1');
        $routes->get('get-referensi-tp/(:num)/(:any)', 'AsesmenController::getReferensiTp/$1/$2');
    });

    // --- CETAK TP (TUJUAN PEMBELAJARAN) ---
    $routes->get('cetak_tp', 'CetakTp::index');
    $routes->post('cetak_tp/susun', 'CetakTp::susun');
    $routes->post('cetak_tp/simpan_dan_export', 'CetakTp::simpan_dan_export'); // Route Penting!
    $routes->get('cetak_tp/delete/(:num)', 'CetakTp::delete/$1');

    // --- CETAK ATP (ALUR TUJUAN PEMBELAJARAN - AI) ---
    $routes->get('cetak_atp', 'CetakAtp::index');
    $routes->post('cetak_atp/generate_ai', 'CetakAtp::generate_ai');
    $routes->post('cetak_atp/save_atp', 'CetakAtp::save_atp');
    $routes->get('cetak_atp/download/(:num)', 'CetakAtp::download/$1');
    $routes->get('cetak_atp/delete/(:num)', 'CetakAtp::delete/$1');
    $routes->get('cetak_atp/download_promes/(:num)', 'CetakAtp::download_promes/$1');
            // Tambahkan di dalam group auth Administrasi
    $routes->get('cetak_promes', 'CetakAtp::index_promes');
    
});


// =========================================================================
// 4. DATA MASTER (ADMINISTRASI KELAS)
// =========================================================================
$routes->group('datamaster', ['filter' => 'auth'], function($routes) {

    // IDENTITAS SEKOLAH
    $routes->get('sekolah', 'DataMaster\Sekolah::index');
    $routes->post('sekolah/update', 'DataMaster\Sekolah::update');

    // DATA KELAS
    $routes->get('kelas', 'DataMaster\Kelas::index');
    $routes->post('kelas/store', 'DataMaster\Kelas::store');
    $routes->post('kelas/update/(:num)', 'DataMaster\Kelas::update/$1');
    $routes->get('kelas/delete/(:num)', 'DataMaster\Kelas::delete/$1');

    // MATA PELAJARAN
    $routes->get('mapel', 'DataMaster\Mapel::index');
    $routes->post('mapel/store', 'DataMaster\Mapel::store');
    $routes->post('mapel/update/(:num)', 'DataMaster\Mapel::update/$1');
    $routes->get('mapel/delete/(:num)', 'DataMaster\Mapel::delete/$1');

    // DATA SISWA
    $routes->get('siswa', 'DataMaster\Siswa::index');
    $routes->post('siswa/import', 'DataMaster\Siswa::import');
    $routes->get('siswa/edit/(:num)', 'DataMaster\Siswa::edit/$1');
    $routes->post('siswa/update/(:num)', 'DataMaster\Siswa::update/$1');
    $routes->get('siswa/delete/(:num)', 'DataMaster\Siswa::delete/$1');

    // TUJUAN PEMBELAJARAN (ADMINISTRASI MASTER)
    $routes->get('tp', 'DataMaster\Tp::index');
    $routes->post('tp/store', 'DataMaster\Tp::store');
    $routes->post('tp/update/(:num)', 'DataMaster\Tp::update/$1');
    $routes->get('tp/delete/(:num)', 'DataMaster\Tp::delete/$1');
    
    // AJAX Referensi TP
    $routes->get('tp/get_referensi', 'DataMaster\Tp::get_referensi');
    $routes->post('tp/proses_ambil', 'DataMaster\Tp::proses_ambil');
});


// =========================================================================
// 5. E-RAPOR & PENILAIAN
// =========================================================================
$routes->group('', ['filter' => 'auth'], function($routes) {

    // INPUT NILAI HARIAN / SUMATIF
    $routes->get('nilai', 'Nilai::index');
    $routes->get('nilai/form', 'Nilai::form');
    $routes->post('nilai/simpan', 'Nilai::simpan');

    // INPUT NILAI UJIAN (STS / SAS)
    $routes->get('nilai/form_ujian', 'Nilai::form_ujian');
    $routes->post('nilai/simpan_ujian', 'Nilai::simpan_ujian');

    // EKSKUL
    $routes->get('ekskul', 'Ekskul::index');
    $routes->post('ekskul/simpan', 'Ekskul::simpan');
    $routes->get('ekskul/hapus/(:num)', 'Ekskul::hapus/$1');

    // CATATAN WALI KELAS & ABSENSI
    $routes->get('catatan', 'Catatan::index');
    $routes->post('catatan/simpan', 'Catatan::simpan');

    // PROJEK P5 (KOKURIKULER)
    $routes->get('kokurikuler', 'Kokurikuler::index');
    $routes->post('kokurikuler/tambah_projek', 'Kokurikuler::tambah_projek');
    $routes->get('kokurikuler/hapus_projek/(:num)', 'Kokurikuler::hapus_projek/$1');
    $routes->get('kokurikuler/nilai/(:num)', 'Kokurikuler::nilai/$1');
    $routes->post('kokurikuler/simpan_nilai', 'Kokurikuler::simpan_nilai');

    // CETAK RAPOR
    $routes->get('rapor', 'Rapor::index');
    $routes->get('rapor/cetak/(:num)', 'Rapor::cetak/$1');
    
    // SETTING RAPOR (TANGGAL, TTD)
    $routes->get('rapor/setting', 'Rapor::setting');
    $routes->post('rapor/update_setting', 'Rapor::update_setting');

    // DESKRIPSI RAPOR (CUSTOM)
    $routes->get('rapor/edit_deskripsi/(:num)', 'Rapor::edit_deskripsi/$1');
    $routes->post('rapor/simpan_deskripsi', 'Rapor::simpan_deskripsi');
});


// =========================================================================
// 6. EXTRA (TUTORIAL & BANTUAN)
// =========================================================================
$routes->get('tutorial', 'Tutorial::index');
$routes->get('tutorial/read/(:segment)', 'Tutorial::read/$1');
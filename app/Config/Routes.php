<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', function() {
    return redirect()->to('login');
});

// --- ROUTING APLIKASI GURU SD ---

// --- ROUTING AUTH ---
$routes->get('login', 'Auth::login');
$routes->post('login/process', 'Auth::process');
$routes->get('logout', 'Auth::logout');

// Route Registrasi
$routes->get('register', 'Auth::register');
$routes->post('auth/process_register', 'Auth::process_register');

// --- ROUTE ADMIN USER ---
$routes->get('user', 'UserController::index');
$routes->get('user/activate/(:num)', 'UserController::activate/$1');
$routes->get('user/deactivate/(:num)', 'UserController::deactivate/$1');

// --- ROUTE PROFIL SAYA ---
$routes->get('user/profile', 'UserController::profile');
$routes->post('user/updatePassword', 'UserController::updatePassword');
$routes->get('user/resetpassword/(:num)', 'UserController::resetPassword/$1');

// Ubah Dashboard agar mengarah ke file yang benar (nanti kita buat)
$routes->get('dashboard', 'Dashboard::index');


// --- ROUTES MODUL AJAR ---
$routes->get('modulajar', 'ModulAjar::index');                 // Halaman List
$routes->get('modulajar/create', 'ModulAjar::create');         // Form Input
$routes->post('modulajar/store', 'ModulAjar::store');          // Proses Generate AI
$routes->get('modulajar/edit/(:num)', 'ModulAjar::edit/$1');   // Halaman Editor
$routes->post('modulajar/update/(:num)', 'ModulAjar::update/$1'); // Proses Simpan Edit

$routes->get('modulajar/cetak/(:num)', 'ModulAjar::cetak/$1');  // Untuk PDF
$routes->get('modulajar/word/(:num)', 'ModulAjar::word/$1');    // Untuk Word
// TAMBAHKAN INI UNTUK DELETE:
$routes->get('modulajar/delete/(:num)', 'ModulAjar::delete/$1');

// --- TAMBAHKAN BARIS INI (Route untuk Tombol Tahap 2) ---
$routes->post('modulajar/generate_lampiran/(:num)', 'ModulAjar::generateLampiran/$1');


// --- GENERATOR SOAL (AI) ---
$routes->group('asesmen', function($routes) {
    $routes->get('/', 'AsesmenController::index');           // Ini ke Daftar Riwayat
    $routes->get('baru', 'AsesmenController::create');       // Ini ke Form Input
    $routes->post('generate', 'AsesmenController::generate'); // Proses AI
    $routes->get('lihat/(:num)', 'AsesmenController::viewResult/$1'); // Lihat Hasil
    $routes->get('delete/(:num)', 'AsesmenController::delete/$1');    // Hapus
    $routes->post('updateResult/(:num)', 'AsesmenController::updateResult/$1'); // Save Changes
    $routes->get('exportWord/(:num)', 'AsesmenController::exportWord/$1');     // Download Word
    
    // Helper AJAX
    $routes->get('get-existing-tp/(:num)', 'AsesmenController::getExistingTp/$1');
    $routes->get('get-referensi-tp/(:num)/(:any)', 'AsesmenController::getReferensiTp/$1/$2');
});


// --- DATA MASTER (Fondasi Raport) ---
$routes->group('datamaster', function($routes) {

    $routes->get('siswa', 'DataMaster\Siswa::index');
    $routes->post('siswa/import', 'DataMaster\Siswa::import');

    // --- TAMBAHAN ROUTE EDIT & DELETE ---
    $routes->get('siswa/edit/(:num)', 'DataMaster\Siswa::edit/$1'); // Form Edit
    $routes->post('siswa/update/(:num)', 'DataMaster\Siswa::update/$1'); // Proses Simpan
    $routes->get('siswa/delete/(:num)', 'DataMaster\Siswa::delete/$1'); // Hapus

    // Identitas Sekolah
    $routes->get('sekolah', 'DataMaster\Sekolah::index');
    $routes->post('sekolah/update', 'DataMaster\Sekolah::update');

    // MANAJEMEN KELAS
    $routes->get('kelas', 'DataMaster\Kelas::index');
    $routes->post('kelas/store', 'DataMaster\Kelas::store');
    $routes->post('kelas/update/(:num)', 'DataMaster\Kelas::update/$1');
    $routes->get('kelas/delete/(:num)', 'DataMaster\Kelas::delete/$1');
    
    // 4. MATA PELAJARAN
    $routes->get('mapel', 'DataMaster\Mapel::index');
    $routes->post('mapel/store', 'DataMaster\Mapel::store');
    $routes->post('mapel/update/(:num)', 'DataMaster\Mapel::update/$1');
    $routes->get('mapel/delete/(:num)', 'DataMaster\Mapel::delete/$1');
    
    // 5. TUJUAN PEMBELAJARAN (TP)
    $routes->get('tp', 'DataMaster\Tp::index');
    $routes->post('tp/store', 'DataMaster\Tp::store');
    $routes->post('tp/update/(:num)', 'DataMaster\Tp::update/$1');
    $routes->get('tp/delete/(:num)', 'DataMaster\Tp::delete/$1');

    // --- TAMBAHAN BANK TP ---
    $routes->get('tp/get_referensi', 'DataMaster\Tp::get_referensi');
    $routes->post('tp/proses_ambil', 'DataMaster\Tp::proses_ambil');

});


// --- APLIKASI PENILAIAN (ASESMEN MANUAL & RAPORT) ---
$routes->get('nilai', 'Nilai::index');          // Halaman Pilih Kelas
$routes->get('nilai/form', 'Nilai::form');      // Halaman Input Grid
$routes->post('nilai/simpan', 'Nilai::simpan'); // Proses Simpan

// TAMBAHAN: Route Ujian STS/SAS
$routes->get('nilai/form_ujian', 'Nilai::form_ujian');
$routes->post('nilai/simpan_ujian', 'Nilai::simpan_ujian');


// --- EKSKUL ---
$routes->get('ekskul', 'Ekskul::index');
$routes->post('ekskul/simpan', 'Ekskul::simpan');
$routes->get('ekskul/hapus/(:num)', 'Ekskul::hapus/$1');

// --- CATATAN WALAS ---
$routes->get('catatan', 'Catatan::index');
$routes->post('catatan/simpan', 'Catatan::simpan');

// --- KOKURIKULER (P5) ---
$routes->get('kokurikuler', 'Kokurikuler::index');
$routes->post('kokurikuler/tambah_projek', 'Kokurikuler::tambah_projek');
$routes->get('kokurikuler/hapus_projek/(:num)', 'Kokurikuler::hapus_projek/$1');

$routes->get('kokurikuler/nilai/(:num)', 'Kokurikuler::nilai/$1');
$routes->post('kokurikuler/simpan_nilai', 'Kokurikuler::simpan_nilai');


// --- CETAK RAPOR ---
$routes->get('rapor', 'Rapor::index');
$routes->get('rapor/cetak/(:num)', 'Rapor::cetak/$1');
$routes->get('rapor/setting', 'Rapor::setting');
$routes->post('rapor/update_setting', 'Rapor::update_setting');

$routes->get('rapor/edit_deskripsi/(:num)', 'Rapor::edit_deskripsi/$1');
$routes->post('rapor/simpan_deskripsi', 'Rapor::simpan_deskripsi');

// --- ROUTES UNTUK TUTORIAL ---
$routes->get('tutorial', 'Tutorial::index'); // Halaman depan tutorial
$routes->get('tutorial/read/(:segment)', 'Tutorial::read/$1'); // Halaman baca detail (dinamis)

$routes->get('auth/success', 'Auth::success'); // Halaman Terima Kasih


// --- LAPORAN PERFORMA IKLAN (DASHBOARD SULTAN) ---
// Kita bungkus dalam group 'admin' atau filter auth supaya aman
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Halaman Laporan ROI
    $routes->get('adsreport', 'AdsReport::index');
    
    // Proses Simpan Biaya Harian
    $routes->post('adsreport/store', 'AdsReport::store_cost');

});


// Hapus baris ini nanti setelah berhasil login
$routes->get('buat-password', function() {
    echo password_hash('admin123', PASSWORD_DEFAULT);
});
<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\MapelModel;
use App\Models\NilaiUjianModel; 
use App\Models\NilaiTpModel;
use App\Models\TpModel;         
use App\Models\ReferensiTpModel;
use App\Models\CatatanWalasModel;
use App\Models\NilaiEkskulModel;
use App\Models\SekolahModel;     // Tambahkan use
use App\Models\SettingRaporModel; // Tambahkan use

class Rapor extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;
    protected $mapelModel;
    protected $nilaiModel; 
    protected $nilaiTpModel;
    protected $tpModel;
    protected $catatanModel;
    protected $ekskulModel;
    protected $sekolahModel;     // Tambahkan property
    protected $settingModel;     // Tambahkan property

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->mapelModel = new MapelModel();
        
        $this->nilaiModel = new NilaiUjianModel(); 
        $this->nilaiTpModel = new NilaiTpModel();
        $this->tpModel = new TpModel();
        $this->catatanModel = new CatatanWalasModel();
        $this->ekskulModel = new NilaiEkskulModel();
        $this->sekolahModel = new SekolahModel();
        $this->settingModel = new SettingRaporModel();
    }

    public function index()
    {
        $id_guru = session()->get('id'); // Ambil ID Guru
        $kelas_id = $this->request->getGet('kelas_id');
        
        $data = [
            'title' => 'Cetak Rapor',
            // 1. Filter: Kelas milik guru ini
            'kelas' => $this->kelasModel->where('user_id', $id_guru)->findAll(),
            'kelas_id' => $kelas_id,
            'siswa' => []
        ];

        if ($kelas_id) {
            // 2. Filter: Siswa milik guru ini
            $data['siswa'] = $this->siswaModel->where([
                'kelas_id' => $kelas_id,
                'user_id'  => $id_guru
            ])->findAll();
        }

        return view('rapor/index', $data);
    }

    public function cetak($siswa_id)
    {
        $id_guru = session()->get('id');

        // 1. AMBIL DATA IDENTITAS & SETTING (Milik Guru Ini)
        $sekolah = $this->sekolahModel->where('user_id', $id_guru)->first(); 
        $setting = $this->settingModel->where('user_id', $id_guru)->first(); 

        if(!$sekolah) {
            return redirect()->to('/datamaster/sekolah')->with('error', 'Silakan lengkapi Data Sekolah dulu.');
        }

        // Ambil Data Siswa & Kelas (Validasi Kepemilikan)
        $siswa = $this->siswaModel->where(['id' => $siswa_id, 'user_id' => $id_guru])->first();
        if (!$siswa) return redirect()->to('/rapor')->with('error', 'Siswa tidak ditemukan.');
        
        // Ambil Kelas
        $kelas = $this->kelasModel->where(['id' => $siswa['kelas_id'], 'user_id' => $id_guru])->first();

        // 2. PROSES NILAI & DESKRIPSI
        // Ambil Mapel milik guru ini (urutkan sesuai settingan)
        $semua_mapel = $this->mapelModel->where('user_id', $id_guru)->orderBy('no_urut', 'ASC')->findAll();
        
        $nilai_rapor = [];
        $total_semua_nilai = 0; 
        $jumlah_mapel_ternilai = 0;
        
        // Cek Settingan Semester (Default Ganjil jika belum diset)
        $semester_aktif = $setting['semester'] ?? 'Ganjil';

        foreach ($semua_mapel as $m) {
            // FILTER UTAMA: user_id wajib ada
            $syarat_nilai = [
                'user_id'  => $id_guru,
                'siswa_id' => $siswa_id, 
                'mapel_id' => $m['id'],
                // 'semester' => $semester_aktif // Jika tabel nilai ada kolom semester, aktifkan ini. Jika belum, abaikan dulu.
            ];
            
            // A. AMBIL NILAI SUMATIF
            $nilai_sum = $this->nilaiModel->where($syarat_nilai)->first();
            
            $sts = 0;
            $sas = 0;
            $deskripsi_manual = '';

            if ($nilai_sum) {
                $sts = $nilai_sum['nilai_sts']; 
                $sas = $nilai_sum['nilai_sas']; 
                $deskripsi_manual = $nilai_sum['deskripsi_custom'] ?? '';
            }
            
            // Logika Rata-rata Sumatif
            if ($sts > 0 && $sas > 0) $rata_sumatif = ($sts + $sas) / 2;
            elseif ($sts > 0) $rata_sumatif = $sts;
            elseif ($sas > 0) $rata_sumatif = $sas;
            else $rata_sumatif = 0;

            // B. Nilai TP (Formatif)
            $data_tp = $this->nilaiTpModel->where($syarat_nilai)->findAll();
            $total_tp = 0; $jumlah_tp = count($data_tp); $rata_tp = 0;
            $max_score = -1; $min_score = 101; $desc_max = "kompetensi ini"; $desc_min = "kompetensi ini";

            if ($jumlah_tp > 0) {
                foreach ($data_tp as $tp) {
                    $skor = $tp['nilai'];
                    $total_tp += $skor;
                    
                    // Ambil Teks Deskripsi TP (Cek milik user ini)
                    $tp_ref = $this->tpModel->where(['id' => $tp['tp_id'], 'user_id' => $id_guru])->first();
                    
                    $teks = "kompetensi";
                    if ($tp_ref) {
                         if (isset($tp_ref['deskripsi'])) $teks = $tp_ref['deskripsi'];
                         elseif (isset($tp_ref['deskripsi_tp'])) $teks = $tp_ref['deskripsi_tp'];
                    }

                    if ($skor > $max_score) { $max_score = $skor; $desc_max = $teks; }
                    if ($skor < $min_score) { $min_score = $skor; $desc_min = $teks; }
                }
                $rata_tp = $total_tp / $jumlah_tp;
            }

            // C. Hitung Nilai Akhir
            if ($rata_tp == 0 && $rata_sumatif == 0) {
                $nilai_akhir = 0;
            } else {
                // Rumus: (2 * Rata2 Formatif + Rata2 Sumatif) / 3
                $nilai_akhir = round((($rata_tp * 2) + $rata_sumatif) / 3);
            }

            // D. Rakit Deskripsi
            if (!empty($deskripsi_manual)) {
                $kalimat_deskripsi = $deskripsi_manual;
            } else {
                // Auto Generate
                $kalimat_deskripsi = "";
                $kata_dibuang = ['peserta didik mampu ', 'peserta didik dapat ', 'siswa mampu ', 'siswa dapat ', 'mampu ', 'dapat '];

                $clean_max = strtolower($desc_max);
                foreach($kata_dibuang as $sampah) { if (str_starts_with($clean_max, $sampah)) { $clean_max = substr($clean_max, strlen($sampah)); break; } }

                $clean_min = strtolower($desc_min);
                foreach($kata_dibuang as $sampah) { if (str_starts_with($clean_min, $sampah)) { $clean_min = substr($clean_min, strlen($sampah)); break; } }
                
                if ($nilai_akhir > 0) $kalimat_deskripsi .= "Menunjukkan pemahaman dalam " . $clean_max . ". ";
                if ($min_score < 75 && $min_score > 0) $kalimat_deskripsi .= "Perlu bimbingan dalam " . $clean_min . ".";
                if ($nilai_akhir == 0) $kalimat_deskripsi = "-";
            }

            if($nilai_akhir > 0) {
                $total_semua_nilai += $nilai_akhir;
                $jumlah_mapel_ternilai++;
            }

            $nilai_rapor[$m['id']] = [
                'nama_mapel' => $m['nama_mapel'],
                'kode_mapel' => $m['kode_mapel'],
                'nilai_akhir' => $nilai_akhir,
                'deskripsi' => $kalimat_deskripsi
            ];
        }

        // 3. AMBIL DATA PENDUKUNG (Ekskul & Absensi) - Filter user_id
        $ekskul = $this->ekskulModel->where(['siswa_id' => $siswa_id, 'user_id' => $id_guru])->findAll();
        $absensi = $this->catatanModel->where([
            'siswa_id' => $siswa_id, 
            'kelas_id' => $kelas['id'],
            'user_id'  => $id_guru
        ])->first();

        // LOGIKA CATATAN WALAS OTOMATIS
        $rata_rata_siswa = ($jumlah_mapel_ternilai > 0) ? $total_semua_nilai / $jumlah_mapel_ternilai : 0;
        $catatan_otomatis = "";
        
        if ($rata_rata_siswa >= 90) $catatan_otomatis = "Prestasi Ananda sangat membanggakan. Pertahankan semangat belajarnya.";
        elseif ($rata_rata_siswa >= 80) $catatan_otomatis = "Ananda menunjukkan perkembangan yang sangat baik. Teruslah rajin belajar.";
        elseif ($rata_rata_siswa >= 70) $catatan_otomatis = "Ananda sudah cukup baik dalam mengikuti pelajaran. Perbanyak latihan di rumah.";
        else $catatan_otomatis = "Ananda perlu lebih fokus dan disiplin dalam belajar.";

        if(!$absensi) $absensi = [];
        $absensi['catatan'] = (!empty($absensi['catatan']) && $absensi['catatan'] != '-') ? $absensi['catatan'] : $catatan_otomatis;

        $data = [
            'sekolah' => $sekolah,
            'setting' => $setting,
            'siswa'   => $siswa,
            'kelas'   => $kelas,
            'nilai'   => $nilai_rapor,
            'ekskul'  => $ekskul,
            'absensi' => $absensi
        ];

        return view('rapor/cetak', $data);
    }

    // --- HALAMAN PENGATURAN RAPOR ---
    public function setting()
    {
        $id_guru = session()->get('id');
        
        // Ambil data setting milik user ini
        $data = [
            'title'   => 'Pengaturan Rapor',
            'setting' => $this->settingModel->where('user_id', $id_guru)->first()
        ];
        return view('rapor/setting', $data);
    }

    // --- PROSES SIMPAN SETTING ---
    public function update_setting()
    {
        $id_guru = session()->get('id');
        $id = $this->request->getPost('id'); // ID Setting (jika ada)

        $data = [
            'user_id'       => $id_guru, // WAJIB
            'tahun_ajaran'  => $this->request->getPost('tahun_ajaran'),
            'semester'      => $this->request->getPost('semester'),
            'tanggal_rapor' => $this->request->getPost('tanggal_rapor'),
            'kota_terbit'   => $this->request->getPost('kota_terbit') ?? 'Kota Anda', // Opsional
        ];

        // Cek apakah user ini sudah punya settingan?
        $cek = $this->settingModel->where('user_id', $id_guru)->first();

        if($cek) {
            // Update
            $this->settingModel->update($cek['id'], $data);
        } else {
            // Insert
            $this->settingModel->save($data);
        }

        return redirect()->to('/rapor/setting')->with('success', 'Pengaturan Rapor berhasil diperbarui!');
    }

    // --- HALAMAN FORM EDIT DESKRIPSI ---
    public function edit_deskripsi($siswa_id)
    {
        $id_guru = session()->get('id');

        // Validasi Kepemilikan Siswa
        $siswa = $this->siswaModel->where(['id' => $siswa_id, 'user_id' => $id_guru])->first();
        if(!$siswa) return redirect()->to('/rapor');

        $kelas = $this->kelasModel->where(['id' => $siswa['kelas_id'], 'user_id' => $id_guru])->first();
        $semua_mapel = $this->mapelModel->where('user_id', $id_guru)->findAll();
        
        $data_deskripsi = [];
        
        foreach($semua_mapel as $m) {
            // Ambil data dengan filter user_id
            $nilai_ujian = $this->nilaiModel->where([
                'siswa_id' => $siswa_id, 
                'mapel_id' => $m['id'],
                'user_id'  => $id_guru
            ])->first();

            $deskripsi_tersimpan = $nilai_ujian['deskripsi_custom'] ?? '';

            // ... (Logika Generate Deskripsi Otomatis sama dengan fungsi Cetak) ...
            // Agar aman dan akurat, sebaiknya ambil Nilai TP dengan filter user_id juga
            $data_tp = $this->nilaiTpModel->where([
                'siswa_id' => $siswa_id, 
                'mapel_id' => $m['id'],
                'user_id'  => $id_guru
            ])->findAll();

            $max_score = -1; $min_score = 101; $desc_max = ""; $desc_min = "";
            
            if(!empty($data_tp)){
                 foreach ($data_tp as $tp) {
                    $skor = $tp['nilai'];
                    // Ambil referensi TP milik guru
                    $tp_ref = $this->tpModel->where(['id' => $tp['tp_id'], 'user_id' => $id_guru])->first();
                    
                    $teks = "kompetensi";
                    if ($tp_ref) {
                         if (isset($tp_ref['deskripsi'])) $teks = $tp_ref['deskripsi'];
                         elseif (isset($tp_ref['deskripsi_tp'])) $teks = $tp_ref['deskripsi_tp'];
                    }
                    if ($skor > $max_score) { $max_score = $skor; $desc_max = $teks; }
                    if ($skor < $min_score) { $min_score = $skor; $desc_min = $teks; }
                 }
            }
            
            $kata_dibuang = ['peserta didik mampu ', 'peserta didik dapat ', 'siswa mampu ', 'siswa dapat ', 'mampu ', 'dapat '];
            
            $clean_max = strtolower($desc_max);
            foreach($kata_dibuang as $sampah) { if (str_starts_with($clean_max, $sampah)) { $clean_max = substr($clean_max, strlen($sampah)); break; } }
            
            $clean_min = strtolower($desc_min);
            foreach($kata_dibuang as $sampah) { if (str_starts_with($clean_min, $sampah)) { $clean_min = substr($clean_min, strlen($sampah)); break; } }

            $kalimat_auto = "";
            if ($max_score > 0) $kalimat_auto .= "Menunjukkan pemahaman dalam " . $clean_max . ". ";
            if ($min_score < 75 && $min_score > 0) $kalimat_auto .= "Perlu bimbingan dalam " . $clean_min . ".";
            if ($kalimat_auto == "") $kalimat_auto = "-";

            $data_deskripsi[] = [
                'mapel_id' => $m['id'],
                'nama_mapel' => $m['nama_mapel'],
                'deskripsi_auto' => $kalimat_auto,
                'deskripsi_custom' => $deskripsi_tersimpan,
                'id_nilai_ujian' => $nilai_ujian['id'] ?? null 
            ];
        }

        $data = [
            'title' => 'Edit Deskripsi Rapor',
            'siswa' => $siswa,
            'kelas' => $kelas,
            'list_deskripsi' => $data_deskripsi
        ];

        return view('rapor/edit_deskripsi', $data);
    }

    // --- PROSES SIMPAN DESKRIPSI MANUAL ---
    public function simpan_deskripsi()
    {
        $id_guru = session()->get('id');
        $siswa_id = $this->request->getPost('siswa_id');
        $mapel_id = $this->request->getPost('mapel_id');
        $deskripsi_custom = $this->request->getPost('deskripsi_custom');

        // Pastikan Siswa Milik Guru Ini (Cegah Edit Paksa)
        $validSiswa = $this->siswaModel->where(['id' => $siswa_id, 'user_id' => $id_guru])->first();
        if(!$validSiswa) return redirect()->to('/rapor');

        foreach($mapel_id as $key => $id_mapel) {
            $teks_baru = $deskripsi_custom[$key];
            
            // Cek data nilai (Filter user_id)
            $cek = $this->nilaiModel->where([
                'siswa_id' => $siswa_id, 
                'mapel_id' => $id_mapel,
                'user_id'  => $id_guru
            ])->first();
            
            if($cek) {
                // Update
                $this->nilaiModel->update($cek['id'], ['deskripsi_custom' => $teks_baru]);
            } else {
                // Insert Baru (Wajib ada user_id)
                $this->nilaiModel->save([
                    'user_id'  => $id_guru,
                    'siswa_id' => $siswa_id,
                    'mapel_id' => $id_mapel,
                    'deskripsi_custom' => $teks_baru
                ]);
            }
        }
        
        return redirect()->to('/rapor/edit_deskripsi/'.$siswa_id)->with('success', 'Deskripsi berhasil disimpan!');
    }
}
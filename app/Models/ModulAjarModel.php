<?php

namespace App\Models;

use CodeIgniter\Model;

class ModulAjarModel extends Model
{
    protected $table            = 'modul_ajar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Fitur Keamanan: Field yang diizinkan untuk diinput/update
    // REVISI: Menambahkan semua kolom baru agar data tersimpan
    protected $allowedFields    = [
        // 1. Identitas & Relasi
        'user_id', 
        'sekolah', 
        'kepala_sekolah', 
        'nip_ks',
        'mapel', 
        'fase', 
        'kelas', 
        'tp_id',              // BARU: Relasi ke Referensi TP
        'materi', 
        
        // 2. Setting Pembelajaran
        'alokasi_waktu', 
        'jumlah_pertemuan',   // BARU: Input jumlah pertemuan
        'model_belajar', 
        'profil_pancasila',   
        
        // 3. Komponen Inti (Deep Learning)
        'tujuan_pembelajaran', 
        'identifikasi_murid',    // PENTING: Kemarin ini kosong karena belum didaftarkan
        'pemahaman_bermakna',
        'kerangka_pembelajaran', // BARU: JSON Deep Learning
        'kegiatan_inti',         
        'media_pembelajaran',
        'asesmen_deskripsi',     // BARU: JSON Deskripsi Asesmen
        
        // 4. Lampiran & Output Tahap 2 (Fitur Baru)
        'materi_pembelajaran',   // Legacy (tetap simpan)
        'asesmen_formatif',      // Legacy (tetap simpan)
        'asesmen_sumatif',       // Legacy (tetap simpan)
        
        'lampiran_materi',       // BARU: Hasil Generate Tahap 2
        'lampiran_lkpd',         // BARU: Hasil Generate Tahap 2
        'lampiran_asesmen'       // BARU: Hasil Generate Tahap 2
    ];

    // Auto Timestamp (Otomatis isi created_at & updated_at)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------------------------------------------------------
    // CUSTOM METHODS KHUSUS SAAS (ISOLASI DATA)
    // -------------------------------------------------------------------------

    /**
     * Mengambil daftar modul HANYA milik user yang sedang login.
     */
    public function getAllByUser($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Mengambil SATU modul detail, dengan validasi kepemilikan.
     */
    public function getOneByUser($modulId, $userId)
    {
        return $this->where('id', $modulId)
                    ->where('user_id', $userId)
                    ->first();
    }

    /**
     * Cek kepemilikan sebelum Update/Delete
     */
    public function isOwner($modulId, $userId)
    {
        $count = $this->where('id', $modulId)
                      ->where('user_id', $userId)
                      ->countAllResults();
        
        return $count > 0;
    }
}
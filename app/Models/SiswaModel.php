<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // DAFTAR KOLOM YANG BOLEH DIISI (Sesuai Tabel Baru)
    protected $allowedFields    = [
        'user_id',
        'kelas_id', 
        'nis', 'nisn', 'nama_lengkap', 'jenis_kelamin',
        'tempat_lahir', 'tanggal_lahir', 'agama', 'pendidikan_sebelumnya',
        'alamat_peserta_didik', 'desa', 'kecamatan', 'kota', 'propinsi',
        'nama_ayah', 'nama_ibu', 'pekerjaan_ayah', 'pekerjaan_ibu',
        'nama_wali', 'pekerjaan_wali', 'alamat_wali', 'no_telephone',
        'status_aktif'
    ];

    protected $useTimestamps = true;
}
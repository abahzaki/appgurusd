<?php

namespace App\Models;

use CodeIgniter\Model;

class SekolahModel extends Model
{
    protected $table            = 'sekolah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'user_id','nama_sekolah', 'npsn', 'nss', 'alamat_sekolah', 'kode_pos',
        'telepon', 'kelurahan', 'kecamatan', 'kabupaten_kota', 'provinsi', 'telepon', 'website', 'email', 'nama_kepsek', 'nip_kepsek'
    ];
    protected $useTimestamps = true;
}
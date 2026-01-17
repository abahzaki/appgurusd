<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiUjianModel extends Model
{
    protected $table            = 'nilai_ujian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'siswa_id', 'kelas_id', 'mapel_id', 'nilai_sts', 'nilai_sas', 'deskripsi_custom', 'semester', 'tahun_ajaran'];
    protected $useTimestamps    = true;
}
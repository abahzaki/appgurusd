<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiEkskulModel extends Model
{
    protected $table            = 'nilai_ekskul';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'siswa_id', 'kelas_id', 'nama_ekskul', 'predikat', 'keterangan'];
    protected $useTimestamps    = true;
}
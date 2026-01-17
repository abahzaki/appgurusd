<?php

namespace App\Models;

use CodeIgniter\Model;

class CatatanWalasModel extends Model
{
    protected $table            = 'catatan_walas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'siswa_id', 'kelas_id', 'sakit', 'izin', 'alpha', 'catatan', 'status_naik'];
    protected $useTimestamps    = true;
}
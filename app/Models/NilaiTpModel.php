<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiTpModel extends Model
{
    protected $table            = 'nilai_tp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'siswa_id', 'kelas_id', 'mapel_id', 'tp_id', 'nilai', 'semester', 'tahun_ajaran'];
    protected $useTimestamps    = true;

    // Fungsi Khusus untuk Cek apakah Siswa A sudah punya nilai di TP B?
    public function getNilaiSiswa($siswa_id, $tp_id)
    {
        return $this->where(['siswa_id' => $siswa_id, 'tp_id' => $tp_id])->first();
    }
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class TpModel extends Model
{
    protected $table            = 'tujuan_pembelajaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'mapel_id', 'kelas_id', 'kode_tp', 'deskripsi_tp'];
    protected $useTimestamps    = true;

    // Fitur Join tabel agar kita bisa ambil Nama Mapel & Nama Kelas
    public function getLengkap()
    {
        return $this->select('tujuan_pembelajaran.*, mapel.nama_mapel, kelas.nama_kelas')
                    ->join('mapel', 'mapel.id = tujuan_pembelajaran.mapel_id')
                    ->join('kelas', 'kelas.id = tujuan_pembelajaran.kelas_id')
                    ->orderBy('mapel.id', 'ASC')
                    ->orderBy('kode_tp', 'ASC')
                    ->findAll();
    }
}
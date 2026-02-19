<?php

namespace App\Models;

use CodeIgniter\Model;

class AtpHistoryModel extends Model
{
    protected $table            = 'atp_history';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_id', 'mapel_id', 'kelas_id', 'tahun_ajar', 'content_sem1', 'content_sem2'];

    // Helper untuk mengambil data lengkap dengan nama mapel & kelas
    public function getHistoryByUser($userId)
    {
        return $this->select('atp_history.*, mapel.nama_mapel, kelas.nama_kelas, kelas.fase')
                    ->join('mapel', 'mapel.id = atp_history.mapel_id')
                    ->join('kelas', 'kelas.id = atp_history.kelas_id')
                    ->where('atp_history.user_id', $userId)
                    ->orderBy('atp_history.id', 'DESC')
                    ->findAll();
    }
}
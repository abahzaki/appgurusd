<?php

namespace App\Models;

use CodeIgniter\Model;

class ReferensiTpModel extends Model
{
    protected $table            = 'referensi_tp';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['mapel_id', 'fase', 'kode_tp', 'deskripsi_tp'];
    
    // Helper untuk mengambil TP berdasarkan Fase (untuk Dropdown nanti)
    public function getByFase($fase)
    {
        return $this->where('fase', $fase)->findAll();
    }
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class ReferensiCpModel extends Model
{
    protected $table            = 'referensi_cp';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['mapel_id', 'fase', 'elemen', 'deskripsi_cp'];

    // Fungsi khusus untuk mengambil CP per mapel & fase
    public function getCpByMapelFase($mapel_id, $fase)
    {
        return $this->where('mapel_id', $mapel_id)
                    ->where('fase', $fase)
                    ->orderBy('id', 'ASC') // Urutkan biar rapi
                    ->findAll();
    }
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingRaporModel extends Model
{
    protected $table            = 'setting_rapor';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_id', 'tahun_ajaran', 'semester', 'tanggal_rapor'];
}
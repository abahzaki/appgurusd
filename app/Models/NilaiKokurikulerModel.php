<?php
namespace App\Models;
use CodeIgniter\Model;

class NilaiKokurikulerModel extends Model
{
    protected $table = 'nilai_kokurikuler';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'siswa_id', 'kokurikuler_id', 'dimensi_id', 'skor'];
    protected $useTimestamps = true;
}
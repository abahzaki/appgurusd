<?php
namespace App\Models;
use CodeIgniter\Model;

class KokurikulerModel extends Model
{
    protected $table = 'kokurikuler';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'kelas_id', 'nama_projek', 'deskripsi'];
    protected $useTimestamps = true;
}
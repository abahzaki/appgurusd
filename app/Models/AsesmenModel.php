<?php namespace App\Models;
use CodeIgniter\Model;

class AsesmenModel extends Model {
    protected $table = 'asesmen';
    protected $allowedFields = ['user_id', 'mapel_id', 'kelas_id', 'jenis_ujian', 'judul', 'konten_json'];
    protected $returnType = 'object';
}
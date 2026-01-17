<?php
namespace App\Models;
use CodeIgniter\Model;

class DimensiModel extends Model
{
    protected $table = 'ref_dimensi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nama_dimensi'];
}
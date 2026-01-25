<?php

namespace App\Models;

use CodeIgniter\Model;

class AdExpenseModel extends Model
{
    protected $table            = 'ad_expenses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Kolom yang boleh diisi user
    protected $allowedFields    = ['tanggal', 'platform', 'amount'];

    // Aktifkan timestamp agar kita tahu kapan data diinput
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // Kita tidak butuh updated_at untuk log biaya
}
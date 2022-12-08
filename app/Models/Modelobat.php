<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelobat extends Model
{
    protected $table = 'obat';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id','nama','harga','jenis'
    ];
}
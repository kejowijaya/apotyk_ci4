<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelreservasi extends Model
{
    protected $table = 'reservasi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id','tanggal','sesi','dokter','keterangan'
    ];
}

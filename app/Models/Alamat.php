<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'alamat';
    protected $fillable = [
        'detail_alamat',
        'id_kelurahan',
        'kode_pos',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengalaman extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'pengalaman';
    protected $fillable = [
        'nama_instansi',
        'durasi_waktu',    
    ];
}

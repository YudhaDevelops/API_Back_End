<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relawan_Pengalaman extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'relawan_pengalaman';
    protected $fillable = [
        'id_relawan',
        'id_pengalaman',
    ];
}

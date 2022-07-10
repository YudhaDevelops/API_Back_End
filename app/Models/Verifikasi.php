<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'verifikasi';
    protected $fillable = [
        'id_admin',
        'id_relawan',
        'status_verifikasi',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relawan_Keahlian extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'relawan_keahlian';
    protected $fillable = [
        'id_relawan',
        'id_keahlian',
    ];
    
}

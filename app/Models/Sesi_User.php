<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesi_User extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'sesi_user';
    protected $fillable = [
        'tokens',
    ];
}

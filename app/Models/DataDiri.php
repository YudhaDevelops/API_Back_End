<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDiri extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'data_diri';
    
    protected $fillable = [
        'id_user',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_telepon',
        'alamat_ktp',
        'alamat_domisili',
        'gol_darah',
        'kondisi_disabilitas',
        'penyakit',
        'status_vaksin',
        'pernah_bekerja_sama',
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'instansi',
        'file_pelatihan',
        'created_at',
        'updated_at',
    ];
}

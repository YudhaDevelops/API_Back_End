<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Keahlian;
class KeahlianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Keahlian::create([
            'jenis_keahlian' => 'Programer',
            
        ]);
 
        Keahlian::create([
            'jenis_keahlian' => 'Video Editor',
            
        ]);

        Keahlian::create([
            'jenis_keahlian' => 'Gammer',
            
        ]);
 
        Keahlian::create([
            'jenis_keahlian' => 'Photo Grafer',
            
        ]);
    }
}

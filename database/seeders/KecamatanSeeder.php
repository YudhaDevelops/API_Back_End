<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kecamatan')->delete();

        $csvFile = fopen(base_path("database/data/districts.csv"), "r");
  
        $firstline = false;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Kecamatan::create([
                    "id" => $data['0'],
                    "id_kabupaten" => $data['1'],
                    "nama" => $data['2']
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}

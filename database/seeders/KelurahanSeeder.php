<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kelurahan')->delete();

        $csvFile = fopen(base_path("database/data/villages.csv"), "r");
  
        $firstline = false;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Kelurahan::create([
                    "id" => $data['0'],
                    "id_kecamatan" => $data['1'],
                    "nama" => $data['2']
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kabupaten')->delete();

        $csvFile = fopen(base_path("database/data/regencies.csv"), "r");
  
        $firstline = false;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Kabupaten::create([
                    "id" => $data['0'],
                    "id_provinsi" => $data['1'],
                    "nama" => $data['2']
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}

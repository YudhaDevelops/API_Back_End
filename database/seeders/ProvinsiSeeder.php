<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provinsi')->delete();
  
        $csvFile = fopen(base_path("database/data/provinces.csv"), "r");
  
        $firstline = false;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Provinsi::create([
                    "id" => $data['0'],
                    "nama" => $data['1'],
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(ProvinceTableSeeder::class);
          $this->call(MunicipalitySeeder::class);
          $this->call(BarangayTableSeeder::class);
    }
}

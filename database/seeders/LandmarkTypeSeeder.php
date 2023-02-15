<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LandmarkType;

class LandmarkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        LandmarkType::truncate();
        DB::table('landmark_types')->insert([
          ['name' => 'Mosque'],
          ['name' => 'Tomb'],
          ['name' => 'Fortress'],
          ['name' => 'Bathhouse'],
        ]);
    }
}

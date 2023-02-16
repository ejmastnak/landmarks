<?php

namespace Database\Seeders;

use App\Models\Landmark;
use App\Models\LandmarkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LandmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('landmarks')->delete();
        DB::table('landmark_types')->delete();

        $json = Storage::disk('local')->get('/json/landmarks.json');
        $landmarks = json_decode($json, true);

        foreach ($landmarks['landmarks'] as $landmark) {

            // Name of landmark type, e.g. "Mosque", "Fortress", ...
            $type_name = $landmark['type'];

            // Try to find matching LandmarkType database record
            $type = LandmarkType::where('name', $type_name)->first();

            // Create new LandmarkType record if none exists
            if (!$type) {
                $type = LandmarkType::create(['name' => $type_name]);
            }

            Landmark::create([
                'name' => $landmark['name'],
                'landmark_type_id' => $type->id,
                'city' => $landmark['city'],
                'country' => $landmark['country'],
                'comment' => $landmark['comment'] == '' ? null : $landmark['comment'],
                'link' => $landmark['link'] == '' ? null : $landmark['link'],
            ]);
        }
    }
}

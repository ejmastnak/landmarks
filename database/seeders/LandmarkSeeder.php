<?php

namespace Database\Seeders;

use App\Models\Landmark;
use App\Models\LandmarkType;
use App\Models\Country;
use App\Models\User;
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
        DB::table('countries')->delete();

        $admin = User::where('name', 'admin')->first();
        $json = Storage::disk('local')->get('/json/landmarks.json');
        $landmarks = json_decode($json, true);

        foreach ($landmarks as $landmark) {

            // Name of landmark type, e.g. "Mosque", "Fortress", ...
            $type_name = $landmark['landmark_type'];
            // Try to find matching LandmarkType database record
            $type = LandmarkType::where('name', $type_name)->first();
            // Create new LandmarkType record if none exists
            if (!$type) {
                $type = LandmarkType::create(['name' => $type_name]);
            }

            // Same story for country
            $country_name = $landmark['country'];
            $country = Country::where('name', $country_name)->first();
            if (!$country) {
                $country = Country::create(['name' => $country_name]);
            }

            Landmark::create([
                'name' => $landmark['name'],
                'landmark_type_id' => $type->id,
                'city' => $landmark['city'],
                'country_id' => $country->id,
                'comment' => $landmark['comment'] == '' ? null : $landmark['comment'],
                'user_id' => $admin->id,
            ]);
        }
    }
}

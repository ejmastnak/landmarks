<?php

namespace Database\Seeders;

use App\Models\Landmark;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class LandmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Landmark::truncate();
        $json = Storage::disk('local')->get('/json/landmarks.json');
        $landmarks = json_decode($json, true);

        foreach ($landmarks['landmarks'] as $landmark) {
            Landmark::create([
                'name' => $landmark['name'],
                'type' => $landmark['type'],
                'city' => $landmark['city'],
                'country' => $landmark['country'],
                'comment' => $landmark['comment'] == '' ? null : $landmark['comment'],
                'link' => $landmark['link'] == '' ? null : $landmark['link'],
            ]);
        }
    }
}

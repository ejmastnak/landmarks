<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create([
        'name' => 'admin',
        'email' => 'admin@ejmastnak.com',
        'password' => '$2y$10$QAE1Dj/HEsv99jGhzSyJv.6bmUSgSnOBzIHDsufrhnWsLZCUqMY2i',
        'can_create' => true,
        'can_edit' => true,
        'can_delete' => true,
        ]);

        User::create([
        'name' => 'Martin Mastnak',
        'email' => 'martin.mastnak3@gmail.com',
        'password' => '$2y$10$KAsviXhhB0VGxm79PWjsvOxha.IihX2ymL0qli.sf8DWbn12nDZiu',
        'can_create' => true,
        'can_edit' => true,
        'can_delete' => true,
        ]);

    }
}

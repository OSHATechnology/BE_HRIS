<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $team = [
            [
                'teamId' => 1,
                'name' => 'Developer',
                'leadBy' => 1,
                'createdBy' => 1,
                'created_at' => now(),
                'updated_at' => now()

            ],
        ];

        DB::table('teams')->insert($team);
    }
}

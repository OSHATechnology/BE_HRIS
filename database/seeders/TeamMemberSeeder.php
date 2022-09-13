<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
            [
                'memberId' => 1,
                'teamId' => 1, 
                'empId' => 2, 
                'assignedBy' => 1, 
                'joinedAt' => now(),
                'created_at' => now(),
                'updated_at' => now()
                
            ],
        ];

        DB::table('team_members')->insert($teams);
    }
}

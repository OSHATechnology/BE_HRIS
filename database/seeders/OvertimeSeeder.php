<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OvertimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $overtime = [
            [
                'overtimeId' => 1,
                'employeeId' => 1,
                'startAt' => now(),
                'endAt' => now(),
                'assignedBy' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
        ];

        DB::table('overtimes')->insert($overtime);
    }
}

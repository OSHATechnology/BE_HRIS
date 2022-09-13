<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkPermitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $workPermit = [
            [
                'workPermitId' => 1, 
                'employeeId' => 2, 
                'startAt' => now(), 
                'endAt' => now(), 
                'isConfirmed' => true,
                'confirmedBy' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
        ];

        DB::table('work_permits')->insert($workPermit);
    }
}

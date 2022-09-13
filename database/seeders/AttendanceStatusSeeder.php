<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attd_status = [
            [
                'attendanceStatusId' => 1,
                'status' => 'work',
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'attendanceStatusId' => 2,
                'status' => 'furlough',
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'attendanceStatusId' => 3,
                'status' => 'work permit',
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'attendanceStatusId' => 4,
                'status' => 'overtime',
                'created_at' => now(),
                'updated_at' => now()
                
            ],
        ];

        DB::table('attendance_statuses')->insert($attd_status);
    }
}

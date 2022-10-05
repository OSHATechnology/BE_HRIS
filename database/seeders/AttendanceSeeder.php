<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $attendance = [
        //     [
        //         'attendId' => 1, 
        //         'employeeId' => 1, 
        //         'attendanceStatusId' => 1, 
        //         'submitedAt' => now(),
        //         'submitedById' => 1,
        //         'typeInOut' => 'In', 
        //         'timeAttend' => now(), 
        //         'created_at' => now(), 
        //         'updated_at' => now()
        //     ],
        //     [
        //         'attendId' => 2, 
        //         'employeeId' => 1, 
        //         'attendanceStatusId' => 1, 
        //         'submitedAt' => now(),
        //         'submitedById' => 1,
        //         'typeInOut' => 'Out', 
        //         'timeAttend' => now(), 
        //         'created_at' => now(), 
        //         'updated_at' => now()
        //     ]
        // ];

        // DB::table('attendances')->insert($attendance);
        Attendance::factory()->count(100)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
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

        // return DB::table('attendances')->insert($attendance);
        // Attendance::factory()->count(100)->create();

        $attendances = [];

        $daysWorking = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        $dateWorking = [];
        $payroll_date = '24';
        $month = "10-2022";
        $monthPayroll = date('Y-m-d', strtotime($payroll_date . '-' . $month));
        $firstDatePayroll = date('Y-m-d', strtotime($monthPayroll . " -1 month"));
        $dateArray = $this->getDates($firstDatePayroll, $monthPayroll);
        for ($i = 0; $i < count($dateArray); $i++) {
            $date = $dateArray[$i];
            $day = date('D', strtotime($date));
            if (in_array($day, $daysWorking)) {
                $dateWorking[] = $date;
            } else {
                $dateOff[] = $date;
            }
        }

        $index = 1;

        $Employees = Employee::all();
        foreach ($Employees as $item) {
            foreach ($dateWorking as $value) {
                $in = [
                    'attendId' => $index++,
                    'employeeId' => $item->employeeId,
                    'attendanceStatusId' => 1,
                    'submitedAt' => now(),
                    'submitedById' => $item->employeeId,
                    'typeInOut' => 'In',
                    'timeAttend' => date('Y-m-d H:i:s', strtotime($value . ' 7:00:00')),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $out = [
                    'attendId' => $index++,
                    'employeeId' => $item->employeeId,
                    'attendanceStatusId' => 1,
                    'submitedAt' => now(),
                    'submitedById' => $item->employeeId,
                    'typeInOut' => 'Out',
                    'timeAttend' => date('Y-m-d H:i:s', strtotime($value . ' 17:00:00')),
                    'created_at' => now(),
                    'updated_at' => now()
                ];


                $attendancedata = [
                    $in,
                    $out
                ];


                DB::table('attendances')->insert($attendancedata);
            }
        }
    }

    public function getDates($startDate, $stopDate)
    {
        $dates = [];
        $currentDate = $startDate;
        while (strtotime($currentDate) <= strtotime($stopDate)) {
            $dates[] = $currentDate;
            $currentDate = date('Y-m-d', strtotime($currentDate . ' + 1 day'));
        }
        return $dates;
    }
}

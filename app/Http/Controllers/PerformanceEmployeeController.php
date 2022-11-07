<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\PerformanceEmployeeResource;
use App\Models\Attendance;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

class PerformanceEmployeeController extends BaseController
{
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

    public function attendancePerformance(Request $request, $id)
    {
        try {
            if ($request->month != null) {
                $date = $request->month;
            } else {
                $date = date('Y-m', strtotime(now()));
            }
            $arrayDate = explode('-', $date);
            $month = $arrayDate[1];
            $year = $arrayDate[0];
            $startDate = $date . '-1';
            $endDate = $date . '-' . cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $attendanceArray = Attendance::where('employeeId', $id)
                ->whereBetween('timeAttend', [$startDate, $endDate])
                ->Where(function ($query) {
                    $query->where('attendanceStatusId', 1);
                })->Where(function ($query) {
                    $query->where('typeInOut', 'in');
                })
                ->get();

            $daysWorking = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
            $dateWorking = [];
            $dateOff = [];

            $dateArray = $this->getDates($startDate, $endDate);
            for ($i = 0; $i < count($dateArray); $i++) {
                $date = $dateArray[$i];
                $day = date('D', strtotime($date));
                if (in_array($day, $daysWorking)) {
                    $dateWorking[] = $date;
                } else {
                    $dateOff[] = $date;
                }
            }

            $dateAttendance = [];
            for ($j = 0; $j < count($attendanceArray); $j++) {
                $dataAttendance = $attendanceArray[$j];
                $dateCheck = date('Y-m-d', strtotime($dataAttendance->timeAttend));
                $day = date('Y-m-d', strtotime($dateCheck));
                if (in_array($day, $dateWorking)) {
                    $dateAttendance[] = $dataAttendance;
                }
            }

            $result = [];
            for ($k = 0; $k < count($dateWorking); $k++) {
                if ($k < count($dateAttendance)) {
                    if (in_array(date('Y-m-d', strtotime($dateAttendance[$k]->timeAttend)), $dateWorking)) {
                        $tmp = [
                            'date' => $dateWorking[$k],
                            'status' => true,
                            'timestamp' => $dateAttendance[$k]->timeAttend
                        ];
                    }
                } else {
                    $tmp = [
                        'date' => $dateWorking[$k],
                        'status' => false,
                        'timestamp' => '-'
                    ];
                }
                array_push($result, $tmp);
            }
            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => "performance retrieved successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => "error retrieving performance"
            ]);
        }
    }

    public function myAttendancePerformance(Request $request)
    {
        return $this->attendancePerformance($request, auth()->user()->id);
    }
}

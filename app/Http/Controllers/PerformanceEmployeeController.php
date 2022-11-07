<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\PerformanceEmployeeResource;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            if ($request->yearMonth != null) {
                $date = $request->yearMonth;
            } else {
                $date = date('Y-m', strtotime(now()));
            }
            $arrayDate = explode('-', $date);
            $month = $arrayDate[1];
            $year = $arrayDate[0];
            $startDate = $date . '-01';
            $endDate = $date . '-' . cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $attendanceArray = Attendance::where('employeeId', $id)
                ->whereBetween('timeAttend', [$startDate, $endDate])
                ->Where(function ($query) {
                    $query->where('attendanceStatusId', 1);
                })->Where(function ($query) {
                    $query->where('typeInOut', 'in');
                })
                ->get();

            $attendTime = [];
            foreach ($attendanceArray as $key => $value) {
                $attendTime[date('Y-m-d', strtotime($value->timeAttend))] =  $value->timeAttend;
            }

            $daysWorking = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
            $dateWorking = [];

            $dateArray = $this->getDates($startDate, $endDate);
            for ($i = 0; $i < count($dateArray); $i++) {
                $date = $dateArray[$i];
                $day = date('D', strtotime($date));
                if (in_array($day, $daysWorking)) {
                    $dateWorking[] = $date;
                }
            }

            $result = [];
            foreach ($dateWorking as $value) {
                if (in_array($value, array_keys($attendTime))) {
                    $result[] = [
                        'date' => $value,
                        'status' => true,
                        'timestamp' => $attendTime[$value]
                    ];
                } else {
                    $result[] = [
                        'date' => $value,
                        'status' => false,
                        'timestamp' => null
                    ];
                }
            }
            return $this->sendResponse($result, 'Get attendance performance successfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 'Get attendance performance failed');
        }
    }

    public function myAttendancePerformance(Request $request)
    {
        return $this->attendancePerformance($request, Auth::user()->employeeId);
    }
}

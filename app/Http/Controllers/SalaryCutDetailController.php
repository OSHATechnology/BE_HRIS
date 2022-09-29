<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Salary;
use App\Models\SalaryCutDetail;
use Illuminate\Http\Request;

class SalaryCutDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function attendanceCutFee($id)
    {
        $date = request('date');
        $str = explode("-", $date);
        $month = $str[0];
        $year = $str[1];
        $totalDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $totalAttendance = Attendance::where('employeeId', $id)
                                        ->where('attendanceStatusId', 1)
                                        ->get();
        $percentAttendance = round((count($totalAttendance) / $totalDay) * 100);
        $basicEmp = Salary::where('empId', $id)->first();
        $gross = $basicEmp->gross;
        $attendance_fee = ($gross * $percentAttendance) / 100;
        dd($attendance_fee);
        return $attendance_fee;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalaryCutDetail  $salaryCutDetail
     * @return \Illuminate\Http\Response
     */
    public function show(SalaryCutDetail $salaryCutDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalaryCutDetail  $salaryCutDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(SalaryCutDetail $salaryCutDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalaryCutDetail  $salaryCutDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalaryCutDetail $salaryCutDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalaryCutDetail  $salaryCutDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalaryCutDetail $salaryCutDetail)
    {
        //
    }
}

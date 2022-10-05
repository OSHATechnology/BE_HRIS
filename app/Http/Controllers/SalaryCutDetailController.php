<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\SalaryCutDetailResource;
use App\Models\Attendance;
use App\Models\Instalment;
use App\Models\Salary;
use App\Models\SalaryCutDetail;
use App\Support\Collection;
use Illuminate\Http\Request;

class SalaryCutDetailController extends BaseController
{
    const VALIDATION_RULES = [
        'salaryId' => 'required|integer',
        'loanId' => 'required|integer',
        'etc' => 'required|integer',
    ];

    const NumPaginate = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $salaryCut = (new Collection(SalaryCutDetailResource::collection(SalaryCutDetail::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($salaryCut, "salary cut detail retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error retrieving salary cut detail");
        }
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $now = date('Y-m');
            $str = explode("-", $now);
            $year = $str[0];
            $month = $str[1];
            $totalDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $salary = Salary::findOrFail($request->salaryId);
            $totalAttendance = Attendance::where('employeeId', $salary->empId)
                                            ->where('attendanceStatusId', 1)
                                            ->where('typeInOut', "in")
                                            ->get();
            $percentAttendance = round((count($totalAttendance) / $totalDay) * 100);
            $basicEmp = Salary::where('empId', $salary->empId)->first();
            $gross = $basicEmp->gross;
            $attendance_fee = ($gross * $percentAttendance) / 100;
            
            $this->validate($request, self::VALIDATION_RULES);
            $salaryCut = new SalaryCutDetail;
            $salaryCut->salaryId = $request->salaryId;
            $salaryCut->totalAttendance = count($totalAttendance);
            $salaryCut->attdFeeReduction = $attendance_fee;
            $salaryCut->loanId = $request->loanId;
            $salaryCut->etc = $request->etc;
            $instalmentNominal = Instalment::getLastNominal($request->loanId);
            $total = $attendance_fee + $request->etc;
            $salaryCut->total = $total;
            $salaryCut->net = $gross - $total - $instalmentNominal;
            $salaryCut->save();
            return $this->sendResponse(new SalaryCutDetailResource($salaryCut), "salary cut detail created successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error creating salary cut detail", $th->getMessage());
        }
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
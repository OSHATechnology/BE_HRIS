<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\SalaryGrossResources;
use App\Http\Resources\SalaryResource;
use App\Models\Attendance;
use App\Models\BasicSalaryByEmployee;
use App\Models\BasicSalaryByRole;
use App\Models\Employee;
use App\Models\EmployeeFamily;
use App\Models\Insurance;
use App\Models\InsuranceItem;
use App\Models\Overtime;
use App\Models\Salary;
use App\Models\SalaryInsuranceDetail;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryController extends BaseController
{
    const VALIDATION_RULES = [
        'empId' =>  "required|integer",
        'bonus' => "required|integer",
    ];

    const NumPaginate = 10;
    const feeOneHour = 100000;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // try {
        //     $salary = (new Collection(SalaryResource::collection(Salary::all())))->paginate(self::NumPaginate);
        //     return $this->sendResponse($salary, "salary retrieved successfully");
        // } catch (\Throwable $th) {
        //     return $this->sendError("error retrieving salary", $th->getMessage());
        // }

        try {
            $type = $request->type ?? 'gross';
            $month = $request->month ?? date('m-Y');
            $year = date('Y', strtotime("01-" . $month));
            $salaryDate = 1;
            $monthNow = date('Y-m-d');

            if ($month !== date('m-Y', strtotime($monthNow))) {
                return $this->sendError("error retrieving salary", "on development");
            }

            $firstDay = date('Y-m-01');
            $lastDay = date('Y-m-t');

            $employees = Employee::all();

            $SalaryGrossEmployees = [];

            foreach ($employees as $key => $emp) {
                if ($emp->basic_salary != null) {
                    $basicRole = $emp->role->basic_salary->fee ?? 0;
                    $basicSalary = $basicRole + $emp->basic_salary->fee;
                } else {
                    $basicSalary = $emp->role->basic_salary->fee ?? 0;
                }

                $totalOvertime = $this->getTotalOvertime($emp->employeeId, $firstDay, $lastDay);
                $overtimeFee = $totalOvertime * self::feeOneHour;
                $bonus = 0;
                $gross = $basicSalary + $overtimeFee + $bonus;
                $SalaryGrossEmployees[$key] = [
                    'empId' => $emp->employeeId,
                    'empName' => $emp->firstName . ' ' . $emp->lastName,
                    'salaryDate' => $month,
                    'basicSalary' => $basicSalary,
                    'totalOvertime' => $totalOvertime,
                    'overtimeFee' => $overtimeFee,
                    'totalBonus' => $bonus,
                    'total' => $gross,
                ];
            }

            if ($type === 'deduction') {
                $dataDeduction = $this->getDeduction($month, $SalaryGrossEmployees);
            }

            $data = [
                'type' => $type,
                'salaryDate' => date('M Y', strtotime("01-" . $month . "-" . $year)),
                'data' => '',
            ];
            switch ($type) {
                default:
                    $data['data'] = $SalaryGrossEmployees;
                    break;
            }

            return $this->sendResponse($data, "salary retrieved successfully");
            // dd("asd");
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTotalOvertime($empId, $firstDay, $lastDay)
    {
        $totalOvertime = 0;
        $overtimes = Overtime::where('employeeId', $empId)->where('isConfirmed', 1)->get();
        foreach ($overtimes as $key => $overtime) {
            $startAt = new \DateTime($overtime->startAt);
            $endAt = new \DateTime($overtime->endAt);
            $totalOvertime += $startAt->diff($endAt)->h;
        }
        return $totalOvertime;
    }

    public function getDeduction($month, $GrossEmployee)
    {
        $employees = Employee::all();
        $payroll_date = 31;
        $monthPayroll = date('Y-m-d', strtotime($payroll_date . '-' . $month));
        $endDate = date('t', strtotime($payroll_date));
        if ($payroll_date > 1) {
            $isAMonth = !true;
        } else {
            $isAMonth = true;
        }

        dd($isAMonth);
        $firstDatePayroll = date('Y-m-d', strtotime(date('Y-m-' . $payroll_date, strtotime(date('Y-m', strtotime($monthPayroll . " -1 month")))) . " -1 day"));
        $dataDeduction = [];
        $dataAttendance = [];
        $daysWorking = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];

        for ($i = 1; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime($firstDatePayroll . " +$i day"));
            $day = date('D', strtotime($date));
            if (in_array($day, $daysWorking)) {
                $dataAttendance[] = [
                    'date' => $date,
                    'attend' => []
                ];
            }
        }

        // dd($monthPayroll, $firstDatePayroll, $dataAttendance);

        foreach ($employees as $key => $value) {
            $totalAttendance = 0;
            $totalLeave = 0;
            $totalLate = 0;
            $totalAbsent = 0;
            $totalLoan = 0;
            $totalTax = 0;
            $totalInsurance = 0;
            $totalDeduction = 0;

            // if($isAMonth){

            // }

            // $value->totalAttendance = $dataAttendance;
        }

        dd($employees);
    }

    public function automatic_data($id)
    {
        try {
            $emp = Employee::findOrFail($id);
            $overtime = Overtime::where('employeeId', $id)->first();
            $start = date('H', strtotime($overtime->startAt));
            $end = date('H', strtotime($overtime->endAt));
            $hour = $end - $start;
            $basicEmp = BasicSalaryByEmployee::where('empId', $id)->first();
            if ($basicEmp !== null) {
                $basicByRole = BasicSalaryByRole::find($basicEmp->basicSalaryByRoleId);
                $basicSalary = $basicByRole->fee + $basicEmp->fee;
            } else {
                $basicByRole = BasicSalaryByRole::where('roleId', $emp->roleId)->first();
                $basicSalary = $basicByRole->fee;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'idEmp' => $id,
                    'employee' => $emp->firstName . " " . $emp->lastName,
                    'basic' => $basicByRole->fee,
                    'totalOvertime' => $hour,
                    'overtimeFee' => $hour * 100000
                ],
                'message' => 'success retrieved data',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $th->getMessage(),
            ]);
        }
        // dd(false);
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
            $emp = Employee::findOrFail($request->empId);
            $overtime = Overtime::where('employeeId', $request->empId)
                ->where('startAt', 'like', $now . '%')
                ->get();
            $totalHour = 0;
            $hour = 0;
            if (count($overtime) !== 0) {
                for ($i = 0; $i <= count($overtime) - 1; $i++) {
                    $start = date('H', strtotime($overtime[$i]->startAt));
                    $end = date('H', strtotime($overtime[$i]->endAt));
                    $hour = $end - $start;
                    $totalHour = $totalHour + $hour;
                }
            }
            $basicEmp = BasicSalaryByEmployee::where('empId', $request->empId)->first();
            if ($basicEmp !== null) {
                $basicByRole = BasicSalaryByRole::find($basicEmp->basicSalaryByRoleId);
                $basicSalary = $basicByRole->fee + $basicEmp->fee;
            } else {
                $basicByRole = BasicSalaryByRole::where('roleId', $emp->roleId)->first();
                $basicSalary = $basicByRole->fee;
            }
            $salary = new Salary;
            $salary->empId = $request->empId;
            $salary->basic = $basicSalary;
            $salary->totalOvertime = $totalHour;

            $overtimeFee = $hour * self::feeOneHour;
            $salary->overtimeFee = $overtimeFee;

            $bonus = $request->bonus;
            $salary->bonus = $request->bonus;

            $salary->gross = $basicSalary + $overtimeFee + $bonus;
            $salary->save();
            return $this->sendResponse(new SalaryResource($salary), "salary created succesfully");
        } catch (\Throwable $th) {
            return $this->sendError("error creating salary", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $salary = Salary::findOrFail($id);
            return $this->sendResponse(new SalaryResource($salary), "salary retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error retrieving salary", $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $emp = Employee::findOrFail($request->empId);
            $overtime = Overtime::where('employeeId', $request->empId)->first();
            $start = date('H', strtotime($overtime->startAt));
            $end = date('H', strtotime($overtime->endAt));
            $hour = $end - $start;
            $basicEmp = BasicSalaryByEmployee::where('empId', $request->empId)->first();
            if ($basicEmp !== null) {
                $basicByRole = BasicSalaryByRole::find($basicEmp->basicSalaryByRoleId);
                $basicSalary = $basicByRole->fee + $basicEmp->fee;
            } else {
                $basicByRole = BasicSalaryByRole::where('roleId', $emp->roleId)->first();
                $basicSalary = $basicByRole->fee;
            }

            $salary = Salary::findOrFail($id);
            $salary->empId = $request->empId;
            $salary->basic = $basicSalary;
            $salary->totalOvertime = $hour;

            $overtimeFee = $hour * self::feeOneHour;
            $salary->overtimeFee = $overtimeFee;

            $bonus = $request->bonus;
            $salary->bonus = $request->bonus;

            $salary->gross = $basicSalary + $overtimeFee + $bonus;
            $salary->save();
            return $this->sendResponse($salary, "salary updated succesfully");
        } catch (\Throwable $th) {
            return $this->sendError("error updating salary");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $salary = Salary::findOrFail($id);
            $salary->delete();
            return $this->sendResponse($salary, "salary deleted successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error deleting salary", $th->getMessage());
        }
    }
}

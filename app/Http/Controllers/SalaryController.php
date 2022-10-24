<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\SalaryGrossResources;
use App\Http\Resources\SalaryResource;
use App\Models\Allowance;
use App\Models\Attendance;
use App\Models\BasicSalaryByEmployee;
use App\Models\BasicSalaryByRole;
use App\Models\Employee;
use App\Models\EmployeeFamily;
use App\Models\Insurance;
use App\Models\InsuranceItem;
use App\Models\Loan;
use App\Models\Overtime;
use App\Models\Salary;
use App\Support\Collection;
use Illuminate\Http\Request;

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
        try {
            $type = $request->type ?? 'gross';
            if ($request->has('month')) {
                $month = date('m-Y', strtotime($request->month));
            } else {
                $month = date('m-Y');
            }
            $year = date('Y', strtotime("01-" . $month));
            $payroll_date = Salary::PAYROLLDATE;
            // $monthNow = date('Y-m-d');

            if (strtotime($payroll_date . "-" . $month) > strtotime($payroll_date . "-" . date('m-Y'))) {
                return $this->sendError('Month is not valid');
            }

            $data = [
                'type' => $type,
                'salaryDate' => date('M Y', strtotime("01-" . $month . "-" . $year)),
                'data' => '',
            ];
            switch ($type) {
                case 'deduction':
                    $data['data'] = $this->getDeduction($month);
                    break;
                case 'net':
                    $data['data'] = $this->getNetSalary($month);
                    break;
                default:
                    $data['data'] = $this->getGrossSalary($month);
                    break;
            }

            return $this->sendResponse($data, "salary retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
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

    public function getTotalAllowance($empId, $firstDay, $lastDay)
    {
        $totalFee = 0;
        $Employee = Employee::find($empId);
        if ($Employee->role != null) {
            $allowances = $Employee->role->type_of_allowances;
            foreach ($allowances as $key => $allowance) {
                $totalFee += $allowance->nominal;
            }
        }

        if ($Employee->basic_salary != null) {
            $basicRole = $Employee->role->basic_salary->fee ?? 0;
            $basicSalary = $basicRole + $Employee->basic_salary->fee;
        } else {
            $basicSalary = $Employee->role->basic_salary->fee ?? 0;
        }

        $Insurances = Insurance::all();
        foreach ($Insurances as $value) {
            foreach ($value->insurance_items as $item) {
                if ($item->type === 'allowance') {
                    $totalFee += $item->percent * $basicSalary / 100;
                }
            }
        }
        return $totalFee;
    }

    public function getGrossSalary($month)
    {
        $SalaryGrossEmployees = [];
        if ($month !== date('m-Y', strtotime(date('d-m-Y')))) {
            $payroll_date = Salary::PAYROLLDATE;
            $Salaries = Salary::whereMonth('salaryDate', date('m', strtotime($payroll_date . "-" . $month)))->whereYear('salaryDate', date('Y', strtotime($payroll_date . "-" . $month)))->orderBy('empId', 'asc')->get();
            foreach ($Salaries as $key => $value) {
                $listAllowance = $value->allowance_items;
                $totalAllowance = 0;

                foreach ($listAllowance as $key => $lva) {
                    $totalAllowance += $lva->nominal;
                }

                $SalaryGrossEmployees[] = [
                    'empId' => $value->empId,
                    'empName' => $value->emp ? $value->emp->firstName . ' ' . $value->emp->lastName : '',
                    'salaryDate' => $value->salaryDate,
                    'basicSalary' => $value->basic,
                    'totalOvertime' => $value->totalOvertime,
                    'overtimeFee' => $value->overtimeFee,
                    'totalAllowance' => $totalAllowance,
                    'totalBonus' => $value->bonus,
                    'total' => $value->gross,
                ];
            }

            return $SalaryGrossEmployees;
        }
        $employees = Employee::all();

        $firstDay = date('Y-m-01');
        $lastDay = date('Y-m-t');
        foreach ($employees as $key => $emp) {
            if ($emp->basic_salary != null) {
                $basicRole = $emp->role->basic_salary->fee ?? 0;
                $basicSalary = $basicRole + $emp->basic_salary->fee;
            } else {
                $basicSalary = $emp->role->basic_salary->fee ?? 0;
            }

            $totalOvertime = $this->getTotalOvertime($emp->employeeId, $firstDay, $lastDay);
            $overtimeFee = $totalOvertime * self::feeOneHour;
            $allowanceFee = $this->getTotalAllowance($emp->employeeId, $firstDay, $lastDay);
            $bonus = 0;
            // if ($totalOvertime > 1) {
            //     $bonus += 100000;
            // }
            $gross = $basicSalary + $overtimeFee + $allowanceFee + $bonus;
            $SalaryGrossEmployees[$key] = [
                'empId' => $emp->employeeId,
                'empName' => $emp->firstName . ' ' . $emp->lastName,
                'salaryDate' => $month,
                'basicSalary' => $basicSalary,
                'totalOvertime' => $totalOvertime,
                'overtimeFee' => $overtimeFee,
                'totalAllowance' => $allowanceFee,
                'totalBonus' => $bonus,
                'total' => $gross,
            ];
        }

        return $SalaryGrossEmployees;
    }

    public function getDeduction($month)
    {
        $GrossEmployee = $this->getGrossSalary($month);
        $payroll_date = Salary::PAYROLLDATE;
        $monthPayroll = date('Y-m-d', strtotime($payroll_date . '-' . $month));

        $firstDatePayroll = date('Y-m-d', strtotime($monthPayroll . " -1 month"));
        $dataDeduction = [];
        $daysWorking = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        $dateWorking = [];
        $dateOff = [];


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

        if ($month !== date('m-Y', strtotime(date('d-m-Y')))) {
            foreach ($GrossEmployee as $key => $value) {
                $totalAttendance = 0;
                $totalLeave = 0;
                $totalLate = 0;
                $totalAbsent = 0;
                foreach ($dateWorking as $date) {
                    $attendance = Attendance::where('employeeId', $value["empId"])->whereDate('timeAttend', $date)->first();
                    if ($attendance) {
                        $totalAttendance++;
                    } else {
                        $totalAbsent++;
                    }
                }

                $dataDeduction[] = [
                    'empId' => $value["empId"],
                    'empName' => $value["empName"] ?? '',
                    'totalAttendance' => $value["totalAttendance"] ?? 0,
                    'totalLeave' => $totalLeave,
                    'totalLate' => $totalLate,
                    'totalAbsent' => $totalAbsent,
                    'totalLoan' => 0,
                    'totalTax' => 0,
                    'totalInsurance' => 0,
                    'totalDeduction' => 0,
                    'percentAttendance' => round($totalAttendance / count($dateWorking) * 100),
                ];
            }

            return $dataDeduction;
        }

        $employees = Employee::all();
        $insurances = Insurance::all();

        foreach ($employees as $key => $value) {
            $totalAttendance = 0;
            $totalDeductionAttendance = 0;
            // $allowanceDeduction = 0;
            $totalLeave = 0;
            $totalLate = 0;
            $totalAbsent = 0;
            $totalLoan = 0;
            $totalTax = Salary::TAX ?? 0;
            $totalInsurance = 0;
            $percentAttendance = 0;
            $totalDeduction = 0;

            $idxGross = array_keys(array_column($GrossEmployee, 'empId'), $value->employeeId);
            $Gross = $GrossEmployee[$idxGross[0]];

            // $allowances = $value->role->type_of_allowances;
            // foreach ($allowances as $key => $itemAllowance) {
            //     $allowanceDeduction += $itemAllowance->nominal;
            // }

            foreach ($dateWorking as $date) {
                $attendance = Attendance::where('employeeId', $value->employeeId)->whereDate('timeAttend', $date)->first();
                if ($attendance) {
                    $totalAttendance++;
                    // if ($attendance->isLate) {
                    //     $totalLate++;
                    // }
                } else {
                    $totalAbsent++;
                }
            }

            $totalDeductionAttendance = round($Gross['basicSalary'] - (($totalAttendance / count($dateWorking)) * $Gross['basicSalary']));

            if (count($idxGross) > 0) {
                foreach ($insurances as $item) {
                    foreach ($item->insurance_items as $item_insurance) {
                        if ($item_insurance->type == 'deduction') {
                            // $totalInsurance += $item_insurance->percent * $GrossEmployee[$idxGross[0]]['total'] / 100;
                            $totalInsurance += $item_insurance->percent * $Gross['basicSalary'] / 100;
                        }
                    }
                }
            }

            $dataLoan = Loan::where('empId', $value->employeeId)->where('status', 0)->get();

            foreach ($dataLoan as $loanItem) {
                // $remaining = $value->amount - $value->paid;
                if (count($loanItem->instalments) > 0) {
                } else {
                    $totalLoan += round($loanItem->nominal * 5 / 100); //5 for percent loan 
                }
            }

            $percentAttendance = round($totalAttendance / count($dateWorking) * 100);

            $totalDeduction = $totalDeductionAttendance +  $totalInsurance + $totalLoan + ($totalTax * $Gross['total'] / 100);

            $dataDeduction[] = [
                'empId' => $value->employeeId,
                'empName' => $value->firstName . ' ' . $value->lastName,
                'totalAttendance' => $totalAttendance,
                'totalLeave' => $totalLeave,
                'totalLate' => $totalLate,
                'totalAbsent' => $totalAbsent,
                'totalLoan' => $totalLoan,
                'totalTax' => $totalTax,
                'totalInsurance' => $totalInsurance,
                'totalDeduction' => $totalDeduction,
                'percentAttendance' => $percentAttendance,
            ];
        }

        return $dataDeduction;
    }

    public function getNetSalary($month)
    {
        $GrossEmployee = $this->getGrossSalary($month);
        $DeductionEmployee = $this->getDeduction($month);
        $dataNetSalary = [];

        foreach ($GrossEmployee as $key => $value) {
            $idxDeduction = array_keys(array_column($DeductionEmployee, 'empId'), $value['empId']);
            $Deduction = $DeductionEmployee[$idxDeduction[0]];

            $dataNetSalary[] = [
                'empId' => $value['empId'],
                'empName' => $value['empName'],
                'salart_date' => $value['salaryDate'],
                'gross_salary' => $value['total'],
                'salary_deduction' => $Deduction['totalDeduction'],
                'net_salary' => $value['total'] - $Deduction['totalDeduction'],
            ];
        }
        return $dataNetSalary;
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
            $Salary = Salary::findOrFail($id);
            $allowance_items = [];
            foreach ($Salary->allowance_items as $value) {
                $allowance_items[] = [
                    'name' => $value->allowanceName,
                    'fee' => $value->nominal,
                ];
            }

            foreach ($Salary->insuranceItemDetails as $value) {
                if ($value->insuranceItem->type == 'allowance') {
                    $allowance_items[] = [
                        'name' => $value->insuranceItem->name,
                        'fee' => $value->nominal,
                    ];
                }
            }

            $Salary->allowance_item = $allowance_items;
            // return $this->sendResponse(new SalaryResource($Salry), "salary retrieved successfully");
            return $this->sendResponse($Salary, "salary retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error retrieving salary", $th->getMessage());
        }
    }

    public function showByEmployee($id)
    {
        try {
            $Salaries = Salary::where('empId', $id)->get();
            return $this->sendResponse(SalaryResource::collection($Salaries), "salary retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error retrieving salary", $th->getMessage());
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

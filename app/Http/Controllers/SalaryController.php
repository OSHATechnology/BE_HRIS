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
use App\Models\Loan;
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

            switch ($type) {
                case 'deduction':
                    $dataDeduction = $this->getDeduction($month);
                    break;

                case 'net':
                    $dataNet = $this->getNetSalary($month);
                    break;

                default:
                    $SalaryGrossEmployees = $this->getGrossSalary($month);
                    break;
            }

            $data = [
                'type' => $type,
                'salaryDate' => date('M Y', strtotime("01-" . $month . "-" . $year)),
                'data' => '',
            ];

            switch ($type) {
                case 'deduction':
                    $data['data'] = $dataDeduction;
                    break;
                case 'net':
                    $data['data'] = $dataNet;
                    break;
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

    public function getGrossSalary($month)
    {
        $employees = Employee::all();

        $firstDay = date('Y-m-01');
        $lastDay = date('Y-m-t');
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

        return $SalaryGrossEmployees;
    }

    public function getDeduction($month)
    {
        $GrossEmployee = $this->getGrossSalary($month);
        $employees = Employee::all();
        $insurances = Insurance::all();
        $payroll_date = 24;
        $monthPayroll = date('Y-m-d', strtotime($payroll_date . '-' . $month));
        $endDate = date('t', strtotime($payroll_date));

        $firstDatePayroll = date('Y-m-d', strtotime($monthPayroll . " -1 month"));
        $startDate = date('d', strtotime($firstDatePayroll));
        $dataDeduction = [];
        $dataAttendance = [];
        $daysWorking = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        $dateWorking = [];
        $dateOff = [];
        $dateHoliday = [];

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



        foreach ($employees as $key => $value) {
            $totalAttendance = 0;
            $todalDeductionAttendance = 0;
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

            $todalDeductionAttendance = round($Gross['basicSalary'] - ((1 / count($dateWorking)) * $Gross['basicSalary']));

            if (count($idxGross) > 0) {
                foreach ($insurances as $item) {
                    foreach ($item->insurance_items as $item_insurance) {
                        if ($item_insurance->type == 'deduction') {
                            $totalInsurance += $item_insurance->percent * $GrossEmployee[$idxGross[0]]['total'] / 100;
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

            $totalDeduction = $todalDeductionAttendance + $totalInsurance + $totalLoan + ($totalTax * $Gross['total'] / 100);

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

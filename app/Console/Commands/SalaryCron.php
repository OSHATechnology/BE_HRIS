<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\Insurance;
use App\Models\Salary;
use App\Models\SalaryAllowance;
use App\Models\SalaryInsuranceDetail;
use Illuminate\Console\Command;

class SalaryCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salary:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info("Generating Salary at " . now());
        $payroll_date = Salary::PAYROLLDATE;
        // $payroll_month = date('m-Y');
        $payroll_month = "09-2022";

        // get employee active
        $employees = Employee::where('isActive', 1)->get();
        $Insurances = Insurance::all();
        foreach ($employees as $employee) {
            // $salary = Salary::where('empId', $employee->employeeId)->whereMonth('salaryDate', now()->month)->first();

            // if (empty($salary)) {
            //     $salary = Salary::create([
            //         'empId' => $employee->employeeId,
            //         'salaryDate' => now()->format('Y-m-d'),
            //         'basic' => $employee->basicSalary,
            //         'totalOvertime' => 0,
            //         'overtimeFee' => 0,
            //         'bonus' => 0,
            //         'gross' => $employee->basicSalary,
            //     ]);

            //     $salary->insuranceItem()->attach($employee->insuranceItem);
            // }

            if ($employee->basic_salary != null) {
                $basicRole = $employee->role->basic_salary->fee ?? 0;
                $basicSalary = $basicRole + $employee->basic_salary->fee;
            } else {
                $basicSalary = $employee->role->basic_salary->fee ?? 0;
            }
            $totalAllowanceFee = 0;
            $bonus = 0;
            $totalOvertime = random_int(3, 10);
            $overtimeFee = $totalOvertime * 100000; // 100k per hour
            foreach ($employee->role->type_of_allowances as $val_allowance) {
                $totalAllowanceFee += $val_allowance->nominal;
            }

            foreach ($Insurances as $val_insurance) {
                foreach ($val_insurance->insurance_items as $item) {
                    if ($item->type === 'allowance') {
                        $totalAllowanceFee += $item->percent * $basicSalary / 100;
                    }
                }
            }
            $totalAllowanceFee += $val_insurance->percent * $basicSalary / 100;

            $totalGross = $basicSalary + $totalAllowanceFee + $bonus + $overtimeFee;

            $Salary = Salary::create([
                'empId' => $employee->employeeId,
                'salaryDate' => $payroll_date,
                'basic' => $basicSalary,
                'empId' => $employee->employeeId,
                'salaryDate' => date('Y-m-d', strtotime($payroll_date . "-" . $payroll_month)),
                'basic' => $basicSalary,
                'totalOvertime' => $totalOvertime,
                'overtimeFee' => $overtimeFee,
                'bonus' => $bonus,
                'gross' => $totalGross,
            ]);

            if ($Salary) {
                foreach ($employee->role->type_of_allowances as $val_allowance) {
                    $salary_allowances = new SalaryAllowance();
                    $salary_allowances->salaryId = $Salary->salaryId;
                    $salary_allowances->allowanceName = $val_allowance->name;
                    $salary_allowances->nominal = $val_allowance->nominal;
                    $salary_allowances->save();
                }
                foreach ($Insurances as $val_insurance) {
                    $insurance_details = new SalaryInsuranceDetail();
                    $insurance_details->salaryId = $Salary->salaryId;
                    $insurance_details->insItemId = $val_insurance->insuranceId;
                    $insurance_details->nominal = $val_insurance->percent * $basicSalary / 100;
                    $insurance_details->date = date('Y-m-d', strtotime($payroll_date . "-" . $payroll_month));
                    $insurance_details->save();
                }
            }
        }
        return 0;
    }
}

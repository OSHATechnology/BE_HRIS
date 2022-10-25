<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\Insurance;
use App\Models\InsuranceItemRole;
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
        $payroll_month = date('m-Y');

        // get employee active
        $employees = Employee::where('isActive', 1)->get();
        foreach ($employees as $employee) {

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

            $Insurances = $employee->role->insurance_items;
            foreach ($Insurances as $val_ins_item) {
                if ($val_ins_item->type == 'allowance') {
                    $totalAllowanceFee += $val_ins_item->percent * $basicSalary / 100;
                }
            }
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
                foreach ($Insurances as $val_ins_item) {
                    if ($val_ins_item->type == 'allowance') {
                        $insurance_details = new SalaryInsuranceDetail();
                        $insurance_details->salaryId = $Salary->salaryId;
                        $insurance_details->insItemId = $val_ins_item->insItemId;
                        $insurance_details->nominal = $val_ins_item->percent * $basicSalary / 100;
                        $insurance_details->date = date('Y-m-d', strtotime($payroll_date . "-" . $payroll_month));
                        $insurance_details->save();
                    }
                }
            }
        }
        return 0;
    }
}

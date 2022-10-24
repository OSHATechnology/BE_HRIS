<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Employees = Employee::all();
        foreach ($Employees as $Employee) {
            $OT = rand(1, 10);
            $data = new Salary();
            $data->empId = $Employee->employeeId;
            $data->salaryDate = "2022-08-24";
            if ($Employee->basic_salary != null) {
                $basicRole = $Employee->role->basic_salary->fee ?? 0;
                $basicSalary = $basicRole + $Employee->basic_salary->fee;
            } else {
                $basicSalary = $Employee->role->basic_salary->fee ?? 0;
            }
            $data->basic = $basicSalary;
            $data->totalOvertime = $OT;
            $data->overtimeFee = $OT * 100000;
            $data->bonus = rand(0, 10) * 100000;
            $data->gross = rand(0, 10) * 10000 + $basicSalary + $OT * 100000;

            $data->save();
        }
    }
}

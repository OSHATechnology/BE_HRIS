<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\SalaryResource;
use App\Models\BasicSalaryByEmployee;
use App\Models\BasicSalaryByRole;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Salary;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryController extends BaseController
{
    const VALIDATION_RULES = [
        'empId' =>  "required|integer",
        'basic' => "required|integer",
        'totalOvertime' => "required|integer",
        'overtimeFee' => "required|integer",
        'allowance' => "required|integer",
        'bonus' => "required|integer",
        'gross' => "required|integer",
        'net' => "required|integer",
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
            // $salary = ((new Collection(new SalaryResource::collection(Salary::all())))->paginate(self::NumPaginate);
        } catch (\Throwable $th) {
            //throw $th;
        }
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
        // 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show(Salary $salary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit(Salary $salary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $salary)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\BasicSalaryByEmployeeResource;
use App\Http\Resources\BasicSalaryByRoleResource;
use App\Models\BasicSalaryByEmployee;
use App\Support\Collection;
use Illuminate\Http\Request;

class BasicSalaryByEmployeeController extends BaseController
{
    const VALIDATION_RULES = [
        'empId' => 'required|integer',
        'basicSalaryByRoleId' => 'required|integer',
        'fee' => 'required|integer',
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
            if(request()->has('search')){
                return $this->search(request());
            }
            $salaryByEmp = (new Collection(BasicSalaryByEmployeeResource::collection(BasicSalaryByEmployee::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($salaryByEmp, "Basic salary by employee retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error retrieving Basic salary by employee", $th->getMessage());
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
            $this->validate($request, self::VALIDATION_RULES);
            $salaryByEmp = new BasicSalaryByEmployee;
            $salaryByEmp->empId = $request->empId;
            $salaryByEmp->basicSalaryByRoleId = $request->basicSalaryByRoleId;
            $salaryByEmp->fee = $request->fee;
            $salaryByEmp->save();
            return $this->sendResponse(new BasicSalaryByEmployeeResource($salaryByEmp), "Basic salary by employee created successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error creating Basic salary by employee", $th->getMessage());
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BasicSalaryByEmployee  $basicSalaryByEmployee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $salaryByEmp = BasicSalaryByEmployee::findOrFail($id);
            return $this->sendResponse(new BasicSalaryByEmployeeResource($salaryByEmp), "Basic salary by employee retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error retrieving Basic salary by employee", $th->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BasicSalaryByEmployee  $basicSalaryByEmployee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $salaryByEmp = BasicSalaryByEmployee::findOrFail($id);
            $salaryByEmp->empId = $request->empId;
            $salaryByEmp->basicSalaryByRoleId = $request->basicSalaryByRoleId;
            $salaryByEmp->fee = $request->fee;
            $salaryByEmp->save();
            return $this->sendResponse($salaryByEmp, "Basic salary by employee updated successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error updating Basic salary by employee", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BasicSalaryByEmployee  $basicSalaryByEmployee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $salaryByEmp = BasicSalaryByEmployee::findOrFail($id);
            $salaryByEmp->delete();
            return $this->sendResponse($salaryByEmp, "Basic salary by employee deleted successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error deleting Basic salary by employee", $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if($request->filled('search')){
                $query = BasicSalaryByEmployee::join('employees', 'basic_salary_by_employees.empId', '=', 'employees.employeeId')
                                    ->where('employees.firstName', 'like', '%'.$request->search.'%')
                                    ->get();
                $users =   (new Collection(BasicSalaryByEmployeeResource::collection($query)))->paginate(self::NumPaginate);
                
            }else{
                $users = (new Collection(BasicSalaryByEmployeeResource::collection(BasicSalaryByEmployee::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($users, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }
}

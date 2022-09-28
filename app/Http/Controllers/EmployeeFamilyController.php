<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\EmployeeFamilyResource;
use App\Models\EmployeeFamily;
use App\Support\Collection;
use Illuminate\Http\Request;

class EmployeeFamilyController extends BaseController
{
    const VALIDATION_RULES = [
        'empId' => 'required|integer',
        'identityNumber' => 'required|string|unique:employee_families|max:255',
        'name' => 'required|string|max:255',
        'statusId' => 'required|integer',
        'isAlive' => 'required|boolean'
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
            if (request()->has('search')) {
                return $this->search(request());
            }
            $empFam = (new Collection(EmployeeFamilyResource::collection(EmployeeFamily::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($empFam, "Employee Family retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("Error employee Family retrieving", $th->getMessage());
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
            $empFam = new EmployeeFamily;
            // dd($empFam);
            $empFam->empId = $request->empId;
            $empFam->identityNumber = $request->identityNumber;
            $empFam->name = $request->name;
            $empFam->statusId = $request->statusId;
            $empFam->isAlive = $request->isAlive;
            $empFam->save();
            return $this->sendResponse(new EmployeeFamilyResource($empFam), "Employee Family created successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("Error employee Family creating", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeFamily  $employeeFamily
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $empFam = new EmployeeFamilyResource(EmployeeFamily::findOrFail($id));
            return $this->sendResponse($empFam, "Employee Family retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("Error employee Family creating", $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeFamily  $employeeFamily
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'empId' => 'required|integer',
                'name' => 'required|string|max:255',
                'statusId' => 'required|integer',
                'isAlive' => 'required|boolean'
            ]);
            $empFam = EmployeeFamily::findOrFail($id);
            $empFam->empId = $request->empId;
            $empFam->identityNumber = $request->identityNumber;
            $empFam->name = $request->name;
            $empFam->statusId = $request->statusId;
            $empFam->isAlive = $request->isAlive;
            $empFam->save();
            return $this->sendResponse($empFam, "Employee Family updated successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("Error employee Family updating", $th->getMessage());
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeFamily  $employeeFamily
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $empFam = EmployeeFamily::findOrFail($id);
            $empFam->delete();
            return $this->sendResponse($empFam, "Employee Family deleted successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("Error employee Family deleting", $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if($request->filled('search')){
                $query = EmployeeFamily::join('employees', 'employee_families.empId', '=', 'employees.employeeId')
                                    ->where('employees.firstName', 'like', '%'.$request->search.'%')
                                    ->get();
                $users =   (new Collection(EmployeeFamilyResource::collection($query)))->paginate(self::NumPaginate);
                
            }else{
                $users = (new Collection(EmployeeFamilyResource::collection(EmployeeFamily::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($users, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\BasicSalaryByRoleResource;
use App\Models\BasicSalaryByRole;
use App\Support\Collection;
use Illuminate\Http\Request;

class BasicSalaryByRoleController extends BaseController
{
    const VALIDATE_RULES = [
        'roleId' => 'required|integer',
        'fee' => 'required|integer|digits_between:6,10',
    ];

    const MessageError = [
        'fee.required' => 'Gaji berdasarkan role tidak boleh kosong',
        'fee.digits_between' => 'Gaji minimal 6 digit dan maksimal gaji 10 digit'
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
            $salaryRole = (New Collection(BasicSalaryByRoleResource::collection(BasicSalaryByRole::get())))->paginate(self::NumPaginate);
            return $this->sendResponse($salaryRole, "Basic salary by role retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error retrieving basic salary by role", $th->getMessage());
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
            $this->validate($request, self::VALIDATE_RULES, self::MessageError);
            $salaryRole = new BasicSalaryByRole;
            $salaryRole->roleId = $request->roleId;
            $salaryRole->fee = $request->fee;
            $salaryRole->save();
            return $this->sendResponse(new BasicSalaryByRoleResource($salaryRole), "Basic salary by role created successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error creating basic salary by role", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BasicSalaryByRole  $basicSalaryByRole
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $salaryRole = new BasicSalaryByRoleResource(BasicSalaryByRole::findOrFail($id));
            return $this->sendResponse($salaryRole, "Basic salary by role retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("Error retrieving salary by role", $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BasicSalaryByRole  $basicSalaryByRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATE_RULES, self::MessageError);
            $salaryRole = BasicSalaryByRole::findOrFail($id);
            $salaryRole->roleId = $request->roleId;
            $salaryRole->fee = $request->fee;
            $salaryRole->save();
            return $this->sendResponse($salaryRole, "Basic salary by role updated successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error updating basic salary by role", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BasicSalaryByRole  $basicSalaryByRole
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $salaryRole = BasicSalaryByRole::findOrFail($id);
            $salaryRole->delete();
            return $this->sendResponse($salaryRole, "Basic salary by role deleted successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error deleting basic salary by role", $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if($request->filled('search')){
                $query = BasicSalaryByRole::join('roles', 'basic_salary_by_roles.roleId', '=', 'roles.roleId')
                                    ->where('roles.nameRole', 'like', '%'.$request->search.'%')
                                    ->get();
                $users =   (new Collection(BasicSalaryByRoleResource::collection($query)))->paginate(self::NumPaginate);
                
            }else{
                $users = (new Collection(BasicSalaryByRoleResource::collection(BasicSalaryByRole::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($users, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }
}

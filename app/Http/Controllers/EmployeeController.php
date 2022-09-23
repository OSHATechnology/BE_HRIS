<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\EmployeeResource;
use App\Imports\EmployeesImport;
use App\Models\Employee;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends BaseController
{
    const VALIDATION_RULES = [
        'firstName' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'phone' => 'required|string|max:255',
        'email' => 'required|string|unique:employees|max:255',
        'password' => 'required|',
        'gender' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'nation' => 'required|string|max:255',
        'roleId' => 'required|integer',
        'isActive' => 'required|boolean',
        'emailVerifiedAt' => 'date',
        'joinedAt' => 'date',
        'resignedAt' => 'date',
        'statusHireId' => 'required|boolean'
    ];

    const numPaginate = 10;

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
            $employees = (new Collection(EmployeeResource::collection(Employee::all())))->paginate(self::numPaginate);
            // $employees = Employee::paginate(1);
            return $this->sendResponse($employees, "employee retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("employee retrieving successfully", $th->getMessage());
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
            $employee = new Employee;
            $employee->firstName = $request->firstName;
            $employee->lastName = $request->lastName;
            $employee->phone = $request->phone;
            $employee->email = $request->email;
            $employee->password = bcrypt($request->password);
            if ($request->hasFile('photo')) {
                $imageName = time() . '.' . $request->photo->extension();
                $path = $request->file('photo')->storeAs('public/employee_image', $imageName);
                $employee->photo = 'storage/public/employee_image/' . $imageName;
            } else {
                $employee->photo = $request->photo;
            }

            $employee->gender = $request->gender;
            $employee->birthDate = $request->birthDate;
            $employee->address = $request->address;
            $employee->city = $request->city;
            $employee->nation = $request->nation;
            $employee->roleId = $request->roleId;
            $employee->isActive = $request->isActive;
            $employee->emailVerifiedAt = $request->emailVerifiedAt;
            $employee->remember_token = $request->remember_token;
            $employee->joinedAt = $request->joinedAt;
            $employee->resignedAt = $request->resignedAt;
            $employee->statusHireId = $request->statusHireId;
            $employee->save();
            return $this->sendResponse(new EmployeeResource($employee), "employee created successfully");
        } catch (\Throwable $th) {
            return $this->sendError("employee creating successfully", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $employee = new EmployeeResource(Employee::findOrFail($id));
            return $this->sendResponse($employee, "employee retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("employee retrieving successfully", "Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        try {
            $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'gender' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'nation' => 'required|string|max:255',
                'roleId' => 'required|integer',
                'isActive' => 'required|boolean',
                'emailVerifiedAt' => 'date',
                'joinedAt' => 'date',
                'resignedAt' => 'date',
                'statusHireId' => 'required|boolean'
            ]);
            $employee = Employee::findOrFail($id);
            $employee->firstName = $request->firstName;
            $employee->lastName = $request->lastName;
            $employee->phone = $request->phone;
            $employee->password = bcrypt($request->password);

            $employee->firstName = $request->firstName;
            $employee->lastName = $request->lastName;
            $employee->phone = $request->phone;
            $employee->email = $request->email;

            if ($request->hasFile("photo")) {
                $imageName = time() . '.' . $request->photo->extension();
                $path = $request->file('photo')->storeAs('public/employee_image', $imageName);
                $employee->photo = 'storage/public/employee_image/' . $imageName;
            } else {
                $employee->photo = $employee->photo;
            }

            $employee->gender = $request->gender;
            $employee->birthDate = $request->birthDate;
            $employee->address = $request->address;
            $employee->city = $request->city;
            $employee->nation = $request->nation;
            $employee->roleId = $request->roleId;
            $employee->isActive = $request->isActive;
            $employee->emailVerifiedAt = $request->emailVerifiedAt;
            $employee->remember_token = $request->remember_token;
            $employee->joinedAt = $request->joinedAt;
            $employee->resignedAt = $request->resignedAt;
            $employee->statusHireId = $request->statusHireId;
            $employee->save();
            return $this->sendResponse($employee, "employee updated successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error employee updating", $th->getMessage());
        }
    }

    /**
     * Update password the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update_password($id, Request $request)
    {
        try {
            $request->validate([
                'oldPassword' => 'required|string|max:255',
                'newPassword' => 'required|string|max:255',
                'confirmPassword' => 'required|string|max:255',
            ]);
            $employee = Employee::findOrFail($id);
            if(Hash::check($request->oldPassword ,$employee->password)) {
                if ($request->newPassword === $request->confirmPassword) {
                    $employee->password = bcrypt($request->newPassword);
                    $employee->save();
                    return $this->sendResponse($employee, 'Employee password updated successfully');
                } else {
                    throw now('Confirm password is not the same as the new password.');
                }
            } else {
                throw now('the password that is entered is wrong');
            }
        } catch (\Throwable $th) {
            return $this->sendError('Error updating password', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();
            return $this->sendResponse($employee, "employee deleted successfully");
        } catch (\Throwable $th) {
            return $this->sendError("employee deleting successfully", "Data Not Found");
        }
    }

    public function trash()
    {
        try {
            $employees = (new Collection(EmployeeResource::collection(Employee::onlyTrashed()->get())))->paginate(self::numPaginate);
            // $employees = Employee::onlyTrashed()->get();
            return $this->sendResponse($employees, "employee retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("Error retrieved employee", $th->getMessage());
        }
    }
    
    public function restore($id)
    {
        try {
            $employee = new EmployeeResource(Employee::onlyTrashed()->findOrFail($id));
            $employee->restore();
            return $this->sendResponse($employee, "employee retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("Error retrieved employee", $th->getMessage());
        }
    }

    /*
    *   Import data from excel
    */
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xls,xlsx'
            ]);
            $file = $request->file('file');
            $importFile = new EmployeesImport();
            $importFile->import($file);
            if (!$importFile) {
                return $this->sendError("Error employee importing", "Data Not Found");
            }

            return $this->sendResponse($file, "employee imported successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error importing employee failed", $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if($request->filled('search')){
                $users =   (new Collection(EmployeeResource::collection(Employee::search($request->search)->get())))->paginate(self::numPaginate);
            }else{
                $users = (new Collection(EmployeeResource::collection(Employee::all())))->paginate(self::numPaginate);
            }
            return $this->sendResponse($users, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }
}

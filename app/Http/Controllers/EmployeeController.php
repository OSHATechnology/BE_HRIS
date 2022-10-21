<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\FurloughResource;
use App\Http\Resources\OvertimeResource;
use App\Http\Resources\WorkPermitResource;
use App\Imports\EmployeesImport;
use App\Models\Employee;
use App\Models\Furlough;
use App\Models\Overtime;
use App\Models\WorkPermit;
use App\Support\Collection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            if (request()->has('search')) {
                return $this->search(request());
            }

            if (request()->has('per_page')) {
                $numPaginate = request()->per_page;
            } else {
                $numPaginate = self::numPaginate;
            }

            $employees = (new Collection(EmployeeResource::collection(Employee::all())))->paginate($numPaginate);
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
                $employee->photo = 'storage/employee_image/' . $imageName;
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
            return $this->sendError("failed creating employee", $th->getMessage());
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
            return $this->sendError("Error creating employee", $th->getMessage());
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

            $employee->firstName = $request->firstName;
            $employee->lastName = $request->lastName;
            $employee->phone = $request->phone;
            $employee->email = $request->email;

            if ($request->hasFile("photo")) {
                $imageName = time() . '.' . $request->photo->extension();
                $path = $request->file('photo')->storeAs('public/employee_image', $imageName);
                $employee->photo = 'storage/employee_image/' . $imageName;
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
            if (Hash::check($request->oldPassword, $employee->password)) {
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
            return $this->sendError("Error deleting employee", $th->getMessage());
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

    /*
    *   search data 
    */

    public function search(Request $request)
    {
        try {
            if (request()->has('per_page')) {
                $numPaginate = request()->per_page;
            } else {
                $numPaginate = self::numPaginate;
            }

            if ($request->filled('search')) {
                $users =   (new Collection(EmployeeResource::collection(Employee::search($request->search)->get())))->paginate($numPaginate);
            } else {
                $users = (new Collection(EmployeeResource::collection(Employee::all())))->paginate($numPaginate);
            }
            return $this->sendResponse($users, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }

    /*
    *   show my leave request
    */

    public function myLeaveRequests(Request $request)
    {
        try {
            $showAll = $request->has('show_all') ? $request->show_all : false;
            $employee = Employee::findOrFail(Auth::user()->employeeId);
            $collection = new Collection();

            foreach ($employee->furloughs as $key => $value) {
                $collection->push([
                    'id' => "f" . $value->furloughId,
                    'type' => 'furlough',
                    'requestAt' => $value->created_at,
                    'confirmedAt' => $value->confirmedAt !== null ? $value->confirmedAt : null,
                    'status' => Furlough::TYPESTATUS[$value->isConfirmed],
                    'msg' => $value->message,
                ]);
            }

            foreach ($employee->work_permits as $key => $value) {
                $collection->push([
                    'id' => "wp" . $value->workPermitId,
                    'type' => 'work permit',
                    'requestAt' => $value->created_at,
                    'confirmedAt' => $value->confirmedAt !== null ? $value->confirmedAt : null,
                    'status' => $value->confirmedAt !== null ? ($value->isConfirmed !== 0 ? "Confirmed" : 'rejected') : "waiting for approved",
                ]);
            }

            foreach ($employee->overtimes as $key => $value) {
                $collection->push([
                    'id' => "ot" . $value->overtimeId,
                    'type' => 'overtime',
                    'requestAt' => $value->created_at,
                    'confirmedAt' => $value->isConfirmed !== 0 ? $value->updated_at : null,
                    'status' => Overtime::STATUS[$value->isConfirmed] ? Overtime::STATUS[$value->isConfirmed] : "waiting for approved",
                ]);
            }

            if ($showAll) {
                $data = (new Collection($collection->sortByDesc('requestAt')->values()->all()))->paginate(self::numPaginate);
            } else {
                $collection =  $collection->sortByDesc('requestAt')->values()->all();
                $data = array_slice($collection, 0, 5);
            }

            return $this->sendResponse($data, "employee leave request retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error retrieving employee leave request", $th->getMessage());
        }
    }

    /*
    * action add leave request employee
    */

    public function addLeaveRequest(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required',
            ]);

            $empId = Auth::user()->employeeId;

            switch ($request->type) {
                case 'furlough':
                    $request->validate([
                        'type_furlough' => 'required',
                        'start_at' => 'required',
                        'end_at' => 'required',
                    ]);

                    $furlough = new Furlough();
                    $furlough->employeeId = $empId;
                    $furlough->furTypeId = $request->type_furlough;
                    $furlough->startAt = $request->start_at;
                    $furlough->endAt = $request->end_at;
                    $furlough->lastFurloughAt = Furlough::getLastFurlough($empId);
                    $furlough->save();
                    break;

                case 'work_permit':
                    $request->validate([
                        'start_at' => 'required',
                        'end_at' => 'required',
                    ]);

                    $workPermit = new WorkPermit();
                    $workPermit->employeeId = $empId;
                    $workPermit->startAt = $request->start_at;
                    $workPermit->endAt = $request->end_at;
                    $workPermit->save();
                    break;

                case "overtime":
                    $request->validate([
                        'start_at' => 'required',
                        'end_at' => 'required',
                    ]);

                    $overtime = new Overtime();
                    $overtime->employeeId = $empId;
                    $overtime->startAt = $request->start_at;
                    $overtime->endAt = $request->end_at;
                    $overtime->assignedBy = $empId;
                    $overtime->save();
                    break;

                default:
                    return $this->sendError("Error add leave request", "type not found");
                    break;
            }

            return $this->sendResponse($request->type, "request sent successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error adding leave request", $th->getMessage());
        }
    }
}

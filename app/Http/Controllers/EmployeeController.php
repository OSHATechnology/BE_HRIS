<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\EmployeeResource;
use App\Imports\EmployeesImport;
use App\Models\Employee;
use App\Models\Furlough;
use App\Models\Overtime;
use App\Models\WorkPermit;
use App\Models\WorkPermitFile;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends BaseController
{
    const VALIDATION_RULES = [
        'firstName' => 'required|string|min:3|max:15',
        'lastName' => 'required|string|min:3|max:15',
        'birthDate' => 'required|date',
        'phone' => 'required|string|min:10|max:13',
        'email' => 'required|email|unique:employees|max:255',
        'password' => 'required|string|min:6|max:24',
        'gender' => 'required|string|max:255',
        'address' => 'required|string|min:6|max:50',
        'city' => 'required|string|min:3|max:30',
        'nation' => 'required|string|min:3|max:30',
        'roleId' => 'required|integer',
        'isActive' => 'required|boolean',
        'emailVerifiedAt' => 'date',
        'joinedAt' => 'required|date',
        'resignedAt' => 'date',
        'statusHireId' => 'required|boolean'
    ];

    const MessageError = [
        'firstName.required' => 'Nama depan tidak boleh kosong',
        'firstName.min' => 'Nama depan minimal 3 karakter',
        'firstName.max' => 'Nama depan maksimal 15',
        'lastName.required' => 'Nama akhir tidak boleh kosong',
        'lastName.min' => 'Nama akhir minimal 3 karakter',
        'lastName.max' => 'Nama akhir maksimal 15',
        'birthDate.required' => 'Tanggal lahir tidak boleh kosong',
        'phone.required' => 'Nomor Telepon tidak boleh kosong',
        'phone.min' => 'Nomor Telepon minimal 10 karakter',
        'phone.max' => 'Nomor Telepon tidak boleh lebih dari 13 karakter',
        'email.required' => 'Email tidak boleh kosong',
        'email.email' => 'format email tidak sesuai',
        'password.required' => 'password tidak boleh kosong',
        'password.min' => 'password minimal 6 karakter',
        'password.max' => 'password maksimal 24 karakter',
        'gender.required' => 'gender tidak boleh kosong',
        'address.required' => 'address tidak boleh kosong',
        'address.min' => 'address minimal 6 karakter',
        'address.max' => 'address maksimal 50 karakter',
        'city.required' => 'city tidak boleh kosong',
        'city.min' => 'city minimal 3 karakter',
        'city.max' => 'city maksimal 30 karakter',
        'nation.required' => 'nation tidak boleh kosong',
        'nation.min' => 'nation minimal 3 karakter',
        'nation.max' => 'nation maksimal 30 karakter',
        'roleId.required' => 'role tidak boleh kosong',
        'joinedAt.required' => 'joinedAt tidak boleh kosong',
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

            if (request()->has('showAll')) {
                $employees = EmployeeResource::collection(Employee::all());
            } else {
                $employees = (new Collection(EmployeeResource::collection(Employee::all())))->paginate($numPaginate);
            }

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
            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
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
                'firstName' => 'required|string|min:3|max:15',
                'lastName' => 'required|string|min:3|max:15',
                'birthDate' => 'required|date',
                'phone' => 'required|string|min:10|max:13',
                'gender' => 'required|string|max:255',
                'address' => 'required|string|min:6|max:50',
                'city' => 'required|string|min:3|max:30',
                'nation' => 'required|string|min:3|max:30',
                'roleId' => 'required|integer',
                'isActive' => 'required|boolean',
                'emailVerifiedAt' => 'date',
                'joinedAt' => 'required|date',
                'resignedAt' => 'date',
                'statusHireId' => 'required|boolean'
            ],[
                'firstName.required' => 'Nama depan tidak boleh kosong',
                'firstName.min' => 'Nama depan minimal 3 karakter',
                'firstName.max' => 'Nama depan maksimal 15',
                'lastName.required' => 'Nama akhir tidak boleh kosong',
                'lastName.min' => 'Nama akhir minimal 3 karakter',
                'lastName.max' => 'Nama akhir maksimal 15',
                'birthDate.required' => 'Tanggal lahir tidak boleh kosong',
                'phone.required' => 'Nomor Telepon tidak boleh kosong',
                'phone.min' => 'Nomor Telepon minimal 10 karakter',
                'phone.max' => 'Nomor Telepon tidak boleh lebih dari 13 karakter',
                'gender.required' => 'gender tidak boleh kosong',
                'address.required' => 'address tidak boleh kosong',
                'address.min' => 'address minimal 6 karakter',
                'address.max' => 'address maksimal 50 karakter',
                'city.required' => 'city tidak boleh kosong',
                'city.min' => 'city minimal 3 karakter',
                'city.max' => 'city maksimal 30 karakter',
                'nation.required' => 'nation tidak boleh kosong',
                'nation.min' => 'nation minimal 3 karakter',
                'nation.max' => 'nation maksimal 30 karakter',
                'roleId.required' => 'role tidak boleh kosong',
                'joinedAt.required' => 'joinedAt tidak boleh kosong',
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
                'oldPassword' => 'required|string|min:6|max:24',
                'newPassword' => 'required|string|min:6|max:24',
                'confirmPassword' => 'required|string|min:6|max:24',
            ],[
                'oldPassword.required' => 'password lama tidak boleh kosong',
                'oldPassword.min' => 'password lama minimal 6 karakter',
                'oldPassword.max' => 'password lama minimal 24 karakter',
                'newPassword.required' => 'password baru tidak boleh kosong',
                'newPassword.min' => 'password baru minimal 6 karakter',
                'newPassword.max' => 'password baru minimal 24 karakter',
                'confirmPassword.required' => 'konfirmasi password tidak boleh kosong',
                'confirmPassword.min' => 'konfirmasi password minimal 6 karakter',
                'confirmPassword.max' => 'konfirmasi password minimal 24 karakter',
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
     * Update password the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function basic_salary($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            if ($employee->basic_salary) {
                $basicSalary = $employee->basic_salary;
                $basicSalary->basic = $employee->role->basic_salary->fee ?? 0;
                $basicSalary->total = $basicSalary->fee + ($employee->role->basic_salary->fee ?? 0);
            } else {
                $basicSalary = $employee->role->basic_salary;
                $basicSalary->basic = $basicSalary->fee;
                $basicSalary->total = $basicSalary->fee;
            }
            return $this->sendResponse($basicSalary, 'Employee basic salary retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error show basic salary', $th->getMessage());
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
            ],[
                'file.required' => 'file tidak boleh kosong',
                'file.mimes' => 'format yang digunakan adalah xls, xlsx'
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
            $users->appends(['search' => $request->search]);
            // $users->appends($request->all());
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

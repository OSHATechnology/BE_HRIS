<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::all();
        return response()->json([
            "code" => 200,
            "status" => "OK",
            "data" => $employees
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'phoneName' => 'required|string|max:255',
            'email' => 'required|string|unique|max:255',
            'password' => 'required|password|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'gander' => 'required|enum|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'nation' => 'required|string|max:255',
            'roleId' => 'required|integer',
            'isActive' => 'required|boolean',
            'emailVerifiedAt' => 'date',
            'joinedAt' => 'date',
            'resignedAt' => 'date',
            'statusHireId' => 'required|boolean',
        ]);

        $employee = new Employee;
        $employee->firstName = $request->firstName;
        $employee->lastName = $request->lastName;
        $employee->phone = $request->phone;
        $employee->email = $request->email;
        $employee->password = bcrypt($request->password);
        if($request->hasFile('photo')){
            $imageName = time().'.'.$request->photo->extension();
            $path = $request->file('photo')->storeAs('public/employee_image', $imageName);
            $employee->photo = 'storage/public/employee_image/' . $imageName;
        }else{
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
        return response()->json([
            "code" => 200,
            "status" => "OK",
            "data" => $employee
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        return response()->json([
            "code" => 200,
            "status" => "OK",
            "data" => $employee
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
        return response()->json([
            "code" => 200,
            "status" => "OK",
            "data" => $employee
        ]);
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
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'phoneName' => 'required|string|max:255',
            'email' => 'required|string|unique|max:255',
            'password' => 'required|password|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'gander' => 'required|enum|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'nation' => 'required|string|max:255',
            'roleId' => 'required|integer',
            'isActive' => 'required|boolean',
            'emailVerifiedAt' => 'date',
            'joinedAt' => 'date',
            'resignedAt' => 'date',
            'statusHireId' => 'required|boolean',
        ]);

        $employee = Employee::find($id);
        $employee->firstName = $request->firstName;
        $employee->lastName = $request->lastName;
        $employee->phone = $request->phone;
        $employee->email = $request->email;
        $employee->password = bcrypt($request->password);
        
        $employee->firstName = $request->firstName;
        $employee->lastName = $request->lastName;
        $employee->phone = $request->phone;
        $employee->email = $request->email;
        $employee->password = bcrypt($request->password);

        if($employee->photo == $request->photo) {
            $employee->photo = $request->photo;
        } else {
            $imageName = time().'.'.$request->photo->extension();
            $path = $request->file('photo')->storeAs('public/employee_image', $imageName);
            $employee->photo = 'storage/public/employee_image/' . $imageName;
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
        return response()->json([
            "code" => 200,
            "status" => "OK",
            "data" => $employee
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
        $employee->delete();
        return response()->json([
            "code" => 200,
            "status" => "OK",
            "message" => "detele success",
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
use App\Models\StatusHire;
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
        // return view('employee.index', compact('employees' ));
        return response()->json([
            $employees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $statusHires = StatusHire::all();
        return view('employee.create', compact('roles','statusHires' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
        $employee = new Employee;
        $employee->firstName = $request->firstName;
        $employee->lastName = $request->lastName;
        $employee->phone = $request->phone;
        $employee->email = $request->email;
        $employee->password = $request->password;
        $employee->photo = $request->photo;
        $employee->gender = $request->gender;
        $employee->birthDate = $request->birthDate;
        $employee->address = $request->address;
        $employee->city = $request->city;
        $employee->nation = $request->nation;
        $employee->roleId = $request->roleId;
        $employee->isActive = $request->isActive;
        $employee->emailVerified = $request->emailVerified;
        $employee->remberToken = $request->firstName;
        $employee->joinedAt = $request->joinedAt;
        $employee->resignedAt = $request->resignedAt;
        $employee->statusHireId = $request->statusHireId;
        // $employee->save();
        // return redirect()->route('/');
        return response()->json([
            'message' => $request->firstName
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
        $employee = Employee::where('employeeId', $id)->first();
        // return view('employee.show', compact('employee'));
        return response()->json([
            $employee
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $employee = Employee::where('id', $id)
                    ->get();
        $roles = Role::all();
        $statusHires = StatusHire::all();
        return view('employee.edit', compact('employee', 'roles', 'statusHires'));
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
        $employee = Employee::find($id);
        $employee->firstName = $request->firstName;
        $employee->lastName = $request->lastName;
        $employee->phone = $request->phone;
        $employee->email = $request->email;
        $employee->password = $request->password;
        $employee->photo = $request->photo;
        $employee->gender = $request->gender;
        $employee->birthDate = $request->birthDate;
        $employee->address = $request->address;
        $employee->city = $request->city;
        $employee->nation = $request->nation;
        $employee->roleId = $request->roleId;
        $employee->isActive = $request->isActive;
        $employee->emailVerified = $request->emailVerified;
        $employee->remberToken = $request->firstName;
        $employee->joinedAt = $request->joinedAt;
        $employee->resignedAt = $request->resignedAt;
        $employee->statusHireId = $request->statusHireId;
        $employee->save();
        return redirect()->route('/');
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
        return redirect()->route('/');
    }
}

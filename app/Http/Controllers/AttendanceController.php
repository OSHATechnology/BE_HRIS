<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends BaseController
{
    const VALIDATION_RULES = [
        'employeeId' => 'required|integer', 
        'attendanceStatusId' => 'required|integer', 
        'submitedAt' => 'date', 
        'submitedById' => 'required|integer', 
        'typeInOut' => 'required|string|max:255', 
        'timeAttend' => 'date'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $attendance = Attendance::all();
            return $this->sendResponse($attendance,  "Attendance retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error retrieving attendance", $th->getMessage());
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
            $attendance = new Attendance;
            $attendance->employeeId = $request->employeeId;
            $attendance->attendanceStatusId = $request->attendanceStatusId;
            $attendance->submitedAt = $request->submitedAt;
            $attendance->submitedById = $request->submitedById;
            $attendance->typeInOut = $request->typeInOut;
            $attendance->timeAttend = $request->timeAttend;
            $attendance->save();
            return $this->sendResponse($attendance,  "Attendance craeted successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error creating attendance", $th->getMessage());
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $attendance = Attendance::FindOrFail($id);
            return $this->sendResponse($attendance,  "Attendance retrivied successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error retrieving attendance", "Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $attendance = Attendance::FindOrFail($id);
            return $this->sendResponse($attendance,  "Attendance retrivied successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error attendance retrieving", "Data Not Found");
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $attendance = Attendance::findOrFail($id);
            $attendance->employeeId = $request->employeeId;
            $attendance->attendanceStatusId = $request->attendanceStatusId;
            $attendance->submitedAt = $request->submitedAt;
            $attendance->submitedById = $request->submitedById;
            $attendance->typeInOut = $request->typeInOut;
            $attendance->timeAttend = $request->timeAttend;
            $attendance->save();
            return $this->sendResponse($attendance,  "Attendance updated successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error attendance updating", "Data Not Found");
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            $attendance->delete();
            return $this->sendResponse($attendance,  "Attendance deleted successfully");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->senderror("Error deleting attendance", "Data Not Found");
        }
    }
}

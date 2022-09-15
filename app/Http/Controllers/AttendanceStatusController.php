<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\AttendanceStatus;
use Illuminate\Http\Request;

class AttendanceStatusController extends BaseController
{
    const VALIDATION_RULES = [
        'status' => 'required|string|max:255',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $attendanceStatus = AttendanceStatus::all();
            return $this->sendResponse($attendanceStatus, "attendace status retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error retrieving retrieved",  $th->getMessage());
            //throw $th;
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
            $attendanceStatus = new AttendanceStatus();
            $attendanceStatus->status = $request->status;
            $attendanceStatus->save();
            return $this->sendResponse($attendanceStatus, "attendace status creating successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error creating attendace status", $th->getMessage() );
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AttendanceStatus  $attendanceStatus
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        try {
            $attendanceStatus = AttendanceStatus::findOrFail($id);
            return $this->sendResponse($attendanceStatus,"attendace status retrieved successfully");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error retrieving attendance status', 'Data Not Found');
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AttendanceStatus  $attendanceStatus
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $attendanceStatus = AttendanceStatus::findOrFail($id);
            return $this->sendResponse($attendanceStatus,"attendace status retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving attendance status', 'Data Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttendanceStatus  $attendanceStatus
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $attendanceStatus = AttendanceStatus::findOrFail($id);
            $attendanceStatus->status = $request->status;
            $attendanceStatus->save();
            return $this->sendResponse($attendanceStatus, "attendace status updating successfully");
        } catch (\Throwable $th) {
            return $this->sendError('Error updating attendance status', 'Data Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AttendanceStatus  $attendanceStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $attendanceStatus = AttendanceStatus::findOrFail($id);
            $attendanceStatus->delete();
            return $this->sendResponse($attendanceStatus, "attendace status deleting successfully");
        } catch (\Throwable $th) {
            return $this->sendError('Error deleting attendance status', 'Data Not Found');
        }
    }
}

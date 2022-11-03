<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\AttendanceStatus;
use App\Support\Collection;
use Illuminate\Http\Request;

class AttendanceStatusController extends BaseController
{
    const VALIDATION_RULES = [
        'status' => 'required|string|min:4|max:30',
    ];

    const MessageError = [
        'status.required' => 'Nama status attendance tidak boleh kosong',
        'status.min' => 'Nama status attendance minimal 4 karakter',
        'status.max' => 'Nama status attendance tidak boleh lebih dari 30 karakter',
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
                $search = request()->get('search');
                $attendanceStatus =  (new Collection(AttendanceStatus::where('status', 'like', "%{$search}%")->get()))->paginate(self::NumPaginate);
                return $this->sendResponse($attendanceStatus, "attendace status retrieved successfully");
            }
            $attendanceStatus = (new Collection(AttendanceStatus::all()))->paginate(self::NumPaginate);
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
            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
            $attendanceStatus = new AttendanceStatus();
            $attendanceStatus->status = $request->status;
            $attendanceStatus->save();
            return $this->sendResponse($attendanceStatus, "attendace status creating successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error creating attendace status", $th->getMessage());
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
            return $this->sendResponse($attendanceStatus, "attendace status retrieved successfully");
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error retrieving attendance status', $th->getMessage());
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
            return $this->sendResponse($attendanceStatus, "attendace status retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving attendance status', $th->getMessage());
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
            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
            $attendanceStatus = AttendanceStatus::findOrFail($id);
            $attendanceStatus->status = $request->status;
            $attendanceStatus->save();
            return $this->sendResponse($attendanceStatus, "attendace status updating successfully");
        } catch (\Throwable $th) {
            return $this->sendError('Error updating attendance status', $th->getMessage());
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
            return $this->sendError('Error deleting attendance status', $th->getMessage());
        }
    }
}

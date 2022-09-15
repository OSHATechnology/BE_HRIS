<?php

namespace App\Http\Controllers;

use App\Models\AttendanceStatus;
use Illuminate\Http\Request;

class AttendanceStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendanceStatus = AttendanceStatus::all();
        return response()->json([
            'code' => 200,
            'status'=> 'OK',
            'data' => $attendanceStatus
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
            'status' => 'required|string|max:255',
        ]);
        $attendanceStatus = new AttendanceStatus();
        $attendanceStatus->status = $request->status;
        $attendanceStatus->save();
        return response()->json([
            'code' => 200,
            'status'=> 'OK',
            'data' => $attendanceStatus
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AttendanceStatus  $attendanceStatus
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $attendanceStatus = AttendanceStatus::find($id);
        // dd($attendanceStatus == false);
        if ($attendanceStatus) {
            return response()->json([
                'code' => 200,
                'status'=> 'OK',
                'data' => $attendanceStatus
            ]);
        } else {
            return response()->json([
                'code' => 404,
                'status'=> 'Not Found',
                'message' => 'Data Not Found'
            ]);

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
        $attendanceStatus = AttendanceStatus::find($id);
        return response()->json([
            'code' => 200,
            'status'=> 'OK',
            'data' => $attendanceStatus
        ]);
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
        $request->validate([
            'status' => 'required|string|max:255',
        ]);
        $attendanceStatus = AttendanceStatus::find($id);
        $attendanceStatus->status = $request->status;
        $attendanceStatus->save();
        return response()->json([
            'code' => 200,
            'status'=> 'OK',
            'message' => 'update success',
            'data' => $attendanceStatus
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AttendanceStatus  $attendanceStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendanceStatus = AttendanceStatus::find($id);
        $attendanceStatus->delete();
        return response()->json([
            'code' => 200,
            'status'=> 'OK',
            'message' => 'delete success'
        ]);
    }
}

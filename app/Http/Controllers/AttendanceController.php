<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\FurloughResource;
use App\Models\Attendance;
use App\Support\Collection;
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
            if(request()->has('filter')){
                return $this->filter(request());
            }
            if(request()->has('from_y') && request()->has('to_y')){
                return $this->filterCustomYear(request());
            }
            if(request()->has('from_m_y') && request()->has('to_m_y')){
                return $this->filterCustomMonthYear(request());
            }
            $attendance =  (new Collection(AttendanceResource::collection(Attendance::get())))->paginate(self::NumPaginate);
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
            return $this->sendResponse(new AttendanceResource($attendance),  "Attendance craeted successfully");
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
            $attendance = new AttendanceResource(Attendance::findOrFail($id));
            return $this->sendResponse($attendance,  "Attendance retrivied successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error retrieving attendance", $th->getMessage());
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
            return $this->senderror("Error deleting attendance", "Data Not Found");
        }
    }

    public function today()
    {
        try {
            $now = date('Y-m-d');
            $attendance = (new Collection(AttendanceResource::collection(Attendance::where('submitedAt', 'like', '%'. $now.'%')->get())))->paginate(self::NumPaginate);
            return $this->sendResponse($attendance,  "Attendance retrievied successfully");
        } catch (\Throwable $th) {
            return $this->senderror("Error retrieving attendance", "Data Not Found");
        }
    }

    public function search(Request $request)
    {
        try {
            if($request->filled('search')){
                $query = Attendance::join('employees', 'attendances.employeeId', '=', 'employees.employeeId')
                                    ->where('employees.firstName', 'like', '%'.$request->search.'%')
                                    ->get();
                $users =   (new Collection(AttendanceResource::collection($query)))->paginate(self::NumPaginate);
                
            }else{
                $users = (new Collection(AttendanceResource::collection(Attendance::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($users, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }

    public function filter(Request $request)
    {
        try {
            if($request->filled('filter')){
                $attendance = (new Collection(AttendanceResource::collection(Attendance::where('submitedAt', 'like', '%'. $request->filter . '%')->get())))->paginate(self::NumPaginate);
            } else {
                $attendance = (new Collection(AttendanceResource::collection(Attendance::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($attendance,  "Attendance filtered successfully");
        } catch (\Throwable $th) {
            return $this->senderror("Error filtering attendance", 'Data Not Found'());
        }
    }

    public function filterCustomYear(Request $request)
    {
        try {
            $from = $request->from_y . '-01-01 00:00:01';
            $to = $request->to_y . '-12-31 23:59:59';
            $attendance = (new Collection(AttendanceResource::collection(Attendance::whereBetween('submitedAt', [$from, $to])->orderBy('submitedAt')->get())))->paginate(self::NumPaginate);
            return $this->sendResponse($attendance,  "Attendance filtered successfully");
        } catch (\Throwable $th) {
            return $this->senderror("Error filtering attendance", $th->getMessage());
        }
    }

    public function filterCustomMonthYear(Request $request)
    {
        try {
            $from = $request->from_m_y . '-01 00:00:01';
            $to = $request->to_m_y . '-31 23:59:59';
            $attendance = (new Collection(AttendanceResource::collection(Attendance::whereBetween('submitedAt', [$from, $to])->orderBy('submitedAt')->get())))->paginate(self::NumPaginate);
            return $this->sendResponse($attendance,  "Attendance filtered successfully");
        } catch (\Throwable $th) {
            return $this->senderror("Error filtering attendance", $th->getMessage());
        }
    }
}

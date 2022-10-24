<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\FurloughResource;
use App\Models\Attendance;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends BaseController
{
    const VALIDATION_RULES = [
        'employeeId' => 'required|integer',
        'attendanceStatusId' => 'required|integer',
        'submitedAt' => 'date',
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
            if (request()->has('search')) {
                return $this->search(request());
            }
            if (request()->has('filter')) {
                return $this->filter(request());
            }
            if (request()->has('from_y') && request()->has('to_y')) {
                return $this->filterCustomYear(request());
            }
            if (request()->has('from_m_y') && request()->has('to_m_y')) {
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
            $attendance->submitedById = Auth::id();
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
            $attendance->submitedById = Auth::id();
            $attendance->typeInOut = $request->typeInOut;
            $attendance->timeAttend = $request->timeAttend;
            $attendance->save();
            return $this->sendResponse($attendance,  "Attendance updated successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error attendance updating", $th->getMessage());
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
            return $this->senderror("Error deleting attendance", $th->getMessage());
        }
    }

    public function today()
    {
        try {
            $now = date('Y-m-d');
            $attendance = (new Collection(AttendanceResource::collection(Attendance::where('submitedAt', 'like', '%' . $now . '%')->get())))->paginate(self::NumPaginate);
            return $this->sendResponse($attendance,  "Attendance retrievied successfully");
        } catch (\Throwable $th) {
            return $this->senderror("Error retrieving attendance", $th->getMessage());
        }
    }

    public function todayByEmp($id)
    {
        try {
            $now = date('Y-m-d');
            $attendance = (new Collection(AttendanceResource::collection(Attendance::where('employeeId', $id)->where('submitedAt', 'like', '%' . $now . '%')->get())))->paginate(self::NumPaginate);
            return $this->sendResponse($attendance,  "Attendance retrievied successfully");
        } catch (\Throwable $th) {
            return $this->senderror("Error retrieving attendance", $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $attendance = new Attendance();
            if ($request->has('search')) {
                $attendance = $attendance->join('employees', 'attendances.employeeId', '=', 'employees.employeeId')
                    ->where('employees.firstName', 'like', '%' . $request->search . '%');
            }
            if ($request->has('start') && $request->has('end')) {
                $attendance = $attendance->RangeDates($request->start, $request->end);
            }
            $data = (new Collection(AttendanceResource::collection($attendance->get())))->paginate(self::NumPaginate)->withQueryString();

            return $this->sendResponse($data, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }

    public function filter(Request $request)
    {
        try {
            if ($request->filled('filter')) {
                $attendance = (new Collection(AttendanceResource::collection(Attendance::where('submitedAt', 'like', '%' . $request->filter . '%')->get())))->paginate(self::NumPaginate);
            } else {
                $attendance = (new Collection(AttendanceResource::collection(Attendance::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($attendance,  "Attendance filtered successfully");
        } catch (\Throwable $th) {
            return $this->senderror("Error filtering attendance", $th->getMessage());
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

    public function myToday(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required',
            ]);
            $now = date('Y-m-d');

            $checkLeaveToday = Attendance::where('employeeId', Auth::id())->where('submitedAt', 'like', '%' . $now . '%')->where('attendanceStatusId', '!=', 1)->get();

            if ($checkLeaveToday->count() > 0) {
                return $this->sendError("You have leave today", "cannot submit attendance");
            }

            if ($request->type == 'in') {
                $check = Attendance::where('employeeId', Auth::id())->where('timeAttend', 'like', '%' . $now . '%')->where('typeInOut', 'in')->first();
                if ($check) {
                    return $this->sendError("Error attendance", "You already clock in");
                }
            }

            if ($request->type == 'out') {
                $checkClockIn = Attendance::where('employeeId', Auth::id())->where('timeAttend', 'like', '%' . $now . '%')->where('typeInOut', 'in')->first();

                if (!$checkClockIn) {
                    return $this->sendError("Error attendance", "You must clock in first");
                }

                $check = Attendance::where('employeeId', Auth::id())->where('timeAttend', 'like', '%' . $now . '%')->where('typeInOut', 'out')->first();
                if ($check) {
                    return $this->sendError("Error attendance", "You already clock out");
                }
            }

            $now = date('Y-m-d H:i:s');

            $Attend = new Attendance();
            $Attend->employeeId = Auth::id();
            $Attend->attendanceStatusId = Attendance::STATUS_WORK;
            $Attend->submitedAt = $now;
            $Attend->submitedById = Auth::id();
            $Attend->typeInOut = $request->type;
            $Attend->timeAttend = $now;
            $Attend->save();

            return $this->sendResponse("Done",  "Attendance successfully");
        } catch (\Throwable $th) {
            return $this->senderror("failed", $th->getMessage());
        }
    }
}

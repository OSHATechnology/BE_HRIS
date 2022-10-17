<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\OvertimeResource;
use App\Models\Overtime;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends BaseController
{
    const VALIDATION_RULES = [
        'employeeId' => 'required|integer',
        'startAt' => 'required|date',
        'endAt' => 'date',
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
            if (request()->has('search') && request()->get('search') != '') {
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
            $overtime = (new Collection(OvertimeResource::collection(Overtime::withoutPending()->get())))->paginate(self::NumPaginate);
            return $this->sendResponse($overtime, 'Overtime retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving overtime', $th->getMessage());
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
            $request->validate(self::VALIDATION_RULES);

            $overtime = new Overtime;
            $overtime->employeeId = $request->employeeId;
            $overtime->startAt = $request->startAt;
            $overtime->endAt = $request->endAt;
            $overtime->assignedBy = Auth::id();
            $overtime->save();
            return $this->sendResponse(new OvertimeResource($overtime), 'Overtime created successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error creating overtime', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Overtime  $overtime
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $overtime = Overtime::findOrFail($id);
            return $this->sendResponse(new OvertimeResource($overtime), 'Overtime retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving overtime', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Overtime  $overtime
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate(self::VALIDATION_RULES);

            $overtime = Overtime::findOrFail($id);
            $overtime->employeeId = $request->employeeId;
            $overtime->startAt = $request->startAt;
            $overtime->endAt = $request->endAt;
            $overtime->assignedBy = Auth::id();
            $overtime->save();
            return $this->sendResponse($overtime, 'Overtime updated successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating overtime', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Overtime  $overtime
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $overtime = Overtime::findOrFail($id);
            $overtime->delete();
            return $this->sendResponse($overtime, 'Overtime deleted successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error deleting overtime', $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if ($request->filled('search')) {
                $query = Overtime::join('employees', 'overtimes.employeeId', '=', 'employees.employeeId')
                    ->where('employees.firstName', 'like', '%' . $request->search . '%')->withoutPending()->get();
                $users =   (new Collection(OvertimeResource::collection($query)))->paginate(self::NumPaginate);
            } else {
                $users = (new Collection(OvertimeResource::collection(Overtime::withoutPending())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($users, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }

    public function filter(Request $request)
    {
        try {
            if ($request->filled('filter')) {
                $attendance = (new Collection(OvertimeResource::collection(Overtime::where('startAt', 'like', '%' . $request->filter . '%')->get())))->paginate(self::NumPaginate);
            } else {
                $attendance = (new Collection(OvertimeResource::collection(Overtime::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($attendance,  "Overtime filtered successfully");
        } catch (\Throwable $th) {
            return $this->senderror("Error filtering attendance", $th->getMessage());
        }
    }

    public function filterCustomYear(Request $request)
    {
        try {
            $from = $request->from_y . '-01-01 00:00:01';
            $to = $request->to_y . '-12-31 23:59:59';
            $attendance = (new Collection(OvertimeResource::collection(Overtime::whereBetween('startAt', [$from, $to])->orderBy('startAt')->get())))->paginate(self::NumPaginate);
            return $this->sendResponse($attendance,  "Overtime filtered successfully");
        } catch (\Throwable $th) {
            return $this->senderror("Error filtering attendance", $th->getMessage());
        }
    }

    public function filterCustomMonthYear(Request $request)
    {
        try {
            $from = $request->from_m_y . '-01 00:00:01';
            $to = $request->to_m_y . '-31 23:59:59';
            $attendance = (new Collection(OvertimeResource::collection(Overtime::whereBetween('startAt', [$from, $to])->orderBy('startAt')->get())))->paginate(self::NumPaginate);
            return $this->sendResponse($attendance,  "Overtime filtered successfully");
        } catch (\Throwable $th) {
            return $this->senderror("Error filtering attendance", $th->getMessage());
        }
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'overtime_id' => 'required|numeric',
        ]);

        try {
            if ($request->has('assigned_by')) {
                $assigned_by = $request->assigned_by;
            } else {
                $assigned_by = Auth::id();
            }

            if ($request->has('message')) {
                $message = $request->message;
            } else {
                $message = '';
            }

            $overtime = Overtime::confirmOT($request->overtime_id, $assigned_by, $message);
            if (!$overtime) {
                $this->sendError('Error confirming overtime', 'Error confirming overtime');
            }
            return $this->sendResponse("success", 'Overtime confirmed successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error confirming overtime', $th->getMessage());
        }
    }

    public function decline(Request $request)
    {
        $request->validate([
            'overtime_id' => 'required|numeric',
        ]);

        try {
            if ($request->has('message')) {
                $message = $request->message;
            } else {
                $message = '';
            }

            $overtime = Overtime::declineOT($request->overtime_id, $message);
            if (!$overtime) {
                $this->sendError('Error declining overtime', 'Error declining overtime');
            }
            return $this->sendResponse("success", 'Overtime declined successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error declining overtime', $th->getMessage());
        }
    }
}

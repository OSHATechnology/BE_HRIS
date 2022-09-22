<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\FurloughResource;
use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Employee;
use App\Models\Furlough;
use App\Models\Notification;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\type;

class FurloughController extends BaseController
{

    /**
     * Rules for validation request
     *
     * @return array
     */

    const VALIDATION_RULES = [
        'furTypeId' => 'required',
        'employeeId' => 'required',
        'startAt' => 'required',
    ];

    const NumPaginate = 1;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            //gate
            $this->authorize('viewAny', Furlough::class);

            if(request()->has('search')){
                return $this->search(request());
            }

            $furloughs = (new Collection(FurloughResource::collection(Furlough::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($furloughs, 'Furloughs retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving furloughs', $th->getMessage());
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
            //gate
            $this->authorize('create', Furlough::class);

            //validation
            $request->validate(self::VALIDATION_RULES);

            //store furlough
            $furlough = new Furlough();
            $furlough->furTypeId = $request->furTypeId;
            $furlough->employeeId = $request->employeeId;
            $furlough->startAt = $request->startAt;
            $furlough->endAt = ($request->endAt) ? $request->endAt : $request->endAt;
            $furlough->isConfirmed = ($request->isConfirmed) ? $request->isConfirmed : 0;
            $furlough->lastFurloughAt = ($request->lastFurloughAt) ? $request->lastFurloughAt : Furlough::getLastFurlough($request->employeeId);
            $furlough->save();
            return $this->sendResponse(new FurloughResource($furlough), 'Furlough created successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error creating furlough', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $furloughId
     * @return \Illuminate\Http\Response
     */
    public function show($furloughId)
    {
        try {
            //gate
            $this->authorize('view', Furlough::class);
            $furlough = new FurloughResource(Furlough::findOrFail($furloughId));

            return $this->sendResponse($furlough, 'Furlough retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving furlough', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $furloughId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $furloughId)
    {
        try {
            //gate
            $this->authorize('update', Furlough::class);

            //validation
            $request->validate(self::VALIDATION_RULES);

            //update furlough
            $furlough = Furlough::findOrFail($furloughId);
            $furlough->furTypeId = $request->furTypeId;
            $furlough->employeeId = $request->employeeId;
            $furlough->startAt = $request->startAt;
            $furlough->endAt = $request->endAt;
            $furlough->isConfirmed = $request->isConfirmed;
            $furlough->confirmedBy = $request->confirmedBy;
            $furlough->lastFurloughAt = $request->lastFurloughAt;
            $furlough->save();
            return $this->sendResponse($furlough, 'Furlough updated successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating furlough', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $furloughId 
     * @return \Illuminate\Http\Response
     */
    public function destroy($furloughId)
    {

        try {
            //gate
            $this->authorize('delete', Furlough::class);

            $furlough = Furlough::findOrFail($furloughId);
            $furlough->delete();
            return $this->sendResponse($furlough, 'Furlough deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error deleting furlough', $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if($request->filled('search')){
                $query = Furlough::join('employees', 'furloughs.employeeId', '=', 'employees.employeeId')
                                    ->where('employees.firstName', 'like', '%'.$request->search.'%')
                                    ->get();
                $users =   (new Collection(FurloughResource::collection($query)))->paginate(self::NumPaginate);
                
            }else{
                $users = (new Collection(FurloughResource::collection(Furlough::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($users, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }

    public function attendance_accepted(Request $request, $id)
    {
        try {
            $type = Furlough::findOrFail($id);
            $type->isConfirmed = 1;
            $type->confirmedBy = Auth::id();
            $type->message = $request->message;

            $attendance = new Attendance;
            $attendance->employeeId = $type->employeeId;
            $attendance->attendanceStatusId = 2;
            $attendance->submitedAt = now();
            $attendance->submitedById = Auth::id();
            $attendance->typeInOut = "-";
            $attendance->timeAttend = $type->created_at->format('Y-m-d h:i:s');
            
            $employee = Employee::findOrFail($id);
            $notif = new Notification;
            $notif->empId = $id;
            $notif->name = $employee->firstName . " " . $employee->lastName;
            $start = date('d-m-Y', strtotime($type->startAt));
            $end = date('d-m-Y', strtotime($type->endAt));
            $notif->content = "Accepted furlough for " . $start . " until " . $end;
            $notif->type = "furlough";
            $notif->senderBy = Auth::id();
            $notif->scheduleAt = now();
            $notif->status = "Accepted";
    
            $type->save();
            $attendance->save();
            $notif->save();
            return $this->sendResponse($type, "furlough update successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error update furlough failed", $th->getMessage());
        }
    }

    public function attendance_declined(Request $id)
    {
        try {
            $type = Furlough::findOrFail($id);
            $type->isConfirmed = 2;
            $type->confirmedBy = Auth::id();
            $type->message = $request->message;
            
            $employee = Employee::findOrFail($id);
            $notif = new Notification;
            $notif->empId = $id;
            $notif->name = $employee->firstName . " " . $employee->lastName;
            $start = date('d-m-Y', strtotime($type->startAt));
            $end = date('d-m-Y', strtotime($type->endAt));
            $notif->content = "Declined furlough for " . $start . " until " . $end;
            $notif->type = "furlough";
            $notif->senderBy = Auth::id();
            $notif->scheduleAt = now();
            $notif->status = "Declined";
    
            $type->save();
            $notif->save();
            return $this->sendResponse($type, "employee update successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error update employee failed", $th->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Support\Collection;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    const VALIDATION_RULES = [
        'empId' => 'required|integer',
        'name' => 'required|string|max:255',
        'content' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'senderBy' => 'required|integer',
        'scheduleAt' => 'required|date'
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
            $notifications = (new Collection(NotificationResource::collection(Notification::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($notifications, "notification retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error notification retrieving", $th->getMessage());
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
            $notification = new Notification();
            $notification->empId = $request->empId;
            $notification->name = $request->name;
            $notification->content = $request->content;
            $notification->type = $request->type;
            $notification->status = $request->status;
            $notification->senderBy = $request->senderBy;
            $notification->scheduleAt = $request->scheduleAt;
            $notification->save();
            return $this->sendResponse(new NotificationResource($notification), "notification created successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error creating notification", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $notification = new NotificationResource(Notification::findOrFail($id));
            return $this->sendResponse($notification, "notification retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving notification', $th->getMessage());
        }
    }

    public function showByEmployee($id)
    {
        try {
            $notification = (new Collection(NotificationResource::collection(Notification::where('empId',$id)->get())))->paginate(self::NumPaginate);
            return $this->sendResponse($notification, "notification retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving notification', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $notification = Notification::findOrFail($id);
            $notification->empId = $request->empId;
            $notification->name = $request->name;
            $notification->content = $request->content;
            $notification->type = $request->type;
            $notification->status = $request->status;
            $notification->senderBy = $request->senderBy;
            $notification->scheduleAt = $request->scheduleAt;
            $notification->save();
            return $this->sendResponse($notification, "notification updated successfully");
        } catch (\Throwable $th) {
            return $this->sendError('Error updating notification', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();
            return $this->sendResponse($notification, "notification deleting successfully");
        } catch (\Throwable $th) {
            return $this->sendError('Error deleting notification', $th->getMessage());
        }
    }
}

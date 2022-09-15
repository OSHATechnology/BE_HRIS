<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::all();
        return response()->json([
            'code' => '200',
            'status'=> 'OK',
            'data' => $notifications
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
            'empId' => 'required|integer',
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'senderBy' => 'required|integer',
            'scheduleAt' => 'required|date'
        ]);

        $notification = new Notification();
        $notification->empId = $request->empId;
        $notification->name = $request->name;
        $notification->content = $request->content;
        $notification->type = $request->type;
        $notification->status = $request->status;
        $notification->senderBy = $request->senderBy;
        $notification->scheduleAt = $request->scheduleAt;
        $notification->save();
        return response()->json([
            'code' => '200',
            'status'=> 'OK',
            'data' => $notification
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::find($id);
        return response()->json([
            'code' => '200',
            'status'=> 'OK',
            'data' => $notification
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notification = Notification::find($id);
        return response()->json([
            'code' => '200',
            'status'=> 'OK',
            'data' => $notification
        ]);
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
        $request->validate([
            'empId' => 'required|integer',
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'senderBy' => 'required|integer',
            'scheduleAt' => 'required|date'
        ]);

        $notification = Notification::find($id);
        $notification->empId = $request->empId;
        $notification->name = $request->name;
        $notification->content = $request->content;
        $notification->type = $request->type;
        $notification->status = $request->status;
        $notification->senderBy = $request->senderBy;
        $notification->scheduleAt = $request->scheduleAt;
        $notification->save();
        return response()->json([
            'code' => '200',
            'status'=> 'OK',
            'message' => 'update success',
            'data' => $notification
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = Notification::find($id);
        $status->delete();
            return response()->json([
                'code' => '200',
                'status'=> 'OK',
                "message" => "detele success",
            ]);
    }
}

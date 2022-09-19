<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\OvertimeResource;
use App\Models\Overtime;
use Illuminate\Http\Request;

class OvertimeController extends BaseController
{
    const VALIDATION_RULES = [
        'employeeId' => 'required|integer', 
        'startAt' => 'required|date', 
        'endAt' => 'date', 
        'assignedBy' => 'required|integer'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $overtime = OvertimeResource::collection(Overtime::all());
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
            $overtime = new Overtime;
            $overtime->employeeId = $request->employeeId;
            $overtime->startAt = $request->startAt;
            $overtime->endAt = $request->endAt;
            $overtime->assignedBy = $request->assignedBy;
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
            return $this->sendError('Error retrieving overtime', 'Data Not Found');
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
            $overtime = Overtime::findOrFail($id);
            $overtime->employeeId = $request->employeeId;
            $overtime->startAt = $request->startAt;
            $overtime->endAt = $request->endAt;
            $overtime->assignedBy = $request->assignedBy;
            $overtime->save();
            return $this->sendResponse($overtime, 'Overtime updated successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating overtime', 'Data Not Found');
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
            return $this->sendError('Error deleting overtime', 'Data Not Found');
        }
    }
}

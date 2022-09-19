<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\WorkPermitResource;
use App\Models\WorkPermit;
use Illuminate\Http\Request;

class WorkPermitController extends BaseController
{
    const VALIDATION_RULES = [
        'employeeId' => 'required|integer', 
        'startAt' => 'required|date', 
        'endAt' => 'required|date', 
        'isConfirmed' => 'required|integer',
        'confirmedBy' => 'required|integer'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $workPermit = WorkPermitResource::collection(WorkPermit::all());
            return $this->sendResponse($workPermit, 'Work Permit retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving work permit', $th->getMessage());
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
    
            $workPermit = new WorkPermit;
            $workPermit->employeeId = $request->employeeId;
            $workPermit->startAt = $request->startAt;
            $workPermit->endAt = $request->endAt;
            $workPermit->isConfirmed = $request->isConfirmed;
            $workPermit->confirmedBy = $request->confirmedBy;
            $workPermit->save();
            return $this->sendResponse(new WorkPermitResource($workPermit), 'Work Permit created successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error creating work permit.', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkPermit  $workPermit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $workPermit = new WorkPermitResource(WorkPermit::findOrFail($id));
            return $this->sendResponse($workPermit, 'Work permit retrivied successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error retriving Work permit.', 'Data Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkPermit  $workPermit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate(self::VALIDATION_RULES);
    
            $workPermit = WorkPermit::findOrFail($id);
            $workPermit->employeeId = $request->employeeId;
            $workPermit->startAt = $request->startAt;
            $workPermit->endAt = $request->endAt;
            $workPermit->isConfirmed = $request->isConfirmed;
            $workPermit->confirmedBy = $request->confirmedBy;
            $workPermit->save();
            return $this->sendResponse($workPermit, 'Work Permit updated successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating work permit.', 'Data Not Found');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkPermit  $workPermit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $workPermit = WorkPermit::findOrFail($id);
            $workPermit->delete();
            return $this->sendResponse($workPermit, 'Work Permit deleted successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error deleting work permit.', 'Data Not Found');
        }
    }
}

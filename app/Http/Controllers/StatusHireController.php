<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\StatusHire;
use App\Support\Collection;
use Illuminate\Http\Request;

class StatusHireController extends BaseController
{
    const VALIDATION_RULES = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'resposibleBy' => 'required|string|max:255',
        'phone' => 'required|max:50',
        'address' => 'required|string|max:255',
        'assignedBy' => 'required',
        'joinedAt' => 'required'
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
            $statusHires = (new Collection(StatusHire::all()))->paginate(self::NumPaginate);
            return $this->sendResponse($statusHires, "status hire retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error status hire retrieving", $th->getMessage());
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
            $status = new StatusHire;
            $status->name = $request->name;
            $status->save();
            return $this->sendResponse($status, "status hire created successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error status hire creating", $th->getMessage());
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StatusHire  $statusHire
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $status = StatusHire::findOrFail($id);
            return $this->sendResponse($status, "status hire retrieving successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error status hire retrieving", "Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StatusHire  $statusHire
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $status = StatusHire::findOrFail($id);
            return $this->sendResponse($status, "status hire retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error status hire retrieving", "Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StatusHire  $statusHire
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $status = StatusHire::find($id);
            $status->name = $request->name;
            $status->save();
            return $this->sendResponse($status, "status hire updated successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error status hire updating", "Data Not Found");
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StatusHire  $statusHire
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $status = StatusHire::find($id);
            $status->delete();
            return $this->sendResponse($status, "status hire deleted successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error status hire deleting", "Data Not Found");
        }
    }
}

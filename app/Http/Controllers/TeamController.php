<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends BaseController
{
    const VALIDATION_RULES = [
        'name' => 'required|string|max:255', 
        'leadBy' => 'required|integer', 
        'createdBy' => 'required|integer',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $team = Team::all();
            return $this->sendResponse($team, 'team retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving team ', $th->getMessage());
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
            $team = new Team;
            $team->name = $request->name;
            $team->leadBy = $request->leadBy;
            $team->createdBy = $request->createdBy;
            $team->save();
            return $this->sendResponse($team, 'team created successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error creating team ', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $team = Team::findOrFail($id);
            return $this->sendResponse($team, 'team retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving team ', "Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $team = Team::findOrFail($id);
            return $this->sendResponse($team, 'team retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving team ', "Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $team = Team::findOrFail($id);
            $team->name = $request->name;
            $team->leadBy = $request->leadBy;
            $team->createdBy = $request->createdBy;
            $team->save();
            return $this->sendResponse($team, 'team updated successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating team ', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $team = Team::findOrFail($id);
            $team->delete();
            return $this->sendResponse($team, 'team deleted successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error deleting team ', "Data Not Found");
        }
    }
}

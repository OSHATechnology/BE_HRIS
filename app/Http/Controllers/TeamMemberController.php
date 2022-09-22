<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\TeamMemberResource;
use App\Models\Team;
use App\Models\TeamMember;
use App\Support\Collection;
use Illuminate\Http\Request;

class TeamMemberController extends BaseController
{
    const VALIDATION_RULES = [
        'teamId' => 'required|integer', 
        'empId' => 'required|integer', 
        'assignedBy' => 'required|integer', 
        'joinedAt' => 'date'
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
            $member = (new Collection(TeamMemberResource::collection(TeamMember::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($member, 'team member retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving team member', $th->getMessage());
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
            $member = new TeamMember();
            $member->teamId = $request->teamId;
            $member->empId = $request->empId;
            $member->assignedBy = $request->assignedBy;
            $member->joinedAt = $request->joinedAt;
            $member->save();
            return $this->sendResponse(new TeamMemberResource($member), 'team member created successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error creating team member', $th->getMessage());
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeamMember  $teamMember
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $member = new TeamMemberResource(TeamMember::findOrFail($id));
            return $this->sendResponse($member, 'team member retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving team member', 'Data Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeamMember  $teamMember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $member = TeamMember::findOrFail($id);
            $member->teamId = $request->teamId;
            $member->empId = $request->empId;
            $member->assignedBy = $request->assignedBy;
            $member->joinedAt = $request->joinedAt;
            $member->save();
            return $this->sendResponse($member, 'team member updated successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating team member', 'Data Not Found');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamMember  $teamMember
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $member = TeamMember::findOrFail($id);
            $member->delete();
            return $this->sendResponse($member, 'team member deleted successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error deleting team member', 'Data Not Found');
        }
    }
}

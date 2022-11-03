<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Support\Collection;
use Illuminate\Http\Request;

class TeamController extends BaseController
{
    const VALIDATION_RULES = [
        'name' => 'required|string|min:2|max:30',
        'leadBy' => 'required|integer',
        'createdBy' => 'required|integer',
    ];

    const MessageError = [
        'name.required' => 'Nama team tidak boleh kosong',
        'name.min' => 'Nama tim minimal 2 karakter',
        'name.max' => 'Nama tim maksimal 30 karakter',
        'leadBy.required' => 'Leader tim harus diisi terlebih dahulu',
        'createdBy.required' => 'Team Maker harus diisi terlebih dahulu'
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
            $Team = new Team;
            if (request()->has('search')) {
                $Team = $Team->search(request()->search);
            }
            $Team = (new Collection(TeamResource::collection($Team->get())))->paginate(self::NumPaginate);
            return $this->sendResponse($Team, 'Team retrieved successfully.');
            // $team = (new Collection(TeamResource::collection(Team::all())))->paginate(self::NumPaginate);
            // return $this->sendResponse($team, 'team retrieved successfully');
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
            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
            $team = new Team;
            $team->name = $request->name;
            $team->leadBy = $request->leadBy;
            $team->createdBy = $request->createdBy;
            $team->save();
            return $this->sendResponse(new TeamResource($team), 'team created successfully');
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
            $team = new TeamResource(Team::findOrFail($id));
            return $this->sendResponse($team, 'team retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving team ', $th->getMessage());
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
            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
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
            return $this->sendError('Error deleting team ', $th->getMessage());
        }
    }
}

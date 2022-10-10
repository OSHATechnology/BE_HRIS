<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\AllowanceResource;
use App\Models\Allowance;
use App\Support\Collection;
use Illuminate\Http\Request;

class AllowanceController extends BaseController
{
    const VALIDATION_RULES = [
        "roleId" => "required|integer",
        "typeId" => "required|integer",
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
            if (request()->has('search')) {
                return $this->search(request());
            }
            $allowance = (new Collection(AllowanceResource::collection(Allowance::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($allowance, "allowance retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error retrieving allowance", $th->getMessage());
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
            $allowance = new Allowance;
            $allowance->roleId = $request->roleId;
            $allowance->typeId = $request->typeId;
            $allowance->save();
            return $this->sendResponse(new AllowanceResource($allowance), "allowance created successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error creating allowance", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Allowance  $allowance
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $allowance = Allowance::findOrFail($id);
            return $this->sendResponse(new AllowanceResource($allowance), "allowance retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error retrieving allowance", $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Allowance  $allowance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $allowance = Allowance::findOrFail($id);
            $allowance->roleId = $request->roleId;
            $allowance->typeId = $request->typeId;
            $allowance->save();
            return $this->sendResponse($allowance, "allowance updated successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error updating allowance", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Allowance  $allowance
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $allowance = Allowance::findOrFail($id);
            $allowance->delete();
            return $this->sendResponse($allowance, "allowance deleted successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error deleting allowance", $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if ($request->filled('search')) {
                $query = Allowance::join('type_of_allowances', 'allowances.typeId', '=', 'type_of_allowances.typeId')
                                    ->join('roles', 'roles.roleId', '=', 'allowances.roleId')
                                    ->where('type_of_allowances.name', 'like', '%' . $request->search . '%')
                                    ->orwhere('roles.nameRole', 'like', '%' . $request->search . '%')
                                    ->get();
                $result =   (new Collection(AllowanceResource::collection($query)))->paginate(self::NumPaginate);
            } else {
                $result = (new Collection(AllowanceResource::collection(Allowance::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($result, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }
}

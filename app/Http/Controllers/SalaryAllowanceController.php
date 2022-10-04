<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\SalaryAllowanceResource;
use App\Models\SalaryAllowance;
use App\Support\Collection;
use Illuminate\Http\Request;

class SalaryAllowanceController extends BaseController
{
    const VALIDATION_RULES = [
        "salaryId" => "required|integer",
        "allowanceName" => "required|string|max:255",
        "nominal" => "required|integer",
    ];

    const NumPeginate = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $salaryAllowance = (new Collection(SalaryAllowanceResource::collection(SalaryAllowance::all())))->paginate(self::NumPeginate);
            return $this->sendResponse($salaryAllowance, "salary allowance retrieved succesfully"); 
        } catch (\Throwable $th) {
            return $this->sendError($salaryAllowance, "salary allowance retrieved succesfully"); 
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
            $salaryAllowance = new SalaryAllowance;
            $salaryAllowance->salaryId = $request->salaryId;
            $salaryAllowance->allowanceName = $request->allowanceName;
            $salaryAllowance->nominal = $request->nominal;
            $salaryAllowance->save();
            return $this->sendResponse(new SalaryAllowanceResource($salaryAllowance), "salary allowance created successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error creating salary allowance", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalaryAllowance  $salaryAllowance
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $salaryAllowance = SalaryAllowance::findOrFail($id);
            return $this->sendResponse(new SalaryAllowanceResource($salaryAllowance), "salary allowance retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error retrieving salary allowance", $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalaryAllowance  $salaryAllowance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $salaryAllowance = SalaryAllowance::findOrFail($id);
            $salaryAllowance->salaryId = $request->salaryId;
            $salaryAllowance->allowanceName = $request->allowanceName;
            $salaryAllowance->nominal = $request->nominal;
            $salaryAllowance->save();
            return $this->sendResponse($salaryAllowance, "salary allowance updated successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error updating salary allowance", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalaryAllowance  $salaryAllowance
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $salaryAllowance = SalaryAllowance::findOrFail($id);
            $salaryAllowance->delete();
            return $this->sendResponse($salaryAllowance, "salary allowance deleted successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error deleting salary allowance", $th->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\SalaryInsuranceDetailResource;
use App\Models\InsuranceItem;
use App\Models\Salary;
use App\Models\SalaryInsuranceDetail;
use App\Support\Collection;
use Illuminate\Http\Request;

class SalaryInsuranceDetailController extends BaseController
{
    const VALIDATION_RULES = [
        'salaryId' => 'required|integer',
        'insItemId' => 'required|integer',
        'date' => 'date',
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
            $salaryIns = (new Collection(SalaryInsuranceDetailResource::collection(SalaryInsuranceDetail::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($salaryIns, "salary insurance detail retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error retrieving salary insurance detail", $th->getMessage());
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
            $salary = Salary::findOrFail($request->salaryId);
            $insurance = InsuranceItem::findOrFail($request->insItemId);
            $nominal = ($salary->basic * $insurance->percent) / 100;
            $salaryIns = new SalaryInsuranceDetail;
            $salaryIns->salaryId = $request->salaryId;
            $salaryIns->insItemId = $request->insItemId;
            $salaryIns->nominal = $nominal;
            $salaryIns->date = $request->date;
            $salaryIns->save();
            return $this->sendResponse(new SalaryInsuranceDetailResource($salaryIns), "salary insurance detail created successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error creating salary insurance detail", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalaryInsuranceDetail  $salaryInsuranceDetail
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $salaryIns = SalaryInsuranceDetail::findOrFail($id);
            return $this->sendResponse(new SalaryInsuranceDetailResource($salaryIns), "salary insurance detail retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error retrieving salary insurance detail", $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalaryInsuranceDetail  $salaryInsuranceDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $salary = Salary::findOrFail($request->salaryId);
            $insurance = InsuranceItem::findOrFail($request->insItemId);
            $nominal = ($salary->basic * $insurance->percent) / 100;
            $salaryIns = SalaryInsuranceDetail::findOrFail($id);
            $salaryIns->salaryId = $request->salaryId;
            $salaryIns->insItemId = $request->insItemId;
            $salaryIns->nominal = $nominal;
            $salaryIns->date = $request->date;
            $salaryIns->save();
            return $this->sendResponse(new SalaryInsuranceDetailResource($salaryIns), "salary insurance detail updated successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error updating salary insurance detail", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalaryInsuranceDetail  $salaryInsuranceDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $salaryIns = SalaryInsuranceDetail::findOrFail($id);
            $salaryIns->delete();
            return $this->sendResponse($salaryIns, "salary insurance detail deleted sussessfully");
        } catch (\Throwable $th) {
            return $this->sendError("error deleting salary insurance detail");
        }
    }
}

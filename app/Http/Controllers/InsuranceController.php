<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\InsuranceResource;
use App\Models\Insurance;
use App\Models\InsuranceItem;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsuranceController extends BaseController
{
    const VALIDATION_RULES = [
        'name' => 'required|string|max:255',
        'companyName' => 'string|max:255',
        'address' => 'string|max:255',
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
            $insurance = (new Collection(InsuranceResource::collection(Insurance::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($insurance, "Insurance retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error retrieving Insurance", $th->getMessage());
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
            $insurance = new Insurance;
            $insurance->name = $request->name;
            $insurance->companyName = $request->companyName;
            $insurance->address = $request->address;
            $insurance->save();
            return $this->sendResponse(new InsuranceResource($insurance), 'Insurance created successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error creating insurance');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        try {
            $insurance = Insurance::findOrFail($id);
            if ($request->search != null) {
                $search = $request->search;
                $insItem = InsuranceItem::where('insuranceId', $id)
                ->where('name', 'like', '%'. $search . '%')
                ->paginate(self::NumPaginate);
            } else {
                $insItem = InsuranceItem::where('insuranceId', $id)->paginate(self::NumPaginate);
            }
            $result = [
                'id' => $insurance->insuranceId,
                'name' => $insurance->name,
                'company' => $insurance->companyName,
                'address' => $insurance->address,
                'data' => $insItem
            ];
            return $this->sendResponse($result, 'Insurance retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving insurance', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $insurance = Insurance::findOrFail($id);
            $insurance->name = $request->name;
            $insurance->companyName = $request->companyName;
            $insurance->address = $request->address;
            $insurance->save();
            return $this->sendResponse($insurance, 'Insurance updated successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating insurance', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $insurance = Insurance::findOrFail($id);
            $insurance->delete();
            return $this->sendResponse($insurance, 'Insurance deleted successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error deleting insurance', $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if ($request->filled('search')) {
                $insurance =   (new Collection(InsuranceResource::collection(Insurance::search($request->search)->get())))->paginate(self::NumPaginate);
            } else {
                $insurance = (new Collection(InsuranceResource::collection(Insurance::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($insurance, "insurance search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search insurance failed", $th->getMessage());
        }
    }
}

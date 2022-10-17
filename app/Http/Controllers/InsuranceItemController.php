<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\InsuranceItemResource;
use App\Http\Resources\InsuranceResource;
use App\Models\Insurance;
use App\Models\InsuranceItem;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsuranceItemController extends BaseController
{
    const VALIDATION_RULES = [
        'insuranceId' => 'required|integer',
        'name' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'percent' => 'required',
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
            $insItem = (new Collection(InsuranceItemResource::collection(InsuranceItem::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($insItem, "Insurance item retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error retrieving insurance item", $th->getMessage());
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
            $insItem = new InsuranceItem;
            $insItem->insuranceId = $request->insuranceId;
            $insItem->name = $request->name;
            $insItem->type = $request->type;
            $insItem->percent = $request->percent;
            $insItem->save();
            return $this->sendResponse(new InsuranceItemResource($insItem), "Insurance item created successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error creating insurance item", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InsuranceItem  $insuranceItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $insItem = InsuranceItem::findOrFail($id);
            return $this->sendResponse(new InsuranceItemResource($insItem), "Insurance item retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error retrieving insurance item", $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InsuranceItem  $insuranceItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'percent' => 'required',
            ]);
            $insItem = InsuranceItem::findOrFail($id);
            $insItem->name = $request->name;
            $insItem->type = $request->type;
            $insItem->percent = $request->percent;
            $insItem->save();
            return $this->sendResponse($insItem, "Insurance item updated successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error updating insurance item", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InsuranceItem  $insuranceItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $insItem = InsuranceItem::findOrFail($id);
            $insItem->delete();
            return $this->sendResponse(new InsuranceItemResource($insItem), "Insurance item deleted successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error deleting insurance item", $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if ($request->filled('search')) {
                $insItem =   (new Collection(InsuranceItemResource::collection(InsuranceItem::search($request->search)->get())))->paginate(self::NumPaginate);
            } else {
                $insItem = (new Collection(InsuranceItemResource::collection(InsuranceItem::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($insItem, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }
}

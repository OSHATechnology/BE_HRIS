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
        'name' => 'required|string|min:4|max:30',
        'companyName' => 'required|string|min:4|max:30',
        'address' => 'required|string|min:6|max:50',
    ];

    const MessageError = [
        'name.required' => 'Nama tidak boleh kosong',
        'name.min' => 'Nama minimal 4 karakter',
        'name.max' => 'Nama maksimal 30 karakter',
        'companyName.required' => 'Company name tidak boleh kosong',
        'companyName.min' => 'Company name minimal 4 karakter',
        'companyName.max' => 'Company name maksimal 30 karakter',
        'address.required' => 'Address tidak boleh kosong',
        'address.min' => 'Address minimal 6 karakter',
        'address.max' => 'Address maksimal 50 karakter',
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
            $this->authorize('viewAny', Insurance::class);

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
            $this->authorize('create', Insurance::class);
            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
            $insurance = new Insurance;
            $insurance->name = $request->name;
            $insurance->companyName = $request->companyName;
            $insurance->address = $request->address;
            $insurance->save();
            return $this->sendResponse(new InsuranceResource($insurance), 'Insurance created successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error creating insurance', $th->getMessage());
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
            $this->authorize('view', Insurance::class);
            $insurance = Insurance::findOrFail($id);
            if ($request->search != null) {
                $search = $request->search;
                $insItem = InsuranceItem::with('roles')->where('insuranceId', $id)
                    ->where('name', 'like', '%' . $search . '%')
                    ->paginate(self::NumPaginate);
            } else {
                $insItem = InsuranceItem::with('roles')->where('insuranceId', $id)->paginate(self::NumPaginate);
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
            $this->authorize('update', Insurance::class);
            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
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
            $this->authorize('delete', Insurance::class);
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

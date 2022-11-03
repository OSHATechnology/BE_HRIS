<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\TypeOfAllowanceResource;
use App\Models\TypeOfAllowance;
use App\Support\Collection;
use Illuminate\Http\Request;

class TypeOfAllowanceController extends BaseController
{
    const VALIDATION_RULES = [
        "name" => "required|string|min:4|max:30",
        "nominal" => "required|integer|digits_between:5,7",
    ];

    const MessageError = [
        'name.required' => 'Nama tidak boleh kosong',
        'name.min' => 'Nama minimal 4 karakter',
        'name.max' => 'Nama maksimal 30 karakter',
        'nominal.required' => 'Nominal tidak boleh kosong',
        'nominal.digits_between' => 'nominal minimal 5 digit dan maksimal gaji 7 digit'
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
            if (request()->has('showAll')) {
                $type = TypeOfAllowanceResource::collection(TypeOfAllowance::all());
            } else {
                $type = (new Collection(TypeOfAllowanceResource::collection(TypeOfAllowance::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($type, "type of allowance retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error retrieving type of allowance");
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
            $type = new TypeOfAllowance;
            $type->name = $request->name;
            $type->nominal = $request->nominal;
            $type->save();
            return $this->sendResponse(new TypeOfAllowanceResource($type), "type of allowance created successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error creating type of allowance", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypeOfAllowance  $typeOfAllowance
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $type = TypeOfAllowance::findOrFail($id);
            return $this->sendResponse(new TypeOfAllowanceResource($type), "type of allowance retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error retrieving type of allowance", $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypeOfAllowance  $typeOfAllowance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
            $type = TypeOfAllowance::findOrFail($id);
            $type->name = $request->name;
            $type->nominal = $request->nominal;
            $type->save();
            return $this->sendResponse($type, "type of allowance updated successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error updating type of allowance", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypeOfAllowance  $typeOfAllowance
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $type = TypeOfAllowance::findOrFail($id);
            $type->delete();
            return $this->sendResponse($type, "type of allowance deleted successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error deleting type of allowance", $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if ($request->filled('search')) {
                $users =   (new Collection(TypeOfAllowanceResource::collection(TypeOfAllowance::search($request->search)->get())))->paginate(self::NumPaginate);
            } else {
                $users = (new Collection(TypeOfAllowanceResource::collection(TypeOfAllowance::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($users, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }
}

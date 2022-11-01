<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\FurloughTypeResource;
use App\Models\FurloughType;
use App\Support\Collection;
use Illuminate\Http\Request;

class FurloughTypeController extends BaseController
{
    const VALIDATION_RULES = [
        'name' => 'required|string|min:4|max:30',
        'type' => 'required',
        'max' => 'required|integer|digits_between:1,12',
    ];

    const MessageError = [
        'name.required' => 'Nama furlough type tidak boleh kosong',
        'name.min' => 'Nama furlough type minimal 4 karakter',
        'name.max' => 'Nama furlough type tidak boleh lebih dari 30 karakter',
        'type.required' => 'Anda harus memilih type terlebih dahulu',
        'max.required' => 'Duration furlough type tidak boleh kosong',
        'max.digits_between' => 'Duration furlough type minimal 1 angka dan maksimal 12 angka',
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

            if (request()->has('show_all')) {
                $showAll = request()->show_all;
            } else {
                $showAll = false;
            }

            if ($showAll) {
                $type = FurloughType::all();
            } else {
                $type = (new Collection(FurloughType::all()))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($type, 'Furlough type retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving furlough type', $th->getMessage());
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
            $request->validate(self::VALIDATION_RULES, self::MessageError);

            $type = new FurloughType;
            $type->name = $request->name;
            $type->type = $request->type;
            $type->max = $request->max;
            $type->save();
            return $this->sendResponse($type, 'Furlough type created successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error creating furlough type', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FurloughType  $furloughType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $type = FurloughType::findOrFail($id);
            return $this->sendResponse($type, 'Furlough type retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving furlough type', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FurloughType  $furloughType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate(self::VALIDATION_RULES, self::MessageError);
            $type = FurloughType::findOrFail($id);
            $type->name = $request->name;
            $type->type = $request->type;
            $type->max = $request->max;
            $type->save();
            return $this->sendResponse($type, 'Furlough type updated successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating furlough type', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FurloughType  $furloughType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $type = FurloughType::findOrFail($id);
            $type->delete();
            return $this->sendResponse($type, 'Furlough type deleted successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error deleting furlough type', $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        // dd($request->search);
        try {
            if ($request->filled('search')) {
                $users =   (new Collection((FurloughType::search($request->search)->get())))->paginate(self::NumPaginate);
            } else {
                $users = (new Collection((FurloughType::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($users, "furlough type search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search furlough type failed", $th->getMessage());
        }
    }
}

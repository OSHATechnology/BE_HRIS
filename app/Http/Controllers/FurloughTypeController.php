<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\FurloughType;
use App\Support\Collection;
use Illuminate\Http\Request;

class FurloughTypeController extends BaseController
{
    const VALIDATION_RULES = [
        'name' => 'required|string|max:255', 
        'type' => 'required|string|max:255', 
        'max' => 'required|integer',
    ];

    const NumPaginate = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if(request()->has('search')){
                return $this->search(request());
            }
            $type = (new Collection(FurloughType::all()))->paginate(self::NumPaginate);
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
            $request->validate(self::VALIDATION_RULES);

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
            return $this->sendError('Error retrieving furlough type', 'Data Not Found');
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
            $request->validate(self::VALIDATION_RULES);
            $type = FurloughType::findOrFail($id);
            $type->name = $request->name;
            $type->type = $request->type;
            $type->max = $request->max;
            $type->save();
            return $this->sendResponse($type, 'Furlough type updated successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating furlough type', 'Data Not Found');
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
            return $this->sendError('Error deleting furlough type', 'Data Not Found');
        }
    }

    public function search(Request $request)
    {
        // dd($request->search);
        try {
            if($request->filled('search')){
                $users =   (new Collection((FurloughType::search($request->search)->get())))->paginate(self::NumPaginate);
            }else{
                $users = (new Collection((FurloughType::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($users, "furlough type search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search furlough type failed", $th->getMessage());
        }
    }
}

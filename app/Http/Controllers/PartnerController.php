<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends BaseController
{

    const VALIDATION_RULES = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'responsibleBy' => 'required|string|max:255',
        'phone' => 'required|max:50',
        'address' => 'required|string|max:255',
        'assignedBy' => 'required',
        'joinedAt' => 'required'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $partners = Partner::all();
            return $this->sendResponse($partners, 'Partners retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving partners.', $th->getMessage());
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
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = $request->name . '-' . time() . '.' . $logo->getClientOriginalExtension();
                $path = 'partners/' . $request->name . '/' . $logoName;
                Storage::disk('public')->put('partners/' . $request->name . '/' . $logoName, file_get_contents($logo));
            } else {
                $path = 'default.png';
            }

            $partner = Partner::create([
                'name' => $request->name,
                'description' => $request->description,
                'responsibleBy' => $request->responsibleBy,
                'phone' => $request->phone,
                'address' => $request->address,
                'assignedBy' => $request->assignedBy,
                'joinedAt' => $request->joinedAt,
                'photo' => $path
            ]);
            return $this->sendResponse($partner, 'Partner created successfully.', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error creating partner.', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show($partnerId)
    {
        try {
            $partner = Partner::findOrFail($partnerId);
            return $this->sendResponse($partner, 'Partner retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving partner.', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES);
            $partner->update($request->all());
            return $this->sendResponse($partner, 'Partner updated successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error updating partner.', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends BaseController
{

    const VALIDATION_RULES = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'resposibleBy' => 'required|string|max:255',
        'phone' => 'required|max:50',
        'address' => 'required|string|max:255',
        'assignedBy' => 'required',
        'joinedAt' => 'required'
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
            $this->authorize('viewAny', Partner::class);

            $partners = PartnerResource::collection(Partner::paginate(self::NumPaginate));
            return $this->sendResponse($partners, 'Partners retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), []);
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
            $this->authorize('create', Partner::class);

            $this->validate($request, self::VALIDATION_RULES);
            if ($request->hasFile('photo')) {
                $logo = $request->file('photo');
                $logoName = $request->name . '-' . time() . '.' . $logo->getClientOriginalExtension();
                $path = 'partners/' . $logoName;
                Storage::disk('public')->put('partners/' . $logoName, file_get_contents($logo));
            } else {
                $path = 'default.png';
            }

            $partner = Partner::create([
                'name' => $request->name,
                'description' => $request->description,
                'resposibleBy' => $request->resposibleBy,
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
            $this->authorize('view', Partner::class);

            $partner = new PartnerResource(Partner::findOrFail($partnerId));
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
    public function update(Request $request, $partnerId)
    {
        try {
            //gate
            $this->authorize('update', Partner::class);

            $this->validate($request, self::VALIDATION_RULES);
            $Partner = Partner::findOrFail($partnerId);
            if ($request->hasFile('photo')) {
                $logo = $request->file('photo');
                $logoName = $request->name . '-' . time() . '.' . $logo->getClientOriginalExtension();
                $path = 'partners/' . $logoName;
                Storage::disk('public')->put('partners/' . $logoName, file_get_contents($logo));
            } else {
                $path = $Partner->photo;
            }
            $Partner->update([
                'name' => $request->name,
                'description' => $request->description,
                'resposibleBy' => $request->resposibleBy,
                'phone' => $request->phone,
                'address' => $request->address,
                'assignedBy' => $request->assignedBy,
                'joinedAt' => $request->joinedAt,
                'photo' => $path
            ]);
            return $this->sendResponse($Partner, 'Partner updated successfully.');
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
    public function destroy($partnerId)
    {
        try {
            $this->authorize('delete', Partner::class);

            $partner = Partner::findOrFail($partnerId);
            $partner->delete();
            return $this->sendResponse("success deleted", 'Partner deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error deleting partner.', $th->getMessage());
        }
    }
}

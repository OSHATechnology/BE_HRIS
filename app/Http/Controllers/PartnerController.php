<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends BaseController
{

    const VALIDATION_RULES = [
        'name' => 'required|string|min:4|max:30',
        'description' => 'required|string|min:10|max:255',
        'resposibleBy' => 'required|string|min:4|max:30',
        'phone' => 'required|min:10|max:13',
        'address' => 'required|string|min:6|max:50',
        'assignedBy' => 'required',
        'joinedAt' => 'required'
    ];

    const MessageError = [
        'name.required' => 'Nama perusahaan tidak boleh kosong',
        'name.min' => 'Nama perusahaan minimal 4 karakter',
        'name.max' => 'Nama perusahaan maksimal 30 karakter',
        'description.required' => 'Deskripsi perusahaan tidak boleh kosong',
        'description.min' => 'Deskripsi perusahaan minimal 10 karakter',
        'description.max' => 'Deskripsi perusahaan maksimal 255 karakter',
        'resposibleBy.required' => 'resposibleBy tidak boleh kosong',
        'resposibleBy.min' => 'resposibleBy minimal 4 karakter',
        'resposibleBy.max' => 'resposibleBy maksimal 30 karakter',
        'phone.required' => 'Nomor telepon tidak boleh kosong',
        'phone.min' => 'Nomor telepon minimal 10 karakter',
        'phone.max' => 'Nomor telepon maksimal 13 karakter',
        'address.required' => 'Alamat tidak boleh kosong',
        'address.min' => 'Alamat minimal 6 karakter',
        'address.max' => 'Alamat maksimal 50 karakter',
        'assignedBy.required' => 'assignedBy tidak boleh kosong',
        'joinedAt.required' => 'joinedAt tidak boleh kosong',
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
            $this->authorize('viewAny', Partner::class);

            if (request()->has('search')) {
                return $this->search(request());
            }

            $partners = (new Collection(PartnerResource::collection(Partner::all())))->paginate(self::NumPaginate);
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

            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
            if ($request->hasFile('photo')) {
                $logo = $request->file('photo');
                $logoName = $request->name . '-' . time() . '.' . $logo->getClientOriginalExtension();
                $path = 'storage/partners/' . $logoName;
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

            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
            $Partner = Partner::findOrFail($partnerId);
            if ($request->hasFile('photo')) {
                $logo = $request->file('photo');
                $logoName = $request->name . '-' . time() . '.' . $logo->getClientOriginalExtension();
                $path = 'storage/partners/' . $logoName;
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

    public function search(Request $request)
    {
        try {
            if ($request->filled('search')) {
                $partner =   (new Collection(PartnerResource::collection(Partner::search($request->search)->get())))->paginate(self::NumPaginate);
            } else {
                $partner = (new Collection(PartnerResource::collection(Partner::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($partner, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }
}

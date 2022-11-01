<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\InsuranceItem;
use App\Models\Role;
use Illuminate\Http\Request;

class InsuranceItemRoleController extends BaseController
{
    const VALIDATION_RULES = [
        'insuranceItemId' => 'required|integer',
        'roleId' => 'required|integer',
    ];

    const MessageError = [
        'InsuranceItemId.required' => 'Insurance tidak boleh kosong',
        'roleId.required' => 'role tidak boleh kosong',
    ];

    public function store(Request $request)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
            $InsuranceItem = InsuranceItem::findOrFail($request->insuranceItemId);
            $role = Role::findOrFail($request->roleId);
            $InsuranceItem->roles()->attach($role);
            return $this->sendResponse($InsuranceItem, 'InsuranceItemRole created successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), []);
        }
    }

    public function detachAllRoles(Request $request)
    {
        try {
            $this->validate($request, [
                'insuranceItemId' => 'required|integer',
            ], self::MessageError);
            $InsuranceItem = InsuranceItem::findOrFail($request->insuranceItemId);
            $InsuranceItem->roles()->detach();
            return $this->sendResponse($InsuranceItem, 'InsuranceItem detached successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [$request->roleId]);
        }
    }
}

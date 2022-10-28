<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\InsuranceItem;
use App\Models\Role;
use Illuminate\Http\Request;

class InsuranceItemRoleController extends BaseController
{
    public function store(Request $request)
    {
        try {
            $InsuranceItem = InsuranceItem::findOrFail($request->insuranceItemId);
            $role = Role::findOrFail($request->roleId);
            $InsuranceItem->roles()->attach($role);
            return $this->sendResponse($InsuranceItem, 'InsuranceItemRole created successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), []);
        }
    }
}

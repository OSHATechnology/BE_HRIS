<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Support\Collection;
use Illuminate\Http\Request;

class RolePermissionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    const NumPaginate = 10;

    public function index()
    {
        try {
            $rolePermissions = (new Collection(RolePermission::all()))->paginate(self::NumPaginate);
            return $this->sendResponse($rolePermissions, 'RolePermissions retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), []);
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
            $role = Role::find($request->roleId);
            $permission = Permission::find($request->permissionId);
            $role->permissions()->attach($permission);
            return $this->sendResponse($role, 'RolePermission created successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), []);
        }
    }

    public function detachPermissionFromRole(Request $request)
    {
        try {
            $role = Role::findOrFail($request->roleId);
            $permission = Permission::findOrFail($request->permissionId);
            $role->permissions()->detach($permission);
            return $this->sendResponse($role, 'RolePermission detached successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), []);
        }
    }
}

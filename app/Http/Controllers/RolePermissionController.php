<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $rolePermissions = RolePermission::all();
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => [
                    $rolePermissions
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'error',
                'data' => [
                    'error' => $e->getMessage()
                ]
            ]);
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
            return response()->json([
                'code' => 200,
                'message' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'error',
                'data' => [
                    'error' => $e->getMessage()
                ]
            ]);
        }
    }

    public function detachPermissionFromRole(Request $request)
    {
        try {
            $role = Role::findOrFail($request->roleId);
            $permission = Permission::findOrFail($request->permissionId);
            $role->permissions()->detach($permission);
            return response()->json([
                'code' => 200,
                'message' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'error',
                'data' => [
                    'error' => $e->getMessage()
                ]
            ]);
        }
    }
}

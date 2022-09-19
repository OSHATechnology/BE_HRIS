<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends BaseController
{

    const VALIDATION_RULES = [
        'namePermission' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'tag' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
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
            //gate
            $this->authorize('viewAny', Permission::class);

            $permissions = Permission::paginate(self::NumPaginate);
            return $this->sendResponse($permissions, 'Permissions retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving permissions', $th->getMessage());
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
            //gate
            $this->authorize('create', Permission::class);

            $this->validate($request, self::VALIDATION_RULES);
            $permission = Permission::create($request->all());
            return $this->sendResponse($permission, 'Permission created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error creating permission', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show($permissionId)
    {
        try {
            //gate
            $this->authorize('view', Permission::class);

            $Permission = Permission::findOrFail($permissionId);
            return $this->sendResponse($Permission, 'Permission retrieved successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error retrieving permission', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $permissionId)
    {
        try {
            //gate
            $this->authorize('update', Permission::class);

            $this->validate($request, self::VALIDATION_RULES);
            $permission = Permission::findOrFail($permissionId);
            $permission->update($request->all());
            return $this->sendResponse($permission, 'Permission updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error updating permission', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($permissionId)
    {
        try {
            //gate
            $this->authorize('delete', Permission::class);

            $permission = Permission::findOrFail($permissionId);
            $permission->delete();
            return $this->sendResponse($permission, 'Permission deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error deleting permission', $th->getMessage());
        }
    }
}

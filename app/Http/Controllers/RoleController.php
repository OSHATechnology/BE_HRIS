<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Role;
use App\Support\Collection;
use Illuminate\Http\Request;

class RoleController extends BaseController
{

    /**
     * Add rules for validation
     *
     * @return array
     */
    const VALIDATION_RULES = [
        'nameRole' => 'required|string|max:255',
        'description' => 'required|string|max:255',
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
            //gate
            $this->authorize('viewAny', Role::class);

            //search
            if(request()->has('search')){
                return $this->search(request());
            }

            $roles = (new Collection(Role::all()))->paginate(self::NumPaginate);
            return $this->sendResponse($roles, 'Roles retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving roles', $th->getMessage());
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
            $this->authorize('create', Role::class);

            //validation
            $request->validate(self::VALIDATION_RULES);

            //store role
            $role = Role::create($request->all());
            return $this->sendResponse($role, 'Role created successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error creating role', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($roleId)
    {
        try {
            //gate
            $this->authorize('view', Role::class);

            $Role = Role::findOrFail($roleId);
            return $this->sendResponse($Role, 'Role retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving role', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $roleId)
    {
        try {
            //gate
            $this->authorize('update', Role::class);

            //validation
            $request->validate(self::VALIDATION_RULES);

            // action
            $Role = Role::findOrFail($roleId);
            $Role->update($request->all());

            return $this->sendResponse($Role, 'Role updated successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error updating role', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $roleId
     * @return \Illuminate\Http\Response
     */
    public function destroy($roleId)
    {
        try {
            //gate
            $this->authorize('delete', Role::class);

            $Role = Role::findOrFail($roleId);
            $Role->delete();

            return $this->sendResponse($Role, 'Role deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Error deleting role', $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if($request->filled('search')){
                $partner =   (new Collection(Role::search($request->search)->get()))->paginate(self::NumPaginate);
            }else{
                $partner = (new Collection(Role::all()))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($partner, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }
}

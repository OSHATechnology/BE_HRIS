<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    const VALIDATION_RULES = [
        'namePermission' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'tag' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $permissions = Permission::all();
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => $permissions
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'error'
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
            $this->validate($request, self::VALIDATION_RULES);
            $permission = Permission::create($request->all());
            return response()->json([
                'code' => 201,
                'message' => 'success',
                'data' => $permission
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'code' => 500,
                'message' => 'error',
                'data' => [
                    'error' => $th->getMessage()
                ]
            ]);
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
            $Permission = Permission::findOrFail($permissionId);
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => [
                    'permission' => $Permission,
                    'roles' => $Permission->roles
                ]
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'code' => 500,
                'message' => 'error',
                'data' => [
                    'error' => $th->getMessage()
                ]
            ]);
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
            $this->validate($request, self::VALIDATION_RULES);
            $permission = Permission::findOrFail($permissionId);
            $permission->update($request->all());
            return response()->json([
                'code' => 200,
                'message' => 'success updated',
                'data' => $permission
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'code' => 500,
                'message' => 'error',
                'data' => [
                    'error' => $th->getMessage()
                ]
            ]);
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
            $permission = Permission::findOrFail($permissionId);
            $permission->delete();
            return response()->json([
                'code' => 200,
                'message' => 'success deleted',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'code' => 500,
                'message' => 'error',
                'data' => [
                    'error' => $th->getMessage()
                ]
            ]);
        }
    }
}

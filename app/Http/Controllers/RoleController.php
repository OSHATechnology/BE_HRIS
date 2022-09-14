<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('viewAny', Role::class);
        try {
            $roles = Role::all();
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => [
                    'roles' => $roles
                ]
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
            //gate
            // $this->authorize('create', Role::class);

            //validation
            $request->validate(self::VALIDATION_RULES);

            //store role
            $role = Role::create($request->all());
            return response()->json([
                'code' => 201,
                'message' => 'success',
                'data' => [
                    'role' => $role
                ]
            ]);
        } catch (\Throwable $th) {
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
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($roleId)
    {
        try {
            $Role = Role::findOrFail($roleId);
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => $Role
            ]);
        } catch (\Throwable $th) {
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
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $roleId)
    {
        try {
            $request->validate(self::VALIDATION_RULES);

            // update role
            $Role = Role::findOrFail($roleId);
            $Role->update($request->all());

            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => $Role
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
     * @param  $roleId
     * @return \Illuminate\Http\Response
     */
    public function destroy($roleId)
    {
        try {
            $Role = Role::findOrFail($roleId);
            $Role->delete();

            return response()->json([
                'code' => 200,
                'message' => 'success delete role',
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

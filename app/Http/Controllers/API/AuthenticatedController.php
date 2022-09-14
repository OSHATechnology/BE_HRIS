<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedController extends BaseController
{

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }

        $user = Employee::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'token' => $token
        ];

        return $this->sendResponse($response, 'User login successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;

class GoogleController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function linkedGoogleAccount(Request $request)
    {
        try {
            $request->validate([
                'creds' => 'required',
            ]);
            $tokenId = $request->creds;
            $data = Http::get('https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $tokenId);
            $finduser = Employee::where('google_id', $data['sub'])->first();
            if ($finduser) {
                $response = "";
                return $this->sendResponse($response, 'Failed to link google account.');
            } else {
                $linkedAccount = Employee::where('employeeId', auth()->user()->employeeId)->first();
                $linkedAccount->google_id = $data['sub'];
                if ($linkedAccount->save()) {
                    $response = "linked successfully";
                    return $this->sendResponse($response, 'Successfully linked google account.');
                } else {
                    $response = "linked failed";
                    return $this->sendResponse($response, 'Successfully linked google account.');
                }
                return $this->sendError('Error', [], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Unautorized.', ['error' => 'Unautorized'], 401);
        }
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function callback(Request $request)
    {
        $request->validate([
            'creds' => 'required',
        ]);
        $tokenId = $request->creds;
        $data = Http::get('https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $tokenId);
        if (!$data) {
            return $this->sendError('Unautorized.', ['error' => 'Unautorized'], 401);
        }

        $finduser = Employee::where('google_id', $data['sub'])->first();
        if ($finduser) {
            $token = $finduser->createToken('token')->plainTextToken;
            $dataUser = [
                'id' => $finduser->employeeId,
                'name' => $finduser->firstName . " " . $finduser->lastName,
                'email' => $finduser->email,
                'role' => $finduser->role->nameRole,
                'token' => $token
            ];
            $response = [
                'user' => $dataUser
            ];

            Auth::loginUsingId($finduser->employeeId);

            return $this->sendResponse($response, 'User login successfully.');
        } else {
            return $this->sendError('Cannot find employee with this google account', 401);
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    const MESSAGE_ERROR = [
        "This action is unauthorized." => [
            "code" => 401,
            "message" => "unauthorized."
        ],
    ];

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        if (array_key_exists($error, self::MESSAGE_ERROR)) {
            $code = self::MESSAGE_ERROR[$error]['code'];
        }


        return response()->json($response, $code);
    }

    /**
     * return model class.
     *
     * @return \Illuminate\Http\Response
     */
    public function getModel($name)
    {
        switch ($name) {
            case 'employee':
                $modelName = 'App\Models\Employee';
                break;

            case 'role':
                $modelName = 'App\Models\Role';
                break;

            case 'partner':
                $modelName = 'App\Models\Partner';
                break;

            case 'permission':
                $modelName = 'App\Models\Permission';
                break;

            case 'team':
                $modelName = 'App\Models\Team';
                break;

            case 'allowance':
                $modelName = 'App\Models\Allowance';
                break;

            case 'total-leave':
                $modelName = 'App\Models\Attendance';
                break;

            default:
                # code...
                break;
        }
        return new $modelName;
    }
}

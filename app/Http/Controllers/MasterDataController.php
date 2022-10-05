<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;

class MasterDataController extends BaseController
{
    /**
     * Display count data.
     *
     * @return Reponse
     */
    public function count(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|string'
            ]);

            $type = $request->type;

            $model = $this->getModel($type);
            $count = $model::count();

            return $this->sendResponse($count, 'Count ' . $type . ' successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Data Not Found!.', ['error' => 'not found'], 404);
        }
    }
}

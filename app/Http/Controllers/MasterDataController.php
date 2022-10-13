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

            if ($type == 'total-leave') {
                $request->validate([
                    'empId' => 'required|integer'
                ]);
                $model = $model::where('attendanceStatusId', '!=', 1)->where('employeeId', $request->empId)->count();
            }

            if ($type !== 'total-leave') {
                $count = $model::count();
            } else {
                $count = $model;
            }

            return $this->sendResponse($count, 'Count ' . $type . ' successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Data Not Found!.', $th->getMessage(), 404);
        }
    }
}

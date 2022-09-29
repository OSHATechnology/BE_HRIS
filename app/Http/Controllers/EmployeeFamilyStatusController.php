<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\EmployeeFamilyStatusResource;
use App\Models\Employee;
use App\Models\EmployeeFamilyStatus;
use App\Support\Collection;
use Illuminate\Http\Request;

class EmployeeFamilyStatusController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $famStat = (new Collection(EmployeeFamilyStatusResource::collection(EmployeeFamilyStatus::all())));
            return $this->sendResponse($famStat, 'Employee Family Status retrieved successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error retrieving Employee Family Status', $th->getMessage());
        }
    }

}

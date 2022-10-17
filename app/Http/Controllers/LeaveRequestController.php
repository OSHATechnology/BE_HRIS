<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\FurloughResource;
use App\Models\Furlough;
use App\Models\Overtime;
use App\Models\WorkPermit;
use App\Support\Collection;
use Illuminate\Http\Request;

class LeaveRequestController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $sort = $request->get('sort', 'asc');
            $collection = new Collection();
            $furloughs = Furlough::with('employee')->where('isConfirmed', 0)->get();

            foreach ($furloughs as $key => $value) {
                $collection->push([
                    'id' => 'f-' . $value->furloughId,
                    'type' => 'furlough',
                    'employee' => $value->employee ? [
                        'id' => $value->employee->employeeId,
                        'name' => $value->employee->firstName . ' ' . $value->employee->lastName,
                        'role' => $value->employee->role ? $value->employee->role->nameRole : null,
                    ] : null,
                    'startAt' => $value->startAt,
                    'endAt' => $value->endAt,
                    'requestAt' => $value->created_at,
                ]);
            }

            $overtimes = Overtime::with('employee')->where('isConfirmed', 0)->get();

            foreach ($overtimes as $key => $value) {
                $collection->push([
                    'id' => 'o-' . $value->overtimeId,
                    'type' => 'overtime',
                    'employee' => $value->employee ? [
                        'id' => $value->employee->employeeId,
                        'name' => $value->employee->firstName . ' ' . $value->employee->lastName,
                        'role' => $value->employee->role ? $value->employee->role->nameRole : null,
                    ] : null,
                    'startAt' => $value->startAt,
                    'endAt' => $value->endAt,
                    'requestAt' => $value->created_at,
                ]);
            }

            $workPermits = WorkPermit::with('employee')->where('isConfirmed', 0)->get();

            foreach ($workPermits as $key => $value) {
                $collection->push([
                    'id' => 'wp-' . $value->workPermitId,
                    'type' => 'workPermit',
                    'employee' => $value->employee ? [
                        'id' => $value->employee->employeeId,
                        'name' => $value->employee->firstName . ' ' . $value->employee->lastName,
                        'role' => $value->employee->role ? $value->employee->role->nameRole : null,
                    ] : null,
                    'startAt' => $value->startAt,
                    'endAt' => $value->endAt,
                    'requestAt' => $value->created_at,
                ]);
            }

            if ($sort == 'desc') {
                $collection = $collection->sortByDesc('requestAt');
            } else {
                $collection = $collection->sortBy('requestAt');
            }

            $collection = $collection->values();

            return $this->sendResponse($collection->paginate(10), "data retrieved successfully");
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

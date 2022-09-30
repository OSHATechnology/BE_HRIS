<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\InstalmentResource;
use App\Models\Instalment;
use App\Models\Loan;
use App\Models\Salary;
use App\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstalmentController extends BaseController
{
    const VALIDATION_RULES = [
        'loanId' => 'required|integer',
        'date' => 'required|date'
    ];

    const NumPaginate = 10;
    const Percent = 5;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $instalment = (new Collection(InstalmentResource::collection(Instalment::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($instalment, "instalment retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error retrieving instalment");
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
            $loan = Loan::findOrFail($request->loanId);
            $basicSalary = Salary::where('empId', $loan->empId)->first();
            $allowance = ($basicSalary->basic * self::Percent) / 100;
            $instalment = new Instalment;
            if ($loan !== null) {
                $lastRemainder = Instalment::getLastRemainder($request->loanId);
                if ($lastRemainder !== null) {
                    if ($allowance > $lastRemainder) {
                        $nominal = $lastRemainder;
                        $remainder = $lastRemainder - $lastRemainder;
                    } else {
                        $nominal = $allowance;
                        $remainder = $lastRemainder - $allowance;
                    }
                } else {
                    $nominal = $allowance;
                    $remainder = $loan->nominal - $allowance;
                }
            }
            $instalment->loanId = $request->loanId;
            $instalment->date = $request->date;
            $instalment->nominal = $nominal;
            $instalment->remainder = $remainder;

            if ($remainder === 0) {
                $loan->paymentDate = now();
                $loan->status = 1;
                $loan->save();
            }

            $instalment->save();
            return $this->sendResponse($instalment, "instalment created successfully");
        } catch (\Throwable $th) {
            return $this->sendResponse("error creating instalment", $th->getMessage());
        }
    }

    public function showByLoan($loanId)
    {
        try {
            $instalment = (new Collection(InstalmentResource::collection(Instalment::where('loanId', $loanId)->get())))->paginate(self::NumPaginate);
            return $this->sendResponse($instalment, "instalment retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError($instalment, "Error retrieving instalment");
        }
    }
}

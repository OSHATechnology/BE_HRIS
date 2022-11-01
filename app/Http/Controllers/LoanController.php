<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\LoanResource;
use App\Models\Loan;
use App\Support\Collection;
use Illuminate\Http\Request;

class LoanController extends BaseController
{
    const VALIDATION_RULES = [
        'empId' => 'required|integer',
        'name' => 'required|string|min:4|max:30',
        'nominal' => 'required|integer|digits_between:5,7',
        'loanDate' => 'required|date',
        'status' => 'required|boolean',
    ];

    const MessageError = [
        'empId.required' => 'Anda harus mengisi karyawan terlebih dahulu',
        'name.required' => 'Nama loan tidak boleh kosong',
        'name.min' => 'Nama loan minimal 4 karakter',
        'name.max' => 'Nama loan maksimal 20 karakter',
        'nominal.required' => 'Nominal tidak boleh kosong',
        'nominal.digits_between' => 'Nominal loan minimal 5 digit dan maksimal 7 digit',
        'loanDate.required' => 'loan date harus diisi terlebih dahulu',
    ];

    const NumPaginate = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (request()->has('search')) {
                return $this->search(request());
            }
            $loan = (new Collection(LoanResource::collection(Loan::all())))->paginate(self::NumPaginate);
            return $this->sendResponse($loan, "loan retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error retrieving loan");
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
            $this->validate($request, self::VALIDATION_RULES, self::MessageError);

            $statusLastLoan = Loan::getLastLoan($request->empId);
            if ($statusLastLoan === null) {
                $loan = new Loan;
                $loan->empId = $request->empId;
                $loan->name = $request->name;
                $loan->nominal = $request->nominal;
                $loan->loanDate = $request->loanDate;
                $loan->paymentDate = $request->paymentDate;
                $loan->status = $request->status;
                $loan->save();
                return $this->sendResponse(new LoanResource($loan), "loan created successfully");
            } else if ($statusLastLoan === 0) {
                return $this->sendResponse([], "you can't make a loan because the previous loan has not been paid off", 400);
            }
        } catch (\Throwable $th) {
            return $this->sendError("error creating loan", $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $loan = Loan::findOrFail($id);
            return $this->sendResponse(new LoanResource($loan), "loan retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error retrieving loan", $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES, self::MessageError);
            $loan = Loan::findOrFail($id);
            $loan->empId = $request->empId;
            $loan->name = $request->name;
            $loan->nominal = $request->nominal;
            $loan->loanDate = $request->loanDate;
            $loan->paymentDate = $request->paymentDate;
            $loan->status = $request->status;
            $loan->save();
            return $this->sendResponse($loan, "loan updated successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error updating loan", $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $loan = Loan::findOrFail($id);
            $loan->delete();
            return $this->sendResponse($loan, "loan deleted successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error deleting loan", $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            if ($request->filled('search')) {
                $query = Loan::join('employees', 'loans.empId', '=', 'employees.employeeId')
                    ->where('employees.firstName', 'like', '%' . $request->search . '%')
                    ->get();
                $users =   (new Collection(LoanResource::collection($query)))->paginate(self::NumPaginate);
            } else {
                $users = (new Collection(LoanResource::collection(Loan::all())))->paginate(self::NumPaginate);
            }
            return $this->sendResponse($users, "employee search successfully");
        } catch (\Throwable $th) {
            return $this->sendError("Error search employee failed", $th->getMessage());
        }
    }

    public function myLoan()
    {
        try {
            $TotalLoans = Loan::where('empId', auth()->user()->employeeId)->where('status', 0)->sum('nominal');

            $TotalPaid = 0;

            $Loans = Loan::where('empId', auth()->user()->employeeId)->where('status', 0)->get();

            foreach ($Loans as $value) {
                foreach ($value->instalments as $key => $v) {
                    $TotalPaid += $v->nominal;
                }
            }

            $Loan = new Collection([
                'totalLoan' => $TotalLoans ?? 0,
                'totalPaid' => $TotalPaid ?? 0,
                'totalUnPaid' => $TotalLoans - $TotalPaid ?? 0,
            ]);

            return $this->sendResponse($Loan, "my loan retrieved successfully");
        } catch (\Throwable $th) {
            return $this->sendError("error retrieving my loan", $th->getMessage());
        }
    }
}

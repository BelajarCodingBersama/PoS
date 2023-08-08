<?php

namespace App\Api\Controllers\Finance;

use App\Api\Requests\PayrollUpdateRequest;
use App\Api\Resources\PayrollResource;
use App\Api\Resources\PayrollResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Repositories\PayrollRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancePayrollController extends Controller
{
    private $payrollRepository;

    public function __construct(PayrollRepository $payrollRepository)
    {
        $this->payrollRepository = $payrollRepository;
    }

    public function index(Request $request)
    {
        $payrolls = $this->payrollRepository->get([
            'paginate' => $request->per_page
        ]);

        return new PayrollResourceCollection($payrolls);
    }

    public function show(Payroll $payroll)
    {
        return new PayrollResource($payroll);
    }

    public function update(PayrollUpdateRequest $request, Payroll $payroll)
    {
        $this->authorize('update', $payroll);

        try {
            DB::beginTransaction();

            $request->merge(['status' => Payroll::STATUS_PAID]);

            $data = $request->only(['payment_date', 'status']);

            $this->payrollRepository->save($payroll->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Payroll successfully updated.'
        ], 201);
    }
}

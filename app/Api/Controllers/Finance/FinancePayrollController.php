<?php

namespace App\Api\Controllers\Finance;

use App\Api\Requests\PayrollStoreRequest;
use App\Api\Requests\PayrollUpdateRequest;
use App\Api\Resources\PayrollResource;
use App\Api\Resources\PayrollResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\PayrollSetting;
use App\Models\User;
use App\Repositories\PayrollRepository;
use DateTime;
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
            'search' => [
                'name' => $request->name,
                'month' => $request->month,
                'year' => $request->year,
                'status' => $request->status
            ],
            'paginate' => $request->per_page
        ]);

        return new PayrollResourceCollection($payrolls);
    }

    public function store(PayrollStoreRequest $request)
    {
        $user = User::where('username', $request->name)->first();

        $allowances = PayrollSetting::where('name', 'allowances')->first();
        $allowancesType = $allowances->unitType->name;
        
        $tax = PayrollSetting::where('name', 'tax')->first();
        $taxType = $tax->unitType->name;

        try {
            DB::beginTransaction();

            $salary = $user->role->salary->nominal;
            $nominalAllowance = 0;
            $nominalTax = 0;
            $paymentDate = $request->payment_date;
            $paymentStatus = Payroll::STATUS_PENDING;
            
            if ($allowancesType == 'percent') {
                $nominalAllowance = ($salary * $allowances->nominal) / 100;
            } else if ($allowancesType == 'number') {
                $nominalAllowance = $allowances->nominal;
            }

            if ($taxType == 'percent') {
                $nominalTax = (($salary + $nominalAllowance) * $tax->nominal) / 100;
            } else if ($taxType == 'number') {
                $nominalTax = $tax->nominal;
            }

            if (!empty($paymentDate)) {
                $paymentStatus = $request->status;
            }

            $payroll = new Payroll();

            $data = [
                'role' => $user->role->name,
                'basic_salary' => $salary,
                'allowances' => $nominalAllowance,
                'tax' => $nominalTax,
                'net_pay' => ($salary + $nominalAllowance) - $nominalTax,
                'payment_date' => $paymentDate,
                'status' => $paymentStatus,
                'user_id' => $user->id
            ];

            $this->payrollRepository->save($payroll->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Payroll successfully created.'
        ], 201);
    }

    public function show(Payroll $payroll)
    {
        return new PayrollResource($payroll);
    }

    public function update(PayrollUpdateRequest $request, Payroll $payroll)
    {
        try {
            DB::beginTransaction();

            $paymentDate = $request->payment_date;

            if (!empty($paymentDate)) {
                $paymentStatus = $request->status;
            } 

            $request->merge([
                'status' => $paymentStatus
            ]);

            $data = $request->only([
                'payment_date', 'status'
            ]);

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
        ], 200);
    }
}

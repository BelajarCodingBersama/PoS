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
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
        $user = User::where('id', $request->user_id)->first();

        $allowances = PayrollSetting::where('name', 'allowances')->first();
        $allowancesType = $allowances->unitType->name;

        $tax = PayrollSetting::where('name', 'tax')->first();
        $taxType = $tax->unitType->name;

        try {
            DB::beginTransaction();

            $salary = $user->role->salary->nominal;
            $nominalAllowance = 0;
            $nominalTax = 0;
            $paymentStatus = $request->status;

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

            /** check status */
            if ($paymentStatus == Payroll::STATUS_PAID) {
                $paymentDate = $request->payment_date;
            } else {
                $paymentDate = null;
            }

            $month = Carbon::now('m');
            $payrollCheck = Payroll::where('user_id', $user->id)
                ->whereMonth('created_at', $month)
                ->first();

            if (!empty($payrollCheck)) {
                return response()->json([
                    'message' => 'Data already created in this month'
                ], 400);
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

            $paymentStatus = $request->status;

            /** check status */
            if ($paymentStatus == Payroll::STATUS_PAID) {
                $paymentDate = $request->payment_date;
            } else {
                $paymentDate = null;
            }

            $request->merge([
                'payment_date' => $paymentDate
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

    public function print(Request $request)
    {
        $payrolls = $this->payrollRepository->get([
            'search' => [
                'month' => $request->month,
                'year' => $request->year
            ]
        ]);

        if (!empty($request->month)) {
            $month = Carbon::create()->month($request->month)->format('F');
        } else {
            $month = null;
        }

        $path = public_path('storage/pdfs');

        // check directory
        if (File::isDirectory($path)) {
            // remove file in directory
            if (File::exists($path)) {
                File::deleteDirectory($path, 0755, true);
            }
        } else {
            File::makeDirectory($path, 0755, true);
        }

        $pdfContent = view('finance', [
            'payrolls' => $payrolls,
            'month' => $month,
            'year' => $request->year,
        ])->render();

        // Generate a unique file name
        $filename = 'generated-pdf-' . time() . '.pdf';

        // Save the PDF to the public directory
        PDF::loadHTML($pdfContent)->save(public_path('storage/pdfs/' . $filename));

        return response()->json([
            'data' => [
                'pdf_url' => asset('storage/pdfs/' . $filename)
            ]
        ]);
    }
}

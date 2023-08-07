<?php

namespace App\Api\Controllers\Admin;

use App\Api\Requests\PayrollSettingStoreRequest;
use App\Api\Requests\PayrollSettingUpdateRequest;
use App\Api\Resources\PayrollSettingResource;
use App\Api\Resources\PayrollSettingResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\PayrollSetting;
use App\Repositories\PayrollSettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminPayrollSettingController extends Controller
{
   private $payrollSettingRepository;

   public function __construct(PayrollSettingRepository $payrollSettingRepository)
   {
        $this->payrollSettingRepository = $payrollSettingRepository;
   }

   public function index(Request $request)
   {
        $payrollSettings = $this->payrollSettingRepository->get([
            'search' => [
                'name' => $request->name,
                'unit_type_id' => $request->unit_type_id
            ],
            'paginate' => $request->per_page
        ]);

        return new PayrollSettingResourceCollection($payrollSettings);
   }

    public function store(PayrollSettingStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $request->merge(['name' => Str::lower($request->name)]);

            $data = $request->only(['name', 'nominal', 'unit_type_id']);

            $payrollSetting = new PayrollSetting();
            $this->payrollSettingRepository->save($payrollSetting->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'payroll setting successfully created.'
        ], 201);
    }

    public function show(PayrollSetting $payrollSetting)
    {
        return new PayrollSettingResource($payrollSetting);
    }

    public function update(PayrollSettingUpdateRequest $request, PayrollSetting $payrollSetting)
    {
        try {
            DB::beginTransaction();

            $request->merge(['name' => Str::lower($request->name)]);

            $data = $request->only(['name', 'nominal', 'unit_type_id']);

            $this->payrollSettingRepository->save($payrollSetting->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'payroll setting successfully updated.'
        ], 201);
    }

    public function destroy(PayrollSetting $payrollSetting)
    {
        try {
            DB::beginTransaction();

            $payrollSetting->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'payroll setting successfully deleted.'
        ], 201);
    }
}

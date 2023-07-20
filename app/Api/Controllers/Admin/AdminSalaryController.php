<?php

namespace App\Api\Controllers\Admin;

use App\Api\Requests\SalaryStoreRequest;
use App\Api\Requests\SalaryUpdateRequest;
use App\Api\Resources\SalaryResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Salary;
use App\Repositories\SalaryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSalaryController extends Controller
{
    private $salaryRepository;

    public function __construct(SalaryRepository $salaryRepository)
    {
        return $this->salaryRepository = $salaryRepository;
    }

    public function index(Request $request)
    {
        $salaries = $this->salaryRepository->get([
            'paginate' => $request->per_page
        ]) ;

        return new SalaryResourceCollection($salaries);
    }

    public function store(SalaryStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->only([
                'role_id', 'amount'
            ]);

            $salary = new Salary();
            $this->salaryRepository->save($salary->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong,' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Salary successfully created.'
        ], 201);
    }

    public function update(SalaryUpdateRequest $request, Salary $salary)
    {
        try {
            DB::beginTransaction();

            $data = $request->only([
                'role_id', 'amount'
            ]);

            $this->salaryRepository->save($salary->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong,' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Salary successfully updated.'
        ], 200);
    }

    public function destroy(Salary $salary)
    {
        try {
            DB::beginTransaction();

            $salary->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong,' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Salary successfully deleted.'
        ], 200);
    }
}
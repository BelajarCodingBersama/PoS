<?php

namespace App\Api\Controllers\Admin;

use App\Api\Requests\ExpenseTypeStoreRequest;
use App\Api\Requests\ExpenseTypeUpdateRequest;
use App\Api\Resources\ExpenseTypeResource;
use App\Api\Resources\ExpenseTypeResourceCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExpenseType;
use App\Repositories\ExpenseTypeRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminExpenseTypeController extends Controller
{
    private $expenseTypeRepository;

    public function __construct(ExpenseTypeRepository $expenseTypeRepository)
    {
        $this->expenseTypeRepository = $expenseTypeRepository;
    }

    public function index(Request $request)
    {
        $expenseTypes = $this->expenseTypeRepository->get([
            'search' => [
                'name' => $request->name
            ],
            'paginate' => $request->per_page
        ]);

        return new ExpenseTypeResourceCollection($expenseTypes);
    }

    public function store(ExpenseTypeStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $request->merge([
                'slug' => Str::slug($request->name)
            ]);

            $data = $request->only(['name', 'slug']);

            $expenseType = new ExpenseType();
            $this->expenseTypeRepository->save($expenseType->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong,' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Expense Type successfully created.'
        ], 201);
    }

    public function show(ExpenseType $expenseType)
    {
        return new ExpenseTypeResource($expenseType);
    }

    public function update(ExpenseTypeUpdateRequest $request, ExpenseType $expenseType)
    {
        try {
            DB::beginTransaction();

            $request->merge([
                'slug' => Str::slug($request->name)
            ]);

            $data = $request->only(['name', 'slug']);

            $this->expenseTypeRepository->save($expenseType->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong,' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Expense Type successfully updated.'
        ], 200);
    }

    public function destroy(ExpenseType $expenseType)
    {
        try {
            DB::beginTransaction();

            // change name before delete
            $this->expenseTypeRepository->save($expenseType->fill([
                'name' => $expenseType->name . '|' . now()
            ]));

            $expenseType->delete();

            DB::commit();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Expense Type successfully deleted.'
        ], 200);
    }
}

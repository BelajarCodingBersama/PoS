<?php

namespace App\Api\Controllers\Admin;

use App\Api\Requests\UnitTypeStoreRequest;
use App\Api\Requests\UnitTypeUpdateRequest;
use App\Api\Resources\UnitTypeResource;
use App\Api\Resources\UnitTypeResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\UnitType;
use App\Repositories\UnitTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminunitTypeController extends Controller
{
   private $unitTypeRepository;

   public function __construct(UnitTypeRepository $unitTypeRepository)
   {
        $this->unitTypeRepository = $unitTypeRepository;
   }

   public function index(Request $request)
   {
        $unitTypes = $this->unitTypeRepository->get([
            'search' => [
                'name' => $request->name
            ],
            'paginate' => $request->per_page
        ]);

        return new UnitTypeResourceCollection($unitTypes);
   }

    public function store(UnitTypeStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $request->merge(['name' => Str::lower($request->name)]);

            $data = $request->only(['name']);

            $unitType = new UnitType();
            $this->unitTypeRepository->save($unitType->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'unit type successfully created.'
        ], 201);
    }

    public function show(UnitType $unitType)
    {
        return new UnitTypeResource($unitType);
    }

    public function update(UnitTypeUpdateRequest $request, UnitType $unitType)
    {
        try {
            DB::beginTransaction();

            $request->merge(['name' => Str::lower($request->name)]);

            $data = $request->only(['name']);

            $this->unitTypeRepository->save($unitType->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'unit type successfully updated.'
        ], 201);
    }

    public function destroy(UnitType $unitType)
    {
        try {
            DB::beginTransaction();

            if ($unitType->payrollSettings->count() >= 1) {
                return response()->json([
                    'message' => "Can't delete this data."
                ], 400);
            }

            $unitType->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'unit type successfully deleted.'
        ], 201);
    }
}

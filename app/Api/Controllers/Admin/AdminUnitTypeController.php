<?php

namespace App\Api\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\unitTypeRepository;
use App\Api\Requests\unitTypeStoreRequest;
use App\Api\Requests\unitTypeUpdateRequest;
use App\Api\Resources\unitTypeResourceCollection;
use App\Models\unitType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminunitTypeController extends Controller
{
   private $unitTypeRepository;

   public function __construct(unitTypeRepository $unitTypeRepository)
   {
        $this->unitTypeRepository = $unitTypeRepository;
   }

   public function index()
   {
        $unitTypes = $this->unitTypeRepository->get();

        return new unitTypeResourceCollection($unitTypes);
   }

    public function store(unitTypeStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $request->merge(['name' => Str::lower($request->name)]);

            $data = $request->only(['name']);

            $unitType = new unitType();
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

    public function update(unitTypeUpdateRequest $request, unitType $unitType)
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

    public function destroy(unitType $unitType)
    {
        try {
            DB::beginTransaction();

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

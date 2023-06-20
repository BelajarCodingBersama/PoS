<?php

namespace App\Api\Controllers\Admin;

use App\Api\Requests\ProductTypeStoreRequest;
use App\Api\Requests\ProductTypeUpdateRequest;
use App\Api\Resources\ProductTypeResource;
use App\Api\Resources\ProductTypeResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\ProductType;
use App\Repositories\ProductTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminProductTypeController extends Controller
{
    private $productTypeRepository;

    public function __construct(ProductTypeRepository $productTypeRepository)
    {
        $this->productTypeRepository = $productTypeRepository;
    }

    public function index(Request $request)
    {
        $productTypes = $this->productTypeRepository->get([
            'search' => [
                'name' => $request->name
            ],
            'paginate' => $request->per_page
        ]);

        return new ProductTypeResourceCollection($productTypes);
    }

    public function store(ProductTypeStoreRequest $request){
        try {
            DB::beginTransaction();

            $request->merge([
                'slug' => Str::slug($request->name)
            ]);

            $data = $request->only(['name', 'slug']);

            $productType = new ProductType();
            $this->productTypeRepository->save($productType->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Product type successfully created.'
        ], 201);
    }

    public function show(ProductType $productType)
    {
        return new ProductTypeResource($productType);
    }

    public function update(ProductTypeUpdateRequest $request, ProductType $productType)
    {
        try {
            DB::beginTransaction();

            $request->merge([
                'slug' => Str::slug($request->name)
            ]);

            $data = $request->only(['name', 'slug']);

            $this->productTypeRepository->save($productType->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Product type successfully updated.'
        ], 201);
    }

    public function destroy(ProductType $productType)
    {
        try {
            DB::beginTransaction();

            if ($productType->products->count() >= 1) {
                return response()->json([
                    'message' => "Can't delete this data."
                ], 400);
            }

            // change name before delete
            $this->productTypeRepository->save($productType->fill([
                'name' => $productType->name . '|' . now()
            ]));

            $productType->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Product type successfully deleted.'
        ], 201);
    }
}

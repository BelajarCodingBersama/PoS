<?php

namespace App\Api\Controllers\Admin;

use App\Api\Requests\ProductStoreRequest;
use App\Api\Requests\ProductUpdateRequest;
use App\Api\Resources\ProductResource;
use App\Api\Resources\ProductResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $products = $this->productRepository->get([
            'search' => [
                'name' => $request->name,
                'product_type_id' => $request->product_type_id
            ],
            'paginate' => $request->per_page
        ]);

        return new ProductResourceCollection($products);
    }

    public function store(ProductStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $request->merge([
                'slug' => Str::slug($request->name)
            ]);

            $data = $request->only([
                'name', 'slug', 'description', 'price', 'amount',
                'product_type_id'
            ]);

            $product = new Product();
            $this->productRepository->save($product->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Product successfully created.'
        ], 201);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $request->merge([
                'slug' => Str::slug($request->name)
            ]);

            $data = $request->only([
                'name', 'slug', 'description', 'price', 'amount',
                'product_type_id'
            ]);

            $this->productRepository->save($product->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Product successfully updated.'
        ], 201);
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // change name before delete
            $this->productRepository->save($product->fill([
                'name' => $product->name . '|' . now()
            ]));

            $product->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Product successfully deleted.'
        ], 201);
    }
}

<?php

namespace App\Api\Controllers\Finance;

use App\Api\Requests\PurchaseStoreRequest;
use App\Api\Requests\PurchaseUpdateRequest;
use App\Api\Resources\PurchaseResource;
use App\Api\Resources\PurchaseResourceCollection;
use App\Http\Controllers\Controller;
use App\Imports\PurchaseImport;
use App\Models\Product;
use App\Models\Purchase;
use App\Repositories\ProductRepository;
use App\Repositories\PurchaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class FinancePurchaseController extends Controller
{
    private $purchaseRepository, $productRepository;

    public function __construct(
        PurchaseRepository $purchaseRepository,
        ProductRepository $productRepository
    ) {
        $this->purchaseRepository = $purchaseRepository;
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $purchases = $this->purchaseRepository->get([
            'paginate' => $request->per_page
        ]);

        return new PurchaseResourceCollection($purchases);
    }

    public function store(PurchaseStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);

            $request->merge(['user_id' => auth()->id()]);

            $data = $request->only([
                'date', 'amount', 'price', 'product_id',
                'seller_id', 'user_id'
            ]);

            $purchase = new Purchase();
            $this->purchaseRepository->save($purchase->fill($data));

            $product['amount'] += $request->amount;
            $this->productRepository->save($product);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Purchase successfully created.'
        ], 201);
    }

    public function show(Purchase $purchase)
    {
        return new PurchaseResource($purchase);
    }

    public function update(PurchaseUpdateRequest $request, Purchase $purchase)
    {
        $this->authorize('update', $purchase);

        try {
            DB::beginTransaction();

            $data = $request->only([
                'date', 'amount', 'price', 'product_id',
                'seller_id'
            ]);

            // Product ID hasn't changed
            if ($purchase->product_id == $request->product_id) {
                $product = Product::findOrFail($purchase->product_id);

                // Amount has changed
                if ($purchase->amount > $request->amount) {
                    // Minus from before value
                    $product['amount'] -= $purchase->amount - $request->amount;
                } else if ($purchase->amount < $request->amount) {
                    // Plus from before value
                    $product['amount'] += $request->amount - $purchase->amount;
                }

                $this->productRepository->save($product);
            } else {
                // Product ID has changed
                // Old product amount is returned to its original state
                $oldProduct = Product::findOrFail($purchase->product_id);
                $oldProduct['amount'] -= $purchase->amount;
                $this->productRepository->save($oldProduct);

                // Amount of new product added
                $newProduct = Product::findOrFail($request->product_id);
                $newProduct['amount'] += $request->amount;
                $this->productRepository->save($newProduct);
            }

            $this->purchaseRepository->save($purchase->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Purchase successfully updated.'
        ], 200);
    }

    public function destroy(Purchase $purchase)
    {
        $this->authorize('delete', $purchase);

        try {
            DB::beginTransaction();

            // Product amount is returned to its original state
            $product = Product::findOrFail($purchase->product_id);
            $product['amount'] -= $purchase->amount;
            $this->productRepository->save($product);

            $purchase->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Purchase successfully deleted.'
        ], 200);
    }

    public function importPurchase()
    {
        Excel::import(new PurchaseImport, 'csv/purchase.csv');

        return response()->json([
            'message' => 'Import Success'
        ], 200);
    }
}

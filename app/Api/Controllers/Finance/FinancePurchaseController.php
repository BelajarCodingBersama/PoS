<?php

namespace App\Api\Controllers\Finance;

use App\Api\Requests\PurchaseStoreRequest;
use App\Api\Requests\PurchaseUpdateRequest;
use App\Api\Resources\PurchaseResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Repositories\PurchaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancePurchaseController extends Controller
{
    private $purchaseRepository;

    public function __construct(PurchaseRepository $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
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

            $request->merge(['user_id' => auth()->id()]);

            $data = $request->only([
                'date', 'amount', 'price', 'product_id',
                'seller_id', 'user_id'
            ]);

            $purchase = new Purchase();
            $this->purchaseRepository->save($purchase->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Purchase successfully added.'
        ], 201);
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
        ], 201);
    }

    public function destroy(Purchase $purchase)
    {
        $this->authorize('delete', $purchase);

        try {
            DB::beginTransaction();

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
}

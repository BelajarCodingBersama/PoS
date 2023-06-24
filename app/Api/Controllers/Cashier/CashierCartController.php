<?php

namespace App\Api\Controllers\Cashier;

use App\Api\Requests\CartStoreRequest;
use App\Api\Requests\CartUpdateRequest;
use App\Api\Resources\CartResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Repositories\CartRepository;
use Illuminate\Support\Facades\DB;

class CashierCartController extends Controller
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function index()
    {
        $carts = $this->cartRepository->get();

        return new CartResourceCollection($carts);
    }

    public function store(CartStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $request->merge(['user_id' => 1]);

            /** check product in cart */
            $productInCart = Cart::where('user_id', $request->user_id)
                                ->where('product_id', $request->product_id)
                                ->first();

            if (!empty($productInCart)) {
                $productInCart['amount'] += $request->amount;
                $cart = $productInCart;

            } else {
                $data = $request->only([
                    'product_id', 'amount', 'user_id'
                ]);

                $cart = new Cart();
                $cart->fill($data);
            }

            $this->cartRepository->save($cart);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Successfully added to cart.'
        ], 201);
    }

    public function update(CartUpdateRequest $request, Cart $cart)
    {
        try {
            DB::beginTransaction();

            $data = $request->only(['amount']);

            $this->cartRepository->save($cart->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Cart successfully updated.'
        ], 201);
    }

    public function destroy(Cart $cart)
    {
        try {
            DB::beginTransaction();

            $cart->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Cart successfully deleted.'
        ], 200);
    }
}

<?php

namespace App\Api\Controllers\Cashier;

use App\Api\Resources\TransactionResourceCollection;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Repositories\ProductRepository;
use App\Repositories\TransactionDetailRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CashierTransactionController
{
    private $transactionRepository;
    private $transactionDetailRepository;
    private $productRepository;

    public function __construct(
        TransactionRepository $transactionRepository,
        TransactionDetailRepository $transactionDetailRepository,
        ProductRepository $productRepository
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->transactionDetailRepository = $transactionDetailRepository;
        $this->productRepository = $productRepository;
    }

    public function store(Request $request)
    {  
        $user = Auth::user()->id;

        try {
            DB::beginTransaction();

            $total = 0;
            $subTotal = 0;
            /** check user who has cart */
            $carts = Cart::where('user_id', $user)->get();

            /** check, this cart cant be empty */
            if (empty($carts->count())) {
                return response()->json([
                    'message' => 'This cart is empty.'
                ], 400);
            }

            /** looping cart to get sub total and total product item */
            foreach ($carts as $cart) {
                $subTotal += $cart->product->price * $cart->amount;
            }

            $total = $subTotal;

            $request->merge([
                'user_id' => $user,
                'sub_total' => $subTotal,
                'total' => $total
            ]);

            $data = $request->only([
                'sub_total', 'total', 'user_id'
            ]);

            /** store the data transaction */
            $transaction = new Transaction();
            $this->transactionRepository->save($transaction->fill($data));
            
            foreach ($carts as $cart) {
                $data = [
                    'transaction_id' => $transaction->id,
                    'price' => $cart->product->price,
                    'amount' => $cart->amount,
                    'product_id' => $cart->product_id
                ];
         
                /** store data transcation detail */
                $transactionDetail = new TransactionDetail();
                $this->transactionDetailRepository->save($transactionDetail->fill($data));
                
                /** update product amount */
                $product = Product::find($cart->product_id);
                $this->productRepository->save($product->fill([
                    'amount' => $product->amount - $cart->amount
                ]));

                /** cart will deleted after created transaction and trasaction detail */
                $cart->delete();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong,' . $th->getMessage() 
            ], 500);
        }
        
        return response()->json([
            'message' => 'Transaction successfully created.'
        ], 200);
    }
}
<?php

namespace App\Api\Controllers\Cashier;

use App\Api\Resources\TransactionResourceCollection;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CashierTransactionController
{
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function index()
    {
        $transactions = $this->transactionRepository->get();

        return new TransactionResourceCollection($transactions);
    }

    public function store(Request $request)
    {  
        $user = Auth::user()->id;

        try {
            DB::beginTransaction();

            $total = 0;
            
            /** check user who has cart */
            $carts = Cart::where('user_id', $user)->get();

            foreach ($carts as $cart) {
                $subTotal = $cart->product->price * $cart->amount;
                $total += $subTotal;
            }

            $request->merge([
                'user_id' => $user,
                'sub_total' => $subTotal,
                'total' => $total
            ]);

            $data = $request->only([
                'sub_total', 'total', 'user_id'
            ]);

            $transaction = new Transaction();
            $this->transactionRepository->save($transaction->fill($data));

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
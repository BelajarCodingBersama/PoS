<?php

namespace App\Api\Controllers\Cashier;

use App\Api\Resources\TransactionResourceCollection;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

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
        try {
            DB::beginTransaction();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong.'
            ], 500);
        }       
    }
}
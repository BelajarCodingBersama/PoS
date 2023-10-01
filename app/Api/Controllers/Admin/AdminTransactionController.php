<?php

namespace App\Api\Controllers\Admin;

use App\Api\Resources\TransactionDetailResourceCollection;
use App\Api\Resources\TransactionResource;
use App\Api\Resources\TransactionResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Repositories\TransactionDetailRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class AdminTransactionController extends Controller
{
    private $transactionRepository;
    private $transactionDetailRepository;

    public function __construct(
        TransactionDetailRepository $transactionDetailRepository,
        TransactionRepository $transactionRepository
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->transactionDetailRepository = $transactionDetailRepository;
    }

    public function index(Request $request)
    {
        $transactions = $this->transactionRepository->get([
            'paginate' => $request->per_page
        ]);

        return new TransactionResourceCollection($transactions);
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    public function export(Transaction $transaction)
    {
       try {
        DB::beginTransaction();

        $transaction = Transaction::where('id', $transaction->id)->first();

        $pdf = PDF::loadview('transactionPDF', [
            'transaction' => $transaction,
         ]);
 
         return $pdf->download();

        DB::commit();
       } catch (\Throwable $th) {
        DB::rollBack();

        return response()->json([
            'message' => 'Data Not Found.' . $th->getMessage() 
        ], 500);
       }
    }
}

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

    public function index()
    {
        $transactions = $this->transactionRepository->get();

        return new TransactionResourceCollection($transactions);
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }
}
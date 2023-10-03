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
use Illuminate\Support\Facades\File;
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
        $path = public_path('storage/pdf-transactions');

        // Check directory
        if (File::isDirectory($path)) {
            // Remove file in directory
            if (File::exists($path)) {
                File::deleteDirectory($path, 0755, true);
            }
        } else {
            File::makeDirectory($path, 0755, true);
        }

        $pdfContent = view('transactionPDF', [
            'transaction' => $transaction
        ])->render();

        // Generate a unique file name
        $filename = 'generated-pdf-transaction-' . time() . '.pdf';

        // Save the PDF to the public directory
        PDF::loadHTML($pdfContent)->save(public_path('storage/pdf-transactions/' . $filename));

        return response()->json([
            'data' => [
                'pdf_url' => asset('storage/pdf-transactions/' . $filename)
            ]
        ]);
    }
}

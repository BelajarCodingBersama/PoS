<?php

namespace App\Api\Controllers\Finance;

use App\Api\Resources\DashboardFinanceResource;
use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Purchase;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Repositories\PayrollRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\TransactionDetailRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class FinanceDashboardController extends Controller
{
    private $purchaseRepository, $payrollRepository, $transactionRepository,
            $transactionDetailRepository;

    public function __construct(
        PurchaseRepository $purchaseRepository,
        PayrollRepository $payrollRepository,
        TransactionRepository $transactionRepository,
        TransactionDetailRepository $transactionDetailRepository
    ) {
        $this->purchaseRepository = $purchaseRepository;
        $this->payrollRepository = $payrollRepository;
        $this->transactionRepository = $transactionRepository;
        $this->transactionDetailRepository = $transactionDetailRepository;
    }

    public function index(Request $request)
    {
        /** Graph Purchase */
        $purchases = $this->purchaseRepository->get([
            'select' => 'YEAR(date) as year, MONTH(date) as month, SUM(price) as total, COUNT(*) as item',
            'search' => [
                'year' => $request->year
            ],
            'group' => 'year, month',
            'order' => 'year ASC, month ASC'
        ]);

        /** Graph Payroll */
        $payrolls = $this->payrollRepository->get([
            'select' => 'YEAR(created_at) as year, MONTH(created_at) as month, SUM(net_pay) as total, COUNT(*) as item',
            'search' => [
                'year' => $request->year
            ],
            'group' => 'year, month',
            'order' => 'year ASC, month ASC'
        ]);

        /** Graph Transaction */
        $transactions = $this->transactionRepository->get([
            'select' => 'YEAR(created_at) as year, MONTH(created_at) as month, SUM(total) as total, COUNT(*) as item',
            'search' => [
                'year' => $request->year
            ],
            'group' => 'year, month',
            'order' => 'year ASC, month ASC',
        ]);

        /** Total Purchase, Payroll, And Transaction */
        $totalPurchase = $this->purchaseRepository->get([
            'search' => [
                'year' => $request->year
            ],
            'sum' => 'price'
        ]);

        $totalPayroll = $this->payrollRepository->get([
            'search' => [
                'year' => $request->year
            ],
            'sum' => 'net_pay'
        ]);

        $totalTransaction = $this->transactionRepository->get([
            'search' => [
                'year' => $request->year
            ],
            'sum' => 'total'
        ]);

        /** Popular Product */
        $popular  = TransactionDetail::join('products', 'product_id', '=', 'products.id')
                        ->selectRaw('products.name as product_name, SUM(transaction_details.amount) as total')
                        ->when(!empty($request->year), function ($query) use ($request) {
                            return $query->whereYear('transaction_details.created_at', $request->year);
                        })
                        ->groupByRaw('product_name')
                        ->orderByRaw('total DESC')
                        ->get();

        $response = [
            'data' => [
                'graph' => [
                    'purchase' => $purchases,
                    'payroll' => $payrolls,
                    'transaction' => $transactions
                ],
                'total' => [
                    'purchase' => $totalPurchase,
                    'payroll' => $totalPayroll,
                    'transaction' => $totalTransaction
                ],
                'popular_products' => $popular
            ]
        ];

        return response()->json($response);
    }
}

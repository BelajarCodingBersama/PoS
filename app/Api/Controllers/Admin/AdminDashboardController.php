<?php

namespace App\Api\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayrollSetting;
use App\Models\Product;
use App\Repositories\PayrollRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    private $productRepository, $purchaseRepository, $payrollRepository,
            $transactionRepository;

    public function __construct(
        ProductRepository $productRepository,
        PurchaseRepository $purchaseRepository,
        PayrollRepository $payrollRepository,
        TransactionRepository $transactionRepository
    ) {
        $this->productRepository = $productRepository;
        $this->purchaseRepository = $purchaseRepository;
        $this->payrollRepository = $payrollRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function index(Request $request)
    {
        /** Product stocks */
        $productStocks = $this->productRepository->get([
            'select' => 'name, amount',
            'order' => 'amount ASC'
        ]);

        /** Payroll setting (Allowances & Tax) */
        $allowances = PayrollSetting::where('name', 'allowances')->first();
        $tax = PayrollSetting::where('name', 'tax')->first();

        /** List of products and total sales for each product */
        $productSales = Product::leftjoin('transaction_details', 'products.id', '=', 'transaction_details.product_id')
                            ->selectRaw('name as product_name, SUM(transaction_details.amount) as total')
                            ->when(!empty($request->year), function ($query) use ($request) {
                                return $query->whereYear('transaction_details.created_at', $request->year);
                            })
                            ->groupByRaw('product_name')
                            ->orderByRaw('total DESC')
                            ->get();

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

        return response()->json([
            'data' => [
                'product_stocks' => $productStocks,
                'payroll_setting' => [
                    [
                        'name' => $allowances->name,
                        'nominal' => $allowances->nominal,
                        'unit_type' => $allowances->unitType->name
                    ],
                    [
                        'name' => $tax->name,
                        'nominal' => $tax->nominal,
                        'unit_type' => $tax->unitType->name
                    ]
                ],
                'product_sales' => $productSales,
                'total' => [
                    'purchase' => $totalPurchase,
                    'payroll' => $totalPayroll,
                    'transaction' => $totalTransaction
                ],
            ]
        ]);
    }
}

<?php

namespace App\Api\Controllers\Finance;

use App\Api\Resources\DashboardFinanceResource;
use App\Http\Controllers\Controller;
use App\Repositories\PayrollRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class FinanceDashboardController extends Controller
{
    private $purchaseRepository, $payrollRepository, $transactionRepository;

    public function __construct(
        PurchaseRepository $purchaseRepository,
        PayrollRepository $payrollRepository,
        TransactionRepository $transactionRepository
    ) {
        $this->purchaseRepository = $purchaseRepository;
        $this->payrollRepository = $payrollRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function index(Request $request)
    {
        /** Purchase */
        $purchases = $this->purchaseRepository->get();
        $totalPricePurchases = 0;

        foreach ($purchases as $purchase) {
            $totalPricePurchases += $purchase->price;
        }

        /** Payroll */
        $payrolls = $this->payrollRepository->get();
        $totalPricePayrolls = 0;

        foreach ($payrolls as $payroll) {
            $totalPricePayrolls += $payroll->net_pay;
        }

        /** Total Expense */
        $totalExpenses = $totalPricePurchases + $totalPricePayrolls;

        /** Transaction & Total Income */
        $transactions = $this->transactionRepository->get();
        $totalIncomes = 0;

        foreach ($transactions as $trx) {
            $totalIncomes += $trx->total;
        }

        $data = [
            'purchases' => [
                'items' => $purchases,
                'total' => $totalPricePurchases
            ],
            'payrolls' => [
                'items' => $payrolls,
                'total' => $totalPricePayrolls
            ],
            'transactions' => [
                'items' => $transactions,
                'total' => $totalIncomes
            ],
            'total_expenses' => $totalExpenses,
            'total_incomes' => $totalIncomes
        ];

        return response()->json([
            'data' => $data
        ]);
    }
}

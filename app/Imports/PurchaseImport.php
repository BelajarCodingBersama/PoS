<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PurchaseImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {   
        $user = Auth()->user()->id;
        $date = Carbon::parse($row['date'])->format('Y-m-d');
        $purchaseCheck = Purchase::where('product_id', $row['product_id'])->first();

        if (empty($purchaseCheck)) {
            return new Purchase([
                'date' => $date,
                'amount' => $row['amount'],
                'price' => $row['price'],
                'product_id' => $row['product_id'],
                'seller_id' => $row['seller_id'],
                'user_id' => $user
            ]);
        }
    }
}

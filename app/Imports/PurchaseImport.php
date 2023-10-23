<?php

namespace App\Imports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PurchaseImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        return new Purchase([
            'date' => $row['date'],
            'amount' => $row['amount'],
            'price' => $row['price'],
            'product_id' => $row['product_id'],
            'seller_id' => $row['seller_id'],
            'user_id' => auth()->id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'amount' => ['required', 'integer', 'gte:1'],
            'price' => ['required', 'integer', 'gte:1'],
            'product_id' => ['required', 'exists:products,id'],
            'seller_id' => ['required', 'exists:sellers,id'],
        ];
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}

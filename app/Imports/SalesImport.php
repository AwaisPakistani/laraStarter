<?php

namespace App\Imports;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalesImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts
{
    public function model(array $row): Model
    {
        return new Sale([
            'country'       => $row['country'] ?? null,
            'item_type'     => $row['item_type'] ?? null,
            'sales_channel' => $row['sales_channel'] ?? null,
            'order_id'      => $row['order_id'] ?? null,
            'unit_price'    => $row['unit_price'] ?? null,
            'total_profit'  => $row['total_profit'] ?? null,
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}

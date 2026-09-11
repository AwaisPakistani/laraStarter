<?php
namespace App\Exports;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SalesExport implements FromQuery, WithHeadings, WithChunkReading
{
    public function query(): Builder
    {
       $filters = [
            'country',
            'item_type',
            AllowedFilter::exact('sales_channel'),
            AllowedFilter::exact('order_id'),
        ];

        return QueryBuilder::for(Sale::class)
            ->allowedFilters(...$filters) // Notice the ... before $filters
            ->getEloquentBuilder();
         
    }

    public function headings(): array
    {
        return [
            'Country',
            'Item Type',
            'Sales Channel',
            'Order ID',
            'Unit Price',
            'Total Profit',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
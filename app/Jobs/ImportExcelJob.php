<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SalesImport;

class ImportExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Allow the job to run for 30 minutes (1800 seconds)
    public $timeout = 1800;

    // Limit retries so it doesn't loop endlessly if it fails
    public $tries = 1;

    public function __construct(
        public string $filePath
    ) {
        $this->onQueue('imports');
    }

    public function handle(): void
    {
        Excel::import(
            new SalesImport,
            $this->filePath
        );
    }
}
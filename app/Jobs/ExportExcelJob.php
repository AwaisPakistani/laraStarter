<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Events\ExportCompleted;
use App\Exports\SalesExport;
use App\Models\User;

class ExportExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1800; // 30 minutes

    public int $tries = 1;


    public function __construct(
        public string $fileName,
        public User $user
    ) {
        $this->onQueue('exports');
    }

    public function handle(): void
    {
        ini_set('memory_limit', '1024M');
        try {
            Storage::disk('public')->makeDirectory('exports');
            Excel::store(
            new SalesExport,
            $this->fileName,
            'public');
            ////////
            // Generate full absolute URL for download route
            $fileNameOnly = basename($this->fileName);
            $downloadUrl = route('sales.download', ['filename' => $fileNameOnly]);

            // Dispatch Event to trigger Listener & Email Notification
            ExportCompleted::dispatch($this->user, $this->fileName, $downloadUrl);

            Log::info("Export created and completed event dispatched for user ID: {$this->user->id}");
            ////////
            // $downloadurl= asset('storage/' .  $this->fileName);
            // Log::info("Export created successfully: {$this->fileName}");
        } catch (Throwable $e) {
            Log::error('EXPORT JOB FAILED: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; // Re-throw to mark job as failed
        }
    }
}

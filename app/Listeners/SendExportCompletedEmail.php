<?php 
namespace App\Listeners;

use App\Events\ExportCompleted;
use App\Notifications\ExportReadyNotification;

class SendExportCompletedEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ExportCompleted $event): void
    {
        // Pass $filePath (or extract the file name if needed) and downloadUrl
        $fileName = basename($event->filePath);

        $event->user->notify(new ExportReadyNotification(
            fileName: $fileName,
            downloadUrl: $event->downloadUrl
        ));
    }
}
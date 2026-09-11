<?php

namespace App\Listeners;

use App\Events\ExportCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
        $event->user->notify(new ExportReadyNotification(
            $event->fileName,
            $event->downloadUrl
        ));
    }
}

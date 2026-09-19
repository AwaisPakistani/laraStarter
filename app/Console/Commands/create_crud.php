<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:create_crud')]
#[Description('Command description')]
class create_crud extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Command executed successfully!');
    }
}

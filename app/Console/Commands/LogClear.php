<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class LogClear extends Command
{
    protected $signature = 'log:clear';
    protected $description = 'Clear the log files';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        File::put(storage_path('logs/laravel.log'), '');  // Clears the log file
        $this->info('Logs have been cleared!');
    }
}

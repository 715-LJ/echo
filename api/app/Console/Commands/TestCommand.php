<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     * php artisan app:test-command
     * @var string
     */
    protected $signature = 'app:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo "TestCommand\n";
    }
}

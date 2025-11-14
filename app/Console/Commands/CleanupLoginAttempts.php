<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoginAttempt;

class CleanupLoginAttempts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'login:cleanup {--days=30 : Number of days to keep login attempts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old login attempts from the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Cleaning up login attempts older than {$days} days...");
        
        $deletedCount = LoginAttempt::cleanup($days);
        
        $this->info("Successfully deleted {$deletedCount} old login attempts.");
        
        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\GuestSessionService;

class CleanupGuestSessions extends Command
{
    protected $signature = 'guest-sessions:cleanup';
    protected $description = 'Clean up expired guest sessions';

    public function handle(GuestSessionService $service)
    {
        $this->info('Cleaning up expired guest sessions...');
        
        $count = $service->cleanupExpiredSessions();
        
        $this->info("Cleaned up {$count} expired sessions.");
        
        return 0;
    }
}

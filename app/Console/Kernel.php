<?php
namespace App\Console;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
    /**
     * Define the application's command schedule.
     */
    protected function schedule( Schedule $schedule ): void {
        $schedule->call( function() {
            Log::info( "ccccccccc" );
//            exec( 'node crawler.js --url https://daihoc.fpt.edu.vn --n 20' );
        } )
            ->cron("*/2 * * * *");
    }
    /**
     * Register the commands for the application.
     */
    protected function commands(): void {
        $this->load( __DIR__ . '/Commands' );
        require base_path( 'routes/console.php' );
    }
}

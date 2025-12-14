<?php

namespace App\Console\Commands;

use App\Services\RouteGenerationService;
use Illuminate\Console\Command;

class GenerateDailyRoutes extends Command
{
    protected $signature = 'routes:generate {--date= :  Specific date (Y-m-d format)}';
    protected $description = 'Generate routes for the specified date or tomorrow';

    public function handle(RouteGenerationService $routeService): int
    {
        $date = $this->option('date') 
            ? \Carbon\Carbon::parse($this->option('date'))
            : \Carbon\Carbon::tomorrow();

        $this->info("Generating routes for {$date->format('Y-m-d')}...");

        $result = $routeService->generateRoutesForDate($date);

        if (isset($result['error'])) {
            $this->error($result['error']);
            return Command:: FAILURE;
        }

        $this->info("Routes created: {$result['routes_created']}");
        $this->info("Collections assigned: {$result['collections_assigned']}");

        return Command::SUCCESS;
    }
}
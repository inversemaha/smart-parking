<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domains\Admin\Services\DashboardService;

class GenerateReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'parking:generate-reports {--type=daily} {--date=}';

    /**
     * The console command description.
     */
    protected $description = 'Generate parking system reports';

    /**
     * Execute the console command.
     */
    public function handle(DashboardService $dashboardService)
    {
        $type = $this->option('type');
        $date = $this->option('date') ? \Carbon\Carbon::parse($this->option('date')) : now();

        $this->info("Generating {$type} reports for {$date->toDateString()}...");

        try {
            switch ($type) {
                case 'daily':
                    $result = $dashboardService->generateDailyReports($date);
                    break;
                case 'weekly':
                    $result = $dashboardService->generateWeeklyReports($date);
                    break;
                case 'monthly':
                    $result = $dashboardService->generateMonthlyReports($date);
                    break;
                default:
                    $this->error('Invalid report type. Use: daily, weekly, or monthly');
                    return 1;
            }

            $this->info('Report generation completed successfully!');
            $this->table(['Metric', 'Value'], [
                ['Total Revenue', 'BDT ' . number_format($result['revenue'] ?? 0, 2)],
                ['Total Bookings', $result['bookings'] ?? 0],
                ['Unique Users', $result['users'] ?? 0],
                ['Average Duration', ($result['avg_duration'] ?? 0) . ' hours'],
                ['Occupancy Rate', ($result['occupancy_rate'] ?? 0) . '%'],
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error('Report generation failed: ' . $e->getMessage());
            return 1;
        }
    }
}

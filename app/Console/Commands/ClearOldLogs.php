<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ClearOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear 
                            {--days=30 : Number of days to keep logs}
                            {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear old log files and free up storage space';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $force = $this->option('force');

        $this->info("Clearing logs older than {$days} days...");
        
        $logPath = storage_path('logs');
        
        if (!File::exists($logPath)) {
            $this->error('Log directory does not exist!');
            return 1;
        }

        $files = File::files($logPath);
        $deletedCount = 0;
        $freedSpace = 0;
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Scanning " . count($files) . " log files...");

        foreach ($files as $file) {
            $fileDate = Carbon::createFromTimestamp(File::lastModified($file));
            
            if ($fileDate->lt($cutoffDate)) {
                $fileSize = File::size($file);
                $fileName = $file->getFilename();
                
                if ($force || $this->confirm("Delete {$fileName} ({$this->formatBytes($fileSize)})?", true)) {
                    File::delete($file);
                    $deletedCount++;
                    $freedSpace += $fileSize;
                    $this->line("✓ Deleted: {$fileName}");
                }
            }
        }

        $this->newLine();
        $this->info("✓ Deleted {$deletedCount} log file(s)");
        $this->info("✓ Freed {$this->formatBytes($freedSpace)} of storage space");

        // Also clear Telescope data
        if ($this->confirm('Do you also want to clear old Telescope data?', true)) {
            $this->call('telescope:clear');
        }

        // Also clear Activity Log data
        if ($this->confirm('Do you also want to clear old Activity Log data?', true)) {
            $this->call('activitylog:clean');
        }

        return 0;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

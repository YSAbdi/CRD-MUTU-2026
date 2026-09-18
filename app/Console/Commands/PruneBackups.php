<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneBackups extends Command
{
    protected $signature = 'crm:prune-backups {--disk=backups}';
    protected $description = 'Delete database backups older than the configured retention period';

    public function handle(): int
    {
        $disk = Storage::disk($this->option('disk'));
        $cutoff = now()->subDays((int) config('backup.retention_days', 30))->timestamp;
        $deleted = 0;
        foreach ($disk->allFiles('crm-mutu') as $file) {
            if ($disk->lastModified($file) < $cutoff) { $disk->delete($file); $deleted++; }
        }
        $this->info("Deleted {$deleted} expired backup(s).");
        return self::SUCCESS;
    }
}

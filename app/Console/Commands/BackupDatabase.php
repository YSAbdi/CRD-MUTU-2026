<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    protected $signature = 'crm:backup {--disk=backups}';
    protected $description = 'Create a compressed MySQL dump and upload it to the configured filesystem disk';

    public function handle(): int
    {
        $filename = 'crm-mutu/'.now()->format('Y/m/d/His').'-database.sql.gz';
        $database = (string) config('database.connections.mysql.database');
        $user = (string) config('database.connections.mysql.username');
        $password = (string) config('database.connections.mysql.password');
        $host = (string) config('database.connections.mysql.host', '127.0.0.1');
        $port = (string) config('database.connections.mysql.port', '3306');
        $dump = base_path('storage/app/'.basename($filename, '.gz'));
        $gz = $dump.'.gz';
        if (! is_dir(dirname($dump))) mkdir(dirname($dump), 0750, true);
        $command = ['mysqldump', '--single-transaction', '--quick', '--routines', '--host='.$host, '--port='.$port, '--user='.$user, $database];
        $process = new Process($command, base_path(), ['MYSQL_PWD' => $password]);
        $process->run();
        if (! $process->isSuccessful()) { $this->error($process->getErrorOutput()); return self::FAILURE; }
        file_put_contents($dump, $process->getOutput());
        $input = fopen($dump, 'rb'); $output = gzopen($gz, 'wb9'); while (! feof($input)) gzwrite($output, fread($input, 1024 * 1024)); fclose($input); gzclose($output); unlink($dump);
        $disk = $this->option('disk'); Storage::disk($disk)->put($filename, fopen($gz, 'rb')); unlink($gz);
        $this->info("Backup uploaded: {$filename}"); return self::SUCCESS;
    }
}

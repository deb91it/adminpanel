<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Storage;
use DB;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will take a back up of current database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        date_default_timezone_set('Asia/Dhaka');
        $date = date("Y-m-d_h-i");
        $host = env('DB_HOST');
        $port = env('DB_PORT');
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        $command = "mysqldump --user=" . $user ." --password=" . $password . " --host=" . $host . " " . $database . "  > " . $date . ".sql";
        //echo $command;exit();
//        $command = "mysqldump -P {$port} -h {$host} --user={$user} -p {$password} {$database} > {$date}.sql";
        $process = new Process($command);
        $process->start();
        while ($process->isRunning()) {
            $local = Storage::disk('local')
                ->put('database-backup/' . $date. ".sql", file_get_contents("{$date}.sql"));
        }
        unlink("{$date}.sql");

    }
}

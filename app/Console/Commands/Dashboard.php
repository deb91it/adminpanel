<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Repositories\Agent\Dashboard\DashboardRepository;

class Dashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will make dashboard report in each specific time interval !';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $dashboard;

    public function __construct(DashboardRepository $dashboard)
    {
        parent::__construct();
        $this->dashboard = $dashboard;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dashboard->scheduleCalculate();
        $this->info('Dashboard report generation done successfully !');
    }
}

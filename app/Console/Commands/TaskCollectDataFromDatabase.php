<?php

namespace App\Console\Commands;

use App\Http\Controllers\VCenter\HydrateDatabaseController;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class TaskCollectDataFromDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hydrate:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect data from database vcenter';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info(PHP_EOL . 'Creating file if not exist databases.json in storage/json' . PHP_EOL);
        /* generate json file if not exist */
        $diskFile = HydrateDatabaseController::createFileJsonDatabasesAndGet();
        if ($diskFile[0]->host === 'ipexample') {
            $this->info('Please write data in database vcenter json file and run again' . PHP_EOL);
            return -1;
        }
        /* Get date now */
        $date = new DateTime();
        /* Print into log file start task*/
        $this->info('<-----------------------------Start task collect data from database vcenter----------------------------->' . PHP_EOL);
        $this->info('Collect data from database vcenter ' . $date->format('d-M-Y H:m:s') . PHP_EOL);
        /* End */
        try {
            HydrateDatabaseController::hydrateDatabase($diskFile);
        } catch (QueryException $th) {
            $this->error('Ocurred error ' . $th);
            return -1;
        }
        /* Print into log file end task*/
        $this->info(PHP_EOL . '<-----------------------------End task collect data from database vcenter----------------------------->' . PHP_EOL);
        return 0;
    }
}

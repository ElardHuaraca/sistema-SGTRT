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
        /* Get date now */
        $date = new DateTime();
        /* Print into log file start task*/
        $this->info('Collect data from database vcenter ' . $date->format('d-M-Y H:m:s'));
        /* End */

        try {
            $results = new HydrateDatabaseController();
            $results = $results->hydrateDatabase();
            foreach ($results as $result) {
                $this->info($result->datacenter_id . ' ' . $result->id . ' ' .
                    $result->name . ' ' . $result->guest_family . ' ' . $result->memory_ram_2 . ' ' . $result->cpu_cores_2 .
                    ' ' . $result->aggr_unshared_storage_space);
            }
        } catch (QueryException $th) {
            $this->error('Ocurred error ' . $th);
            return -1;
        }
        return 0;
    }
}

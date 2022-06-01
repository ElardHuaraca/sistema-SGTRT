<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResourceConsumption;
use App\Models\Grafic;
use App\Models\Server;
use ArrayObject;

class ReportController extends Controller
{
    public function resource_consumption()
    {
        $servers = Server::selectRaw(
            'servers.idserver, servers.name,
            servers.active, servers.idproject,
            projects.name AS project_name, jsonb_object_agg(resources_history.name,resources_history.amount) as resources,
            sows.name AS sow_name'
        )
            ->join('projects', function ($join) {
                $join->on('projects.idproject', '=', 'servers.idproject');
            })
            ->join('resources_history', function ($join) {
                $join->on('resources_history.idserver', '=', 'servers.idserver');
                $join->orderBy('resources_history.date', 'desc');
                $join->limit(4);
            })
            ->join('sows', function ($join) {
                $join->on('sows.idsow', '=', 'servers.idsow');
            })
            ->groupBy(['servers.idserver', 'servers.name', 'servers.active', 'servers.idproject', 'project_name', 'sow_name'])->get();
        return view('reports.IT-resources-consumption', ['servers' => $servers]);
    }

    public function resource_consumption_grafic($id)
    {
        $grafic = Grafic::all();
        return view('reports.grafics', ['grafic' => $grafic, 'name' => $id]);
    }
}

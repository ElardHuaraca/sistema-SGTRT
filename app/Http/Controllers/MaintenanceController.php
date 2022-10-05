<?php

namespace App\Http\Controllers;

use App\Imports\CostsImport;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Fourwall;
use App\Models\Nexus;
use App\Models\Hp;
use App\Models\ResourceHistory;
use App\Models\Server;
use App\Models\Sow;
use App\Models\SplaLicense;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class MaintenanceController extends Controller
{
    public function sow()
    {
        $sows = Sow::orderBy('created_at', 'desc')->get();
        return view('maintenance.sow', ['sows' => $sows]);
    }

    public function store_sow(Request $request)
    {
        /* Declare varibale  for save sows saved on database */
        $result = array();
        for ($i = 0; $i < 3; $i++) {
            $sow_request = 0;
            switch ($i) {
                case 0:
                    $sow_request = $request->bronce;
                    break;
                case 1:
                    $sow_request = $request->silver;
                    break;
                default:
                    $sow_request = $request->gold;
                    break;
            }
            $sow = new Sow();
            $sow->version = $sow_request['version'];
            $sow->name = $sow_request['name'];
            $sow->type = $sow_request['type'];
            $sow->cost_cpu = $sow_request['cost_cpu'];
            $sow->cost_ram = $sow_request['cost_ram'];
            $sow->cost_hdd_mechanical = $sow_request['cost_hdd_mechanical'];
            $sow->cost_hdd_solid = $sow_request['cost_hdd_solid'];
            $sow->cost_mo_clo_sw_ge = $sow_request['cost_mo_clo_sw_ge'];
            $sow->cost_mo_cot = $sow_request['cost_mo_cot'];
            $sow->cost_cot_monitoring = $sow_request['cost_cot_monitoring'];
            $sow->cost_license_vssp = $sow_request['cost_license_vssp'];
            $sow->cost_license_vssp_srm = $sow_request['cost_license_vssp_srm'];
            $sow->cost_link = $sow_request['cost_link'];
            $sow->add_cost_antivirus = $sow_request['add_cost_antivirus'];
            $sow->add_cost_win_license_cpu = $sow_request['add_cost_win_license_cpu'];
            $sow->add_cost_win_license_ram = $sow_request['add_cost_win_license_ram'];
            $sow->add_cost_linux_license = $sow_request['add_cost_linux_license'];
            $sow->cost_backup_db = $sow_request['cost_backup_db'];
            $sow->save();
            $result[strtolower($sow->type)] = $sow;
        }

        $sows = Sow::orderBy('is_deleted', 'desc')->get();

        return response()->json($sows, 200);
    }

    public function update_sow($id, Request $request)
    {
        $sow = Sow::find($id);
        if ($sow->name !== $request->name) {
            Sow::where('name', $sow->name)->update(['name' => $request->name]);
        }
        if ($sow->version !== $request->version) {
            Sow::where('version', $sow->version)->update(['version' => $request->version]);
        }
        $sow->version = $request->version;
        $sow->name = $request->name;
        $sow->type = $request->type;
        $sow->cost_cpu = $request->cost_cpu;
        $sow->cost_ram = $request->cost_ram;
        $sow->cost_hdd_mechanical = $request->cost_hdd_mechanical;
        $sow->cost_hdd_solid = $request->cost_hdd_solid;
        $sow->cost_mo_clo_sw_ge = $request->cost_mo_clo_sw_ge;
        $sow->cost_mo_cot = $request->cost_mo_cot;
        $sow->cost_cot_monitoring = $request->cost_cot_monitoring;
        $sow->cost_license_vssp = $request->cost_license_vssp;
        $sow->cost_license_vssp_srm = $request->cost_license_vssp_srm;
        $sow->cost_link = $request->cost_link;
        $sow->add_cost_antivirus = $request->add_cost_antivirus;
        $sow->add_cost_win_license_cpu = $request->add_cost_win_license_cpu;
        $sow->add_cost_win_license_ram = $request->add_cost_win_license_ram;
        $sow->add_cost_linux_license = $request->add_cost_linux_license;
        $sow->cost_backup_db = $request->cost_backup_db;
        $sow->save();

        $sows = Sow::orderBy('is_deleted', 'desc')->get();

        return response()->json($sows, 200);
    }

    public function update_sow_status($id, Request $request)
    {
        $sow = Sow::find($id);
        Sow::where('name', $sow->name)->where('version', $sow->version)->update(['is_deleted' => $request->is_deleted]);

        $sows = Sow::orderBy('is_deleted', 'desc')->get();

        return response()->json($sows, 200);
    }

    public function project()
    {
        $projects = Project::all();
        return view('maintenance.projects', ['projects' => $projects]);
    }

    public function store_project(Request $request)
    {
        $project = new Project();
        $project->idproject = $request->idproject;
        $project->name = $request->name;
        $project->save();

        return response()->json($project, 200);
    }

    public function update_project($id, Request $request)
    {
        $project = Project::find($id);
        $project->idproject = $request->idproject;
        $project->name = $request->name;
        $project->save();

        return response()->json($project, 200);
    }

    public function update_project_status($id, Request $request)
    {
        $project = Project::find($id);
        $project->is_deleted = $request->is_deleted;
        $project->save();

        return response()->json($project, 200);
    }

    /* SELECT p.idproyecto, p.nombre,
			(SELECT SUM(f.costo) FROM fourwalls AS f WHERE p.idproyecto = f.idproyecto AND f.eliminado = false) AS costoFourwalls,
			(SELECT SUM(n.costo) FROM nexus AS n WHERE p.idproyecto = n.idproyecto AND n.eliminado = false) AS costoNexus,
			(SELECT SUM(h.costo) FROM hp AS h WHERE p.idproyecto = h.idproyecto AND h.eliminado = false) AS costoHp
	    FROM proyecto AS p; transform to eloquent laravel  */
    public function maintenance_cost()
    {
        [$date_start, $date_end] = ReportController::getDatesCalculed();

        /* [$date_start, $date_end] = [Carbon::createFromFormat('d/m/Y', $date_start)->format('01/m/Y'), Carbon::createFromFormat('d/m/Y', $date_start)->format('t/m/Y')]; */

        $projects = Project::selectRaw('
            projects.idproject,
            projects.name,
            SUM(fourwalls.cost) AS costFourwalls,
            SUM(nexus.cost) AS costNexus,
            SUM(hps.cost) AS costHp
        ')->leftJoin('fourwalls', function ($join) use ($date_start, $date_end) {
            $join->on('projects.idproject', '=', 'fourwalls.idproject');
            $join->whereBetween('fourwalls.created_at', [$date_start, $date_end]);
            $join->where('fourwalls.is_deleted', '=', false);
        })->leftJoin('nexus', function ($join) use ($date_start, $date_end) {
            $join->on('projects.idproject', '=', 'nexus.idproject');
            $join->whereBetween('nexus.created_at', [$date_start, $date_end]);
            $join->where('nexus.is_deleted', '=', false);
        })->leftJoin('hps', function ($join) use ($date_start, $date_end) {
            $join->on('projects.idproject', '=', 'hps.idproject');
            $join->whereBetween('hps.created_at', [$date_start, $date_end]);
            $join->where('hps.is_deleted', '=', false);
        })->groupBy('projects.idproject', 'projects.name')->get();

        return view('maintenance.costs', ['projects' => $projects, 'date_start' => $date_start]);
    }

    public function import_CSV(Request $request)
    {


        $import = new CostsImport();

        Excel::import($import, $request->file('file'));

        $import->processData();

        return response()->json(200);
    }

    public function maintenance_cost_by_month($date)
    {
        if ($date === 'na') {
            [$date_start, $date_end] = ReportController::getDatesCalculed();
            [$date_start, $date_end] = [date('01/m/Y', strtotime($date_start)), date('t/m/Y', strtotime($date_start))];
        } else {
            $date_start = date('01/m/Y', strtotime($date));
            $date_end = date('t/m/Y ', strtotime($date));
        }

        $projects = Project::selectRaw('
            projects.idproject,
            projects.name,
            SUM(fourwalls.cost) AS costFourwalls,
            SUM(nexus.cost) AS costNexus,
            SUM(hps.cost) AS costHp
        ')->leftJoin('fourwalls', function ($join) use ($date_start, $date_end) {
            $join->on('projects.idproject', '=', 'fourwalls.idproject');
            $join->whereBetween('fourwalls.created_at', [$date_start, $date_end]);
            $join->where('fourwalls.is_deleted', '=', false);
        })->leftJoin('nexus', function ($join) use ($date_start, $date_end) {
            $join->on('projects.idproject', '=', 'nexus.idproject');
            $join->whereBetween('nexus.created_at', [$date_start, $date_end]);
            $join->where('nexus.is_deleted', '=', false);
        })->leftJoin('hps', function ($join) use ($date_start, $date_end) {
            $join->on('projects.idproject', '=', 'hps.idproject');
            $join->whereBetween('hps.created_at', [$date_start, $date_end]);
            $join->where('hps.is_deleted', '=', false);
        })->groupBy('projects.idproject', 'projects.name')->get();

        return response()->json($projects);
    }

    public function store_fourwall(Request $request)
    {
        $fourwall = new Fourwall();
        $fourwall->idproject = $request->idproject;
        $fourwall->equipment = $request->equipment;
        $fourwall->serie = $request->serie;
        $fourwall->cost = $request->cost;
        $fourwall->date_start = $request->date_start;
        if ($request->date_end != null) $fourwall->date_end = $request->date_end;
        $fourwall->save();
        return response()->json($fourwall, 200);
    }

    public function store_nexus(Request $request)
    {
        $nexus = new Nexus();
        $nexus->idproject = $request->idproject;
        $nexus->network_point = $request->network_point;
        $nexus->cost = $request->cost;
        $nexus->serie = $request->serie;
        $nexus->date_start = $request->date_start;
        if ($request->date_end != null) $nexus->date_end = $request->date_end;
        $nexus->save();
        return response()->json($nexus, 200);
    }

    public function store_hp(Request $request)
    {
        $hp = new Hp();
        $hp->idproject = $request->idproject;
        $hp->equipment = $request->equipment;
        $hp->serie = $request->serie;
        $hp->cost = $request->cost;
        $hp->date_start = $request->date_start;
        if ($request->date_end != null) $hp->fec_fin = $request->date_end;
        $hp->save();

        return response()->json($hp, 200);
    }

    public function licence_spla()
    {
        $licences = SplaLicense::all();
        return view('maintenance.licence-spla', ['licences' => $licences]);
    }

    public function store_licence_spla(Request $request)
    {
        $licence = new SplaLicense();
        $licence->code = $request->code;
        $licence->name = $request->name;
        $licence->cost = $request->cost;
        $licence->type = $request->type;
        $licence->save();

        return response()->json($licence, 200);
    }

    public function update_licence_spla($id, Request $request)
    {
        $licence = SplaLicense::find($id);
        $licence->code = $request->code;
        $licence->name = $request->name;
        $licence->cost = $request->cost;
        $licence->type = $request->type;
        $licence->save();

        return response()->json($licence, 200);
    }

    public function update_status_licence_spla($id, Request $request)
    {
        $licence = SplaLicense::find($id);
        $licence->is_deleted = $request->is_deleted;
        $licence->save();

        return response()->json($licence, 200);
    }

    public function fourwall_details($id)
    {
        $fourwalls = Fourwall::selectRaw('
            idfourwall,equipment,serie,cost,date_start,date_end,fourwalls.idproject,fourwalls.is_deleted,name
        ')->join('projects', 'projects.idproject', '=', 'fourwalls.idproject')
            ->where('projects.idproject', $id)
            ->orderBy('is_deleted')->get();
        return view('maintenance.fourwalls', ['fourwalls' => $fourwalls]);
    }

    public function nexus_details($id)
    {
        $nexus = Nexus::selectRaw('
            idnexus,network_point,cost,serie,date_start,date_end,nexus.idproject,nexus.is_deleted,name
        ')->join('projects', 'projects.idproject', '=', 'nexus.idproject')
            ->where('projects.idproject', $id)
            ->orderBy('is_deleted')->get();
        return view('maintenance.nexus', ['nexus' => $nexus]);
    }

    public function hp_details($id)
    {
        $hps = Hp::selectRaw('
            idhp,equipment,serie,cost,date_start,date_end,hps.idproject,hps.is_deleted,name
        ')->join('projects', 'projects.idproject', '=', 'hps.idproject')
            ->where('projects.idproject', $id)
            ->orderBy('is_deleted')->get();
        return view('maintenance.hps', ['hps' => $hps]);
    }

    public function update_fourwall($id, Request $request)
    {
        $fourwall = Fourwall::find($id);
        $fourwall->equipment = $request->equipment;
        $fourwall->serie = $request->serie;
        $fourwall->cost = $request->cost;
        $fourwall->date_start = $request->date_start;
        $fourwall->date_end = $request->date_end;
        $fourwall->save();

        return response()->json($fourwall, 200);
    }

    public function update_nexus($id, Request $request)
    {
        $nexus = Nexus::find($id);
        $nexus->network_point = $request->network_point;
        $nexus->cost = $request->cost;
        $nexus->serie = $request->serie;
        $nexus->date_start = $request->date_start;
        $nexus->date_end = $request->date_end;
        $nexus->save();

        return response()->json($nexus, 200);
    }

    public function update_hp($id, Request $request)
    {
        $hp = Hp::find($id);
        $hp->equipment = $request->equipment;
        $hp->serie = $request->serie;
        $hp->cost = $request->cost;
        $hp->date_start = $request->date_start;
        $hp->date_end = $request->date_end;
        $hp->save();

        return response()->json($hp, 200);
    }

    public function update_fourwall_status($id)
    {
        $fourwall = Fourwall::find($id);
        $fourwall->is_deleted = true;
        $fourwall->save();

        return response()->json($fourwall, 200);
    }

    public function update_nexus_status($id)
    {
        $nexus = Nexus::find($id);
        $nexus->is_deleted = true;
        $nexus->save();

        return response()->json($nexus, 200);
    }

    public function update_hp_status($id)
    {
        $hp = Hp::find($id);
        $hp->is_deleted = true;
        $hp->save();

        return response()->json($hp, 200);
    }

    public function licence_spla_servers()
    {
        $servers = Server::selectRaw("
            servers.idserver, servers.idproject, servers.name as server_name,projects.name as project_name,
            JSON_AGG(json_build_object('spla_type', spla_licenses.type, 'spla_code', spla_licenses.code, 'spla_cost', spla_licenses.cost)) AS slpa_licenses
        ")->join('projects', 'projects.idproject', '=', 'servers.idproject')
            ->join('spla_assigned_discounts', 'spla_assigned_discounts.idserver', '=', 'servers.idserver')
            ->join('spla_licenses', 'spla_licenses.idspla', '=', 'spla_assigned_discounts.idspla')
            ->groupBy(['servers.idserver', 'servers.idproject', 'server_name', 'project_name'])->get();

        $resources = ResourceHistory::selectRaw('
            resources_history.idserver, resources_history.amount, resources_history.name as resource_name, resources_history.date
            ')->where('resources_history.name', 'CPU')
            ->where('resources_history.amount', '>', 0)->get();

        $resources_by_server = [];

        foreach ($servers as $server) {
            $spla_licenses = json_decode($server->slpa_licenses, true);
            $resource_desc = collect($resources)->where('idserver', $server->idserver)->all();

            if (sizeof($resource_desc) == 0) {
                $resource = new ResourceHistory();
                $resource->amount = 0;
                $resource->name = 'CPU';
                $resource->date = date('Y-m-d');
                $resource_desc = [$resource];
            }

            foreach ($spla_licenses as $spla_license) {
                $lic_req = 1;
                $cpu = collect($resource_desc)->sortByDesc('date')->first()->amount;
                if (str_contains($spla_license['spla_type'], 'SQL Server')) {
                    $lic_req = ReportController::licenceRequired($resource_desc);
                }
                array_push($resources_by_server, [
                    'idproject' => $server->idproject,
                    'server_name' => $server->server_name,
                    'project_name' => $server->project_name,
                    'CPU' => $cpu,
                    'license_code' => $spla_license['spla_code'],
                    'license_type' => $spla_license['spla_type'],
                    'license_req' => $lic_req,
                    'license_cost' => $lic_req * $spla_license['spla_cost']
                ]);
            }
        }

        return view('maintenance.licence_spla_servers', ['servers' => $resources_by_server]);
    }
}

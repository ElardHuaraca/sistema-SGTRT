<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Fourwall;
use App\Models\Nexus;
use App\Models\Hp;
use App\Models\Sow;
use App\Models\SplaLicense;

class MaintenanceController extends Controller
{
    public function sow()
    {
        $sows = Sow::all();
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

        return response()->json($result, 200);
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



        return response()->json($sow, 200);
    }

    public function update_sow_status($id, Request $request)
    {
        $sow = Sow::find($id);
        $sow->is_deleted = $request->is_deleted;
        $sow->save();

        return response()->json($sow, 200);
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
        $projects = Project::select(
            'projects.idproject',
            'projects.name',
            \DB::raw('(SELECT SUM(fourwalls.cost) FROM fourwalls WHERE projects.idproject = fourwalls.idproject AND fourwalls.is_deleted = false) AS costoFourwalls'),
            \DB::raw('(SELECT SUM(nexus.cost) FROM nexus WHERE projects.idproject = nexus.idproject AND nexus.is_deleted = false) AS costoNexus'),
            \DB::raw('(SELECT SUM(hps.cost) FROM hps WHERE projects.idproject = hps.idproject AND hps.is_deleted = false) AS costoHp')
        )->get();

        return view('maintenance.costs', ['projects' => $projects]);
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
            idnexus,network_point,cost,nexus.idproject,nexus.is_deleted,name
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
        if ($request->date_end != null) $fourwall->date_end = $request->date_end;
        $fourwall->save();

        return response()->json($fourwall, 200);
    }

    public function update_nexus($id, Request $request)
    {
        $nexus = Nexus::find($id);
        $nexus->network_point = $request->network_point;
        $nexus->cost = $request->cost;
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
        if ($request->date_end != null) $hp->date_end = $request->date_end;
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
}

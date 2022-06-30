<?php

namespace App\Http\Controllers;

use App\Models\Fourwall;
use App\Models\Hp;
use App\Models\Nexus;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Server;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('maintenance.projects', ['projects' => $projects]);
    }

    public function store(Request $request)
    {
        $project = new Project();
        $project->idproject = $request->idproject;
        $project->name = $request->name;
        $project->save();
        return response()->json($project, 200);
    }

    public function update($id, Request $request)
    {
        $project = Project::find($id);
        $project->idproject = $request->idproject;
        $project->name = $request->name;
        $project->save();

        $nexus = Nexus::all();
        foreach ($nexus as $nexu) {
            $nexu->idproject = $project->idproject;
            $nexu->save();
        }

        $hps = Hp::all();
        foreach ($hps as $hp) {
            $hp->idproject = $project->idproject;
            $hp->save();
        }

        $fourwalls = Fourwall::all();
        foreach ($fourwalls as $fourwall) {
            $fourwall->idproject = $project->idproject;
            $fourwall->save();
        }

        $servers = Server::where('idproject', $project->idproject)->get();
        foreach ($servers as $server) {
            $server->idproject = $project->idproject;
            $server->save();
        }

        return response()->json($project, 200);
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();
        return response()->json($project, 200);
    }

    public function maintenanceCost()
    {
        $projects = Project::select(
            'proyecto.idproyecto',
            'proyecto.nombre',
            \DB::raw('(SELECT SUM(fourwalls.costo) FROM fourwalls WHERE proyecto.idproyecto = fourwalls.idproyecto AND fourwalls.eliminado = false) AS costoFourwalls'),
            \DB::raw('(SELECT SUM(nexus.costo) FROM nexus WHERE proyecto.idproyecto = nexus.idproyecto AND nexus.eliminado = false) AS costoNexus'),
            \DB::raw('(SELECT SUM(hp.costo) FROM hp WHERE proyecto.idproyecto = hp.idproyecto AND hp.eliminado = false) AS costoHp')
        )->get();

        return view('maintenance.costs', ['projects' => $projects]);
    }
}

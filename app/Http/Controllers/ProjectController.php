<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

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

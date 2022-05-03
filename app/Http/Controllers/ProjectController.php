<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(){
        $projects = Project::all();
        return view('maintenance.projects',['projects'=> $projects]);
    }

    public function store(Request $request){
        $project = new Project();
        $project->idproyecto = $request->id;
        $project->nombre = $request->name;
        $project->save();
        return response()->json($project, 200);
    }

    public function update($id, Request $request){
        $project = Project::find($id);
        $project->idproyecto = $request->id;
        $project->nombre = $request->name;
        $project->save();
        return response()->json($project, 200);
    }

    public function destroy($id){
        $project = Project::find($id);
        $project->delete();
        return response()->json($project, 200);
    }
}

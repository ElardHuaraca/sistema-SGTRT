<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nexus;

class NexusController extends Controller
{
    public function show($id) {
        return view('nexus');
    }

    public function store(Request $request){
        $nexus = new Nexus();
        $nexus->idproyecto = $request->idproyecto;
        $nexus->punto_red = $request->point_red_nexus;
        $nexus->costo = $request->cost;
        $nexus->save();
        return response()->json($nexus, 200);
    }
}

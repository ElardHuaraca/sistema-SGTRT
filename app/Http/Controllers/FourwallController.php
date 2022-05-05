<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fourwall;

class FourwallController extends Controller
{

    function show(Request $request){
        $fourwalls = Fourwall::where('idproyecto', $request->id)->get();
        return response()->json($fourwalls, 200);
    }

    function store(Request $request){
        $fourwall = new Fourwall();
        $fourwall->idproyecto = $request->idproyecto;
        $fourwall->equipo = $request->equipment;
        $fourwall->serie = $request->serie;
        $fourwall->costo = $request->cost;
        $fourwall->fec_inicio = $request->fech_inicio;
        if($request->fech_fin != null) $fourwall->fec_fin = $request->fech_fin;
        $fourwall->save();
        return response()->json($fourwall, 200);
    }
}

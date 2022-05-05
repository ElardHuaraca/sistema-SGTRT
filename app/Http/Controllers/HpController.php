<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hp;

class HpController extends Controller
{
    public function show($id) {
        return view('hp');
    }

    public function store(Request $request){
        $hp = new Hp();
        $hp->idproyecto = $request->idproyecto;
        $hp->equipo = $request->equipment;
        $hp->serie = $request->serie;
        $hp->costo = $request->cost;
        $hp->fec_inicio = $request->fech_inicio;
        if($request->fech_fin != null) $hp->fec_fin = $request->fech_fin;
        $hp->save();
        return response()->json($hp, 200);
    }
}

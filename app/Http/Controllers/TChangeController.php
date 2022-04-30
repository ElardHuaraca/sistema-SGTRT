<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TChange;

class TChangeController extends Controller
{

    public static function getTChange(){
        $tChange = TChange::first();
        return $tChange;
    }

    public function updateTChange(Request $request){
        $tChange = TChange::first();
        $tChange->valor = $request->valor;
        $tChange->save();
        return response()->json([ 'valor' => $request->valor],200);
    }
}

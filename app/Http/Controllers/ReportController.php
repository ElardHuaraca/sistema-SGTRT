<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResourceConsumption;
use App\Models\Grafic;

class ReportController extends Controller
{
    public function index(){
        $resources = ResourceConsumption::all();
        return view('reports.IT-resources-consumption',['resources'=> $resources]);
    }

    public function show($id){
        $grafic = Grafic::all();
        return view('reports.grafics',['grafic'=> $grafic,'name'=> $id]);
    }
}

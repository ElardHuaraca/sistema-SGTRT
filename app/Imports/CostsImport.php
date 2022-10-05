<?php

namespace App\Imports;

use App\Models\Fourwall;
use App\Models\Hp;
use App\Models\Nexus;
use App\Models\Project;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CostsImport implements ToArray, WithHeadingRow
{

    public function __construct()
    {
        $this->data = [];
    }

    public function array(array $rows)
    {
        $this->data = $rows;

        return $rows;
    }

    public function processData()
    {
        foreach ($this->data as $row) {
            $idproject = $row['alp'];

            $project = Project::find($idproject);
            if (!$project) continue;

            switch ($row['tipo_de_mantimiento']) {
                case 'F' || 'f':
                    $this->saveFourwall($row, $project);
                    break;
                case 'HP' || 'hp':
                    $this->saveHP($row, $project);
                    break;
                case 'N' || 'n':
                    $this->saveNexus($row, $project);
                    break;
                default:
                    break;
            }
        }
    }

    private function saveFourwall($row, $project)
    {
        if ($row['equipo'] == null || $row['serie'] == null || $row['costo'] == null || $row['fecha_inicio'] == null) return;

        $fourwall = new Fourwall();
        $fourwall->idproject = $project->idproject;
        $fourwall->equipment = $row['equipo'];
        $fourwall->serie = $row['serie'];
        $fourwall->cost = $row['costo'];
        $fourwall->start_date = Carbon::createFromFormat('d/m/Y', $row['fecha_inicio']);
        $fourwall->save();
    }

    private function saveHP($row, $project)
    {
        if ($row['equipo'] == null || $row['serie'] == null || $row['costo'] == null || $row['fecha_inicio'] == null) return;
        $hp = new Hp();
        $hp->idproject = $project->idproject;
        $hp->equipment = $row['equipo'];
        $hp->serie = $row['serie'];
        $hp->cost = $row['costo'];
        $hp->start_date = Carbon::createFromFormat('d/m/Y', $row['fecha_inicio']);
        $hp->save();
    }

    private function saveNexus($row, $project)
    {
        if ($row['punto_de_red'] == null || $row['serie'] == null || $row['costo'] == null || $row['fecha_inicio'] == null) return;
        $nexus = new Nexus();
        $nexus->idproject = $project->idproject;
        $nexus->equipment = $row['punto_de_red'];
        $nexus->serie = $row['serie'];
        $nexus->cost = $row['costo'];
        $nexus->start_date = Carbon::createFromFormat('d/m/Y', $row['fecha_inicio']);
        $nexus->save();
    }
}

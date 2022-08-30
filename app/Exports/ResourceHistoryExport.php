<?php

namespace App\Exports;

use App\Models\Export\ResourceHistory;
use App\Models\Server;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Axis;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class ResourceHistoryExport implements WithMultipleSheets
{
    public function __construct($date_start, $date_end, $idserver)
    {
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->idserver = $idserver;
    }

    public function sheets(): array
    {
        return [
            0 => new HistorySheetExport($this->date_start, $this->date_end, $this->idserver),
            1 => new CPUSheetExport($this->date_start, $this->date_end, $this->idserver),
        ];
    }
}

class HistorySheetExport implements FromArray, WithHeadings, WithEvents, WithStrictNullComparison, WithTitle
{
    public function __construct($date_start, $date_end, $idserver)
    {
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->idserver = $idserver;
    }

    public function title(): string
    {
        return 'Historial de Recursos';
    }

    public function headings(): array
    {
        return [
            'Servidor',
            'Activo',
            'ALP',
            'Proyecto',
            'Servicio',
            'CPU',
            'RAM',
            'Disco',
            'Fecha de Recursos',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                /* active autofilters */
                $event->sheet->getDelegate()->setAutoFilter('E1:I1');

                /* change style from headers */
                $event->sheet->getDelegate()->getStyle('A1:J1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:J1')->getFont()->setSize(13);
                $event->sheet->getDelegate()->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:J1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                /* change background from headers */
                $event->sheet->getDelegate()->getStyle('A1:J1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFEEEEEE');

                /* color all borders black headers */
                $event->sheet->getDelegate()->getStyle('A1:I1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


                /* vertical centers columns */
                $event->sheet->getDelegate()->getStyle('E:E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('F:F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('G:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('H:H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('I:I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                /* color borders all content */
                $event->sheet->getDelegate()->getStyle('A2:I' . $event->sheet->getDelegate()->getHighestRow())
                    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }

    public function array(): array
    {
        return resources($this->date_start, $this->date_end, $this->idserver);
    }
}

class CPUSheetExport implements WithTitle, WithCharts
{
    public function __construct($date_start, $date_end, $idserver)
    {
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->idserver = $idserver;
    }

    public function title(): string
    {
        return 'Grafica de uso CPU';
    }

    public function charts()
    {
        $values = resources($this->date_start, $this->date_end, $this->idserver);

        [$CPU, $s, $d, $date] = getCPUHistoryAndDate($values);

        /* create char with CPU amount and date */

        $label = [
            new DataSeriesValues('String', 'Worksheet!$B$1', null, 2),
            new DataSeriesValues('String', 'Worksheet!$C$1', null, 2),
        ];
        $categories = [new DataSeriesValues('String', 'Worksheet!$B$2:$B$5', null, 100)];
        $Xvalues  = [new DataSeriesValues('Number', 'Worksheet!$A$2:$A$5', null, 3)];


        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            null, // plotGrouping
            range(0, \count($Xvalues) - 1), // plotOrder
            $label, // plotLabel
            [], // plotCategory
            $Xvalues // plotValues
        );

        $plot   = new PlotArea(null, [$series]);

        $legend = new Legend();

        $chart = new Chart('', new Title('Uso CPU'), $legend, $plot);
        $chart->setTopLeftPosition('A1');
        $chart->setBottomRightPosition('J25');

        return $chart;
    }
}

function resources($date_start, $date_end, $idserver)
{
    $resources = [];
    $servers = Server::selectRaw(
        'servers.idserver, servers.name as server_name,
            servers.active, servers.idproject,
            projects.name AS project_name, jsonb_object_agg(resources_history.name,resources_history.amount) as resources,
            servers.service, resources_history.date as date_resources'
    )->join('projects', function ($join) {
        $join->on('projects.idproject', '=', 'servers.idproject');
    })->join('resources_history', function ($join) {
        $join->on('resources_history.idserver', '=', 'servers.idserver');
    })->whereBetween('resources_history.date', [$date_start, $date_end])
        ->where('servers.idserver', $idserver)
        ->groupBy(['servers.idserver', 'servers.name', 'servers.active', 'servers.idproject', 'project_name', 'service', 'resources_history.date'])
        ->orderBy('resources_history.date', 'asc')->get();

    foreach ($servers as $server) {
        $res = json_decode($server->resources);
        $resource_history = new ResourceHistory();
        $resource_history->server_name = $server->server_name;
        $resource_history->active_server = $server->active;
        $resource_history->idproject = $server->idproject;
        $resource_history->project_name = $server->project_name;
        $resource_history->service = $server->service;
        $resource_history->CPU = isset($res->CPU) ? $res->CPU : 0;
        $resource_history->RAM = isset($res->RAM) ? $res->RAM : 0;
        $resource_history->DISK = (isset($res->HDD) ? $res->HDD : 0) + (isset($res->SSD) ? $res->SSD : 0);
        $resource_history->date = date('d/m/Y', strtotime($server->date_resources));
        array_push($resources, $resource_history);
    }

    return $resources;
}

function getCPUHistoryAndDate($data)
{
    $CPU = array_map(function ($item) {
        return $item->CPU;
    }, $data);

    $RAM = array_map(function ($item) {
        return $item->RAM;
    }, $data);

    $DISK = array_map(function ($item) {
        return $item->DISK;
    }, $data);

    $date = array_map(function ($item) {
        return $item->date;
    }, $data);

    return [$CPU, $RAM, $DISK, $date];
}

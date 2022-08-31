<?php

namespace App\Exports;

use App\Models\Export\TariffTiByProject;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ITTariffByProjectExport implements FromArray, WithTitle, WithHeadings, WithEvents, WithStrictNullComparison
{
    public function __construct($ittariff, $exange_rate)
    {
        $this->ittariff = $ittariff;
        $this->exange_rate = $exange_rate;
    }

    public function array(): array
    {
        $items = array_map(function ($item) {
            $ittariffByProject = new TariffTiByProject();
            $ittariffByProject->idproject = $item->idproject;
            $ittariffByProject->project_name = $item->project_name;
            $ittariffByProject->server_name = $item->server_name;
            $ittariffByProject->sow = $item->sow_name;
            $ittariffByProject->CPU = $item->CPU * $this->exange_rate->value;
            $ittariffByProject->RAM = $item->RAM * $this->exange_rate->value;
            $ittariffByProject->DISK = $item->DISK * $this->exange_rate->value;
            $ittariffByProject->lic_spla = $item->cost_splas * $this->exange_rate->value;
            $ittariffByProject->lic_cloud = $item->lic_cloud * $this->exange_rate->value;
            $ittariffByProject->backup = $item->backup * $this->exange_rate->value;
            $ittariffByProject->mo = $item->mo * $this->exange_rate->value;
            $ittariffByProject->total = $item->cost_total * $this->exange_rate->value;
            return $ittariffByProject;
        }, $this->ittariff);

        array_walk($items, function (&$item) {
            $item->CPU = number_format($item->CPU, 2);
            $item->RAM = number_format($item->RAM, 2);
            $item->DISK = number_format($item->DISK, 2);
            $item->lic_spla = number_format($item->lic_spla, 2);
            $item->lic_cloud = number_format($item->lic_cloud, 2);
            $item->backup = number_format($item->backup, 2);
            $item->mo = number_format($item->mo, 2);
            $item->total = number_format($item->total, 2);
        });

        return $items;
    }

    public function title(): string
    {
        return 'Tarifario TI Servidor';
    }

    public function headings(): array
    {
        return [
            'ALP',
            'Proyecto',
            'Servidor',
            'SOW',
            'CPU',
            'RAM',
            'Disco',
            'Licencia Spla',
            'Licencia Cloud',
            'Servicio Backup',
            'Costo Mantenimiento',
            'Costo Total'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                /* change headers style */
                $event->sheet->getDelegate()->getStyle('A1:L1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:L1')->getFont()->setSize(13);
                $event->sheet->getDelegate()->getStyle('A1:L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:L1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                /* Change color text header */
                $event->sheet->getDelegate()->getStyle('A1:L1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
                /* change background headers */
                $event->sheet->getDelegate()->getStyle('A1:L1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('2A6099');
                /* color borders all content */
                $event->sheet->getDelegate()->getStyle('A2:L' . $event->sheet->getDelegate()->getHighestRow())
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                /* center colums */
                $event->sheet->getDelegate()->getStyle('E:E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('F:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('H:H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('I:I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('J:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('K:K')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                /* information exchange rates- */
                $event->sheet->getDelegate()->mergeCells('O2:P2');
                $event->sheet->getDelegate()->getStyle('O2:P2')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('O2:P2')->getFont()->setSize(13);
                $event->sheet->getDelegate()->getStyle('O2:P2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('O2:P2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getCell('O2')->setValue('Tipo de cambio:');
                $event->sheet->getDelegate()->getCell('Q2')->setValue($this->exange_rate->value);
            }
        ];
    }
}

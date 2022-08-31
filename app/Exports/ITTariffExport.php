<?php

namespace App\Exports;

use App\Models\Export\TariffTi;
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

class ITTariffExport implements FromArray, WithStrictNullComparison, WithHeadings, WithTitle, WithEvents
{

    public function __construct($ittariff, $exange_rate)
    {
        $this->ittariff = $ittariff;
        $this->exange_rate = $exange_rate;
    }

    public function title(): string
    {
        return 'Tarifario TI';
    }

    public function headings(): array
    {
        return [
            'ALP',
            'Proyecto',
            'CPU',
            'Disco',
            'RAM',
            'Licencia Spla',
            'Licencia Cloud',
            'Servicio Backup',
            'MO Cloud Equipo',
            'Costo Mantenimiento',
            'Costo Total',
        ];
    }

    public function array(): array
    {
        return array_map(function ($item) {

            $tariffit = new TariffTi();
            $tariffit->ALP = $item->idproject;
            $tariffit->project_name = $item->project_name;
            $tariffit->CPU = $item->CPU * $this->exange_rate->value;
            $tariffit->DISK = $item->DISK * $this->exange_rate->value;
            $tariffit->RAM = $item->RAM * $this->exange_rate->value;
            $tariffit->lic_spla = $item->cost_splas * $this->exange_rate->value;
            $tariffit->lic_cloud = $item->lic_cloud * $this->exange_rate->value;
            $tariffit->backup = $item->backup * $this->exange_rate->value;
            $tariffit->mo = $item->mo * $this->exange_rate->value;
            $tariffit->maintenance = $item->cost_maintenance * $this->exange_rate->value;
            $tariffit->total = $item->cost_total * $this->exange_rate->value;

            return $tariffit;
        }, $this->ittariff);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                /* change headers style */
                $event->sheet->getDelegate()->getStyle('A1:K1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:K1')->getFont()->setSize(13);
                $event->sheet->getDelegate()->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:K1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                /* Change color text header */
                $event->sheet->getDelegate()->getStyle('A1:K1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

                /* change background headers */
                $event->sheet->getDelegate()->getStyle('A1:K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('2A6099');

                /* color borders all content */
                $event->sheet->getDelegate()->getStyle('A2:K' . $event->sheet->getDelegate()->getHighestRow())
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                /* center colums */
                $event->sheet->getDelegate()->getStyle('C:C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('D:D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('E:E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('F:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('H:H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('I:I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('J:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}

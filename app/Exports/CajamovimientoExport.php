<?php

namespace App\Exports;

use App\Enums\MovimientosEnum;
use App\Models\Cajamovimiento;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CajamovimientoExport implements FromCollection, WithCustomStartCell, Responsable, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize, WithStyles
{

    use Exportable;

    private $fileName = '';
    private $filters;
    private $writerType = Excel::XLSX;


    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($filters)
    {
        // dd($filters);
        // dd(Carbon::now('America/Lima')->year);
        $this->filters = $filters;
        $this->fileName = 'REPORTE_DE_CAJA_' . now('America/Lima')->format('dmYHis') . '.xlsx';
    }

    public function collection()
    {
        $cajamovimientos = Cajamovimiento::with(['sucursal', 'openbox.box', 'monthbox', 'concept', 'methodpayment', 'moneda', 'user'])
            ->queryFilter($this->filters)->orderByDesc('date')->get();
        return $cajamovimientos;
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function columnFormats(): array
    {
        return [
            // 'A' => 'dd/mm/yyyy',
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $horizontal_center = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ];
        $sheet->setTitle('Movimientos');
        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
            if ($rowIndex > 1) { // Skip the header row
                $cellValue = $sheet->getCell("E{$rowIndex}")->getValue();
                $sheet->getStyle("E{$rowIndex}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' =>  $cellValue == MovimientosEnum::INGRESO->value ? '0FB9B9' : 'F50000'
                        ]
                    ],
                ]);
            }
        }
        // $sheet->getStyle('A1:M1')->applyFromArray([
        //     'font' => [
        //         'bold' => true,
        //     ],
        //     'alignment' => [
        //         'horizontal' => 'center',
        //     ],
        //     'fill' => [
        //         'fillType' => 'solid',
        //         'startColor' => [
        //             'argb' => '0FB9B9'
        //         ],
        //     ],
        // ]);

        // $sheet->getStyle('A1:M' . $sheet->getHighestRow())->applyFromArray([
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => 'thin',
        //         ],
        //     ],
        // ]);

        // $sheet->getStyle('A1')->applyFromArray([]);

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '0FB9B9'
                    ],
                ],
            ],
            'A1:M1' => [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '0FB9B9'
                    ],
                ],
            ],
            'A1:M' . $sheet->getHighestRow() => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '0FB9B9'],
                    ],
                ],
            ],
            'A2:M' . $sheet->getHighestRow() => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'A2:A' . $sheet->getHighestRow() => $horizontal_center,
            'B2:B' . $sheet->getHighestRow() => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ]
            ],
            'C2:F' . $sheet->getHighestRow() => $horizontal_center,
            'J2:M' . $sheet->getHighestRow() => $horizontal_center,
            'A2' => [], //Default focus
        ];
    }

    public function headings(): array
    {
        return [
            'FECHA',
            'MONTO',
            'MONEDA',
            'TIPO DE CAMBIO',
            'MOVIMIENTO',
            'FORMA DE PAGO',
            'CONCEPTO',
            'REFERENCIA',
            'DETALLE',
            'CAJA',
            'CAJA MENSUAL',
            'SUCURSAL',
            'USUARIO',
        ];
    }

    public function map($item): array
    {

        $date = new \DateTime($item->date);
        $date->setTimezone(new \DateTimeZone('America/Lima'));

        return [
            Date::dateTimeToExcel($date),
            number_format($item->amount, 2, '.', ''),
            $item->moneda->currency,
            $item->tipocambio,
            $item->typemovement->value,
            $item->methodpayment->name,
            $item->concept->name,
            $item->referencia,
            $item->detalle,
            $item->openbox->box->name,
            $item->monthbox->name,
            $item->sucursal->name,
            $item->user->name,
        ];
    }
}

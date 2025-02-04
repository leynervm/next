<?php

namespace App\Exports;

use App\Enums\FilterReportsEnum;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Excel;
use Modules\Ventas\Entities\Venta;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VentaExport implements FromCollection, WithCustomStartCell, Responsable, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{

    use Exportable;

    private $fileName = '';
    private $filters;
    public $endcell = '';
    public $sumatorias = [];
    private $writerType = Excel::XLSX;

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->endcell = $this->filters['typereporte'] == FilterReportsEnum::VENTAS_POR_COBRAR->value ? 'P' : 'O';
        $this->fileName = 'REPORTE_DE_VENTAS_' . now('America/Lima')->format('dmYHis') . '.xlsx';
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $ventas = Venta::query()->select('ventas.*')
            ->with(['sucursal', 'client', 'typepayment', 'moneda', 'user', 'seriecomprobante.typecomprobante'])
            ->when($this->filters['typecomprobante_id'], function ($subq, $typecomprobante_id) {
                $subq->whereHas('seriecomprobante', function ($q) use ($typecomprobante_id) {
                    $q->where('typecomprobante_id', $typecomprobante_id);
                });
            })->when($this->filters['typereporte'] == FilterReportsEnum::VENTAS_POR_COBRAR->value, function ($subq) {
                $subq->whereColumn('paymentactual', '<', 'total');
            })->when($this->filters['typereporte'] == FilterReportsEnum::TOP_TEN_VENTAS->value, function ($subq) {
                $subq->orderByDesc('total')->take(10);
            }, function ($subq) {
                $subq->orderByDesc('date');
            })->get();

        $this->sumatorias = $ventas->groupBy('moneda_id')->map(function ($ventas) {
            $amounts = [
                'moneda_id' => $ventas->first()->moneda_id,
                'moneda' => $ventas->first()->moneda,
                'total' => $ventas->sum('total'),
            ];
            if ($this->filters['typereporte'] == FilterReportsEnum::VENTAS_POR_COBRAR->value) {
                $amounts['saldos'] = $ventas->sum('total') - $ventas->sum('paymentactual');
            }
            return (object) $amounts;
        })->sortBy('moneda_id')->values();

        return $ventas;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getHighestDataRow();
                $event->sheet->getStyle('B' . $lastRow + 1 . ':B' . $lastRow + 1)->getNumberFormat()->setFormatCode('#,##0.00');

                foreach ($this->sumatorias as $item) {
                    if (key_exists('saldos', get_object_vars($item))) {
                        $event->sheet->append(['SALDOS', $item->saldos, $item->moneda->currency]);
                        $event->sheet->getStyle('B' . $event->sheet->getHighestDataRow() . ':C' . $event->sheet->getHighestDataRow())->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => [
                                    'rgb' => 'F50000',
                                ],
                            ],
                        ]);
                    }
                    $event->sheet->append(['TOTAL', $item->total, $item->moneda->currency]);
                }

                $event->sheet->getStyle('A' . $lastRow + 2 . ':A' . $event->sheet->getHighestDataRow())->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => 'FFFFFF',
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '0FB9B9'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '0FB9B9'
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ],
                ]);
                $event->sheet->getStyle('B' . $lastRow + 2 . ':B' . $event->sheet->getHighestDataRow())->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '0FB9B9'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    ],
                ])->getNumberFormat()->setFormatCode('#,##0.00');
                $event->sheet->getStyle('C' . $lastRow + 2 . ':C' . $event->sheet->getHighestDataRow())->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '0FB9B9'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ],
                ]);
                $event->sheet->getStyle('A2')->applyFromArray([]);
            },
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function columnFormats(): array
    {
        $colformats = [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
        if ($this->filters['typereporte'] == FilterReportsEnum::VENTAS_POR_COBRAR->value) {
            $colformats['M'] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2;
        }
        return $colformats;
    }

    public function styles(Worksheet $sheet)
    {
        $horizontal_center = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ];
        $sheet->setTitle('Ventas');
        // $sheet->getStyle('D2:E' . $sheet->getHighestRow())->applyFromArray([
        //     'alignment' => [
        //         'wrapText' => true,
        //     ],
        // ]);

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
            'A1:' . $this->endcell . $sheet->getHighestRow() => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '0FB9B9'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'A2:C' . $sheet->getHighestRow() => $horizontal_center,
            'F' => $horizontal_center,
            'G2:L' . $sheet->getHighestRow() => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            'M2:P' . $sheet->getHighestRow() => $horizontal_center,
            'A2' => [], //Default focus
        ];
    }

    public function headings(): array
    {
        $header = [
            'FECHA',
            'SERIE',
            'TIPO DE COMPROBANTE',
            'CLIENTE',
            'DIRECCION',
            'TIPO DE PAGO',
            'EXONERADO',
            'GRAVADO',
            'GRATUITO',
            'DESCUENTO',
            'IGV',
            'TOTAL',
        ];

        if ($this->filters['typereporte'] == FilterReportsEnum::VENTAS_POR_COBRAR->value) {
            $header[] = 'SALDO';
        }

        $header[] = 'MONEDA';
        $header[] = 'SUCURSAL';
        $header[] = 'USUARIO';
        return $header;
    }

    public function map($item): array
    {

        $date = new \DateTime($item->date);
        $date->setTimezone(new \DateTimeZone('America/Lima'));

        $cell = [
            Date::dateTimeToExcel($date),
            // $item->moneda->currency,
            $item->seriecompleta,
            $item->seriecomprobante->typecomprobante->name,
            $item->client->document . ' - ' . $item->client->name,
            $item->direccion,
            $item->typepayment->name,
            number_format($item->exonerado, 2, '.', ''),
            number_format($item->gravado, 2, '.', ''),
            number_format($item->gratuito, 2, '.', ''),
            number_format($item->descuento, 2, '.', ''),
            number_format($item->igv + $item->igvgratuito, 2, '.', ''),
            number_format($item->total, 2, '.', ''),
        ];

        if ($this->filters['typereporte'] == FilterReportsEnum::VENTAS_POR_COBRAR->value) {
            $cell[] = $item->total - $item->paymentactual;
        }

        $cell[] = $item->moneda->currency;
        $cell[] = $item->sucursal->name;
        $cell[] = $item->user->name;
        return $cell;
    }
}

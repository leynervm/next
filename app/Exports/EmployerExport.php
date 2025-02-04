<?php

namespace App\Exports;

use App\Enums\FilterReportsEnum;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Excel;

class EmployerExport implements WithMultipleSheets
{

    use Exportable;

    private $empresa;
    private $fileName = '';
    private $filters;
    private $writerType = Excel::XLSX;

    public function __construct($filters)
    {
        $this->empresa = view()->shared('empresa');
        $this->filters = $filters;
        $existtypereport = in_array($filters['typereporte'], FilterReportsEnum::values());

        if ($existtypereport) {
            $this->fileName = 'REPORTE DE PAGOS A TRABAJADORES ' . FilterReportsEnum::getLabel($filters['typereporte']) . '.xlsx';
        } else {
            $this->fileName = FilterReportsEnum::getLabel($filters['typereporte']) . '.xlsx';
        }
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            'payments' => new EmployerPaymentExport($this->empresa, $this->filters),
            'adelantos' => new AdelantoEmployerExport($this->empresa, $this->filters),
        ];
        return $sheets;
    }
}

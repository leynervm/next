<?php

namespace App\Exports;

use App\Enums\FilterReportsEnum;
use App\Models\Employer;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdelantoEmployerExport implements FromView, WithTitle, WithStyles
{

    // use Exportable;

    private $empresa;
    private $filters;
    // private $writerType = Excel::XLSX;

    public function __construct($empresa, $filters)
    {
        $this->empresa = $empresa;
        $this->filters = $filters;
    }

    public function view(): View
    {

        $adelantos = Employer::with('sucursal')->withWhereHas('cajamovimientos', function ($query) {
            $query->with(['monthbox', 'methodpayment'])->when($this->filters['monthbox_id'] ?? null, function ($subq, $monthbox_id) {
                $subq->where('monthbox_id', $monthbox_id);
            })->when($this->filters['user_id'] ?? null, function ($subq, $user_id) {
                $subq->where('user_id', $user_id);
            });

            if ($this->filters['typereporte'] == FilterReportsEnum::MES_ACTUAL->value) {
                $query->whereRaw("TO_CHAR(date, 'YYYY-MM')= ?", [
                    Carbon::now('America/Lima')->format('Y-m'),
                ]);
            } elseif ($this->filters['typereporte'] == FilterReportsEnum::MENSUAL->value) {
                $query->whereRaw("TO_CHAR(date, 'YYYY-MM')= ?", [
                    Carbon::parse($this->filters['month'])->format('Y-m'),
                ]);
            } elseif ($this->filters['typereporte'] == FilterReportsEnum::RANGO_MESES->value) {
                $query->whereRaw("TO_CHAR(date, 'YYYY-MM')>= ? AND TO_CHAR(date, 'YYYY-MM')<= ?", [
                    Carbon::parse($this->filters['month'])->format('Y-m'),
                    Carbon::parse($this->filters['monthto'])->format('Y-m'),
                ]);
            } elseif ($this->filters['typereporte'] == FilterReportsEnum::ANUAL->value) {
                $query->whereYear('date', $this->filters['year']);
            } elseif ($this->filters['typereporte'] == FilterReportsEnum::ANIO_ACTUAL->value) {
                $query->whereRaw("TO_CHAR(date, 'YYYY') = ?", [
                    Carbon::now('America/Lima')->format('Y'),
                ]);
            } elseif ($this->filters['typereporte'] == FilterReportsEnum::ULTIMO_MES->value) {
                $query->whereMonth("date", Carbon::now('America/Lima')->subMonth()->format('Y-m'));
            } elseif ($this->filters['typereporte'] == FilterReportsEnum::ULTIMO_ANIO->value) {
                $query->whereYear('date', Carbon::now('America/Lima')->subYear()->year);
            } elseif ($this->filters['typereporte'] == FilterReportsEnum::MESES_SELECCIONADOS->value) {
                $query->whereRaw("TO_CHAR(date, 'YYYY-MM') IN (" . implode(',', array_fill(0, count($this->filters['months']), '?')) . ")", $this->filters['months']);
            }
        })->when($this->filters['sucursal_id'] ?? null, function ($query, $sucursal_id) {
            $query->where('sucursal_id', $sucursal_id);
        })->when($this->filters['employer_id'] ?? null, function ($subq, $employer_id) {
            $subq->where('id', $employer_id);
        });

        $adelantos = $adelantos->withCount('cajamovimientos')->orderBy('id', 'asc')->get();
        return view('admin.reports.excel.report-adelanto-employers', compact('adelantos'));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'ADELANTOS';
    }

    public function styles(Worksheet $sheet)
    {
        foreach (range('A', 'Z') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}

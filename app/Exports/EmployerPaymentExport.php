<?php

namespace App\Exports;

use App\Enums\FilterReportsEnum;
use App\Models\Employerpayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class EmployerPaymentExport implements FromView, WithTitle, ShouldAutoSize
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

        $payments = Employerpayment::with(['employer' => function ($query) {
            $query->with(['sucursal', 'areawork'])
                ->when($this->filters['sucursal_id'] ?? null, function ($subq, $sucursal_id) {
                    $subq->where('sucursal_id', $sucursal_id);
                });
        }])->withWhereHas('cajamovimientos', function ($subq) {
            $subq->when($this->filters['monthbox_id'] ?? null, function ($q, $monthbox_id) {
                $q->where('monthbox_id', $monthbox_id);
            })->when($this->filters['user_id'] ?? null, function ($q, $user_id) {
                $q->where('user_id', $user_id);
            });
        })->when($this->filters['year'] ?? null, function ($subq, $year) {
            $subq->whereRaw("TO_CHAR(TO_DATE(month, 'YYYY-MM'), 'YYYY') = ?", [$year]);
        })->when($this->filters['employer_id'] ?? null, function ($subq, $employer_id) {
            $subq->where('employer_id', $employer_id);
        });

        if ($this->filters['typereporte'] == FilterReportsEnum::MES_ACTUAL->value) {
            $payments->where("month", Carbon::now('America/Lima')->format('Y-m'));
        } elseif ($this->filters['typereporte'] == FilterReportsEnum::MENSUAL->value) {
            $payments->where("month", Carbon::parse($this->filters['month'])->format('Y-m'));
        } elseif ($this->filters['typereporte'] == FilterReportsEnum::RANGO_MESES->value) {
            $payments->whereRaw("TO_CHAR(TO_DATE(month, 'YYYY-MM'), 'YYYY-MM')>= ? AND TO_CHAR(TO_DATE(month, 'YYYY-MM'), 'YYYY-MM')<= ?", [
                Carbon::parse($this->filters['month'])->format('Y-m'),
                Carbon::parse($this->filters['monthto'])->format('Y-m'),
            ]);
        } elseif ($this->filters['typereporte'] == FilterReportsEnum::ANIO_ACTUAL->value) {
            $payments->whereRaw("TO_CHAR(TO_DATE(month, 'YYYY-MM'), 'YYYY') = ?", [
                Carbon::now('America/Lima')->format('Y'),
            ]);
        } elseif ($this->filters['typereporte'] == FilterReportsEnum::ULTIMO_MES->value) {
            $payments->where("month", Carbon::now('America/Lima')->subMonth()->format('Y-m'));
        } elseif ($this->filters['typereporte'] == FilterReportsEnum::ULTIMO_ANIO->value) {
            $payments->whereRaw("TO_CHAR(TO_DATE(month, 'YYYY-MM'), 'YYYY') = ?", [
                Carbon::now('America/Lima')->subYear()->year,
            ]);
        } elseif ($this->filters['typereporte'] == FilterReportsEnum::MESES_SELECCIONADOS->value) {
            $payments->whereIn('month', $this->filters['months']);
        }

        $payments = $payments->orderByDesc('month')->get();

        return view('admin.reports.excel.report-payment-employers', compact('payments'));
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'HISTORIAL DE PAGOS';
    }
}

<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Enums\FilterReportsEnum;
use App\Exports\CajamovimientoExport;
use App\Models\Concept;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Str;

class GenerarReporteCaja extends Component
{

    public $open = false;

    public $monthboxes = [], $openboxes = [], $days = [], $months = [];
    public $typereporte = FilterReportsEnum::DEFAULT->value;
    public $sucursal_id = '', $concept_id = '', $typemovement = '', $methodpayment_id = '',
        $user_id = '', $monthbox_id = '', $openbox_id = '', $moneda_id = '';
    public $date = '', $dateto = '', $week = '', $month = '', $monthto = '', $year = '';

    protected function rules()
    {
        return [
            'typereporte' => ['required', 'integer', 'min:0', /* Rule::enum(FilterReportsEnum::all()) */],
            'sucursal_id' => ['nullable', 'integer', 'min:1', 'exists:sucursals,id'],
            'concept_id' => ['nullable', 'integer', 'min:1', 'exists:concepts,id'],
            'typemovement' => ['nullable', 'string',],
            'methodpayment_id' => ['nullable', 'integer', 'min:1', 'exists:methodpayments,id'],
            'user_id' => ['nullable', 'integer', 'min:1', 'exists:users,id'],
            'monthbox_id' => ['nullable', 'integer', 'min:1', 'exists:monthboxes,id'],
            'openbox_id' => ['nullable', 'integer', 'min:1', 'exists:openboxes,id'],
            'moneda_id' => ['nullable', 'integer', 'min:1', 'exists:monedas,id'],
            'date' => [
                Rule::requiredIf($this->typereporte == FilterReportsEnum::DIARIO->value ||
                    $this->typereporte == FilterReportsEnum::RANGO_DIAS->value),
                'date',
                'date_format:Y-m-d',
                'before_or_equal:today'
            ],
            'dateto' => [
                Rule::requiredIf($this->typereporte == FilterReportsEnum::RANGO_DIAS->value),
                'date',
                'after:date',
                'before_or_equal:today'
            ],
            'week' => [
                Rule::requiredIf($this->typereporte == FilterReportsEnum::SEMANAL->value),
                'before_or_equal:today'
            ],
            'month' => [
                Rule::requiredIf($this->typereporte == FilterReportsEnum::MENSUAL->value ||
                    $this->typereporte == FilterReportsEnum::RANGO_MESES->value),
                'date_format:Y-m',
                'before_or_equal:today'
            ],
            'monthto' => [
                Rule::requiredIf($this->typereporte == FilterReportsEnum::RANGO_MESES->value),
                'date_format:Y-m',
                'after:month',
                'before_or_equal:today'
            ],
            'year' => [
                Rule::requiredIf($this->typereporte == FilterReportsEnum::ANUAL->value),
                'date_format:Y',
                'before_or_equal:today'
            ],
            'days' => [
                Rule::requiredIf($this->typereporte == FilterReportsEnum::DIAS_SELECCIONADOS->value),
                'array',
            ],
            'months' => [
                Rule::requiredIf($this->typereporte == FilterReportsEnum::MESES_SELECCIONADOS->value),
                'array',
            ],
        ];
    }

    public function mount()
    {
        $this->typereporte = FilterReportsEnum::DEFAULT->value;
    }

    public function render()
    {
        $except = [
            FilterReportsEnum::TOP_TEN_VENTAS->value,
            FilterReportsEnum::VENTAS_POR_COBRAR->value,
        ];
        $typereportes = response()->json(FilterReportsEnum::except($except))->getData();

        $sucursals = Sucursal::with(['monthboxes', 'openboxes'])->whereHas('cajamovimientos');
        if (!auth()->user()->isAdmin() || !auth()->user()->hasPermissionTo('admin.reportes.cajas.allsucursals')) {
            $sucursals->where('id', $this->sucursal_id);
        }
        $sucursals = $sucursals->get();
        $methodpayments = Methodpayment::whereHas('cajamovimientos')->get();
        $typemovements = getTiypemovimientos();
        $concepts = Concept::whereHas('cajamovimientos', function ($query) {
            if (!empty($this->typemovement)) {
                $query->where('typemovement', $this->typemovement);
            }
        })->get();
        $users = User::whereHas('cajamovimientos', function ($query) {
            if (!empty($this->sucursal_id)) {
                $query->where('sucursal_id', $this->sucursal_id);
            }
        })->get();
        $monthboxes = Monthbox::whereHas('cajamovimientos')->get();
        $openboxes = Openbox::whereHas('cajamovimientos', function ($query) {
            if (!empty($this->monthbox_id)) {
                $query->where('monthbox_id', $this->monthbox_id);
            }
        })->get();
        $monedas = Moneda::whereHas('cajamovimientos')->get();
        $years = range(date('Y'), 1900);

        return view('livewire.admin.reports.generar-reporte-caja', compact('sucursals', 'concepts', 'typemovements', 'methodpayments', 'users', 'typereportes', 'years', 'monedas'));
    }

    public function validateFilters()
    {
        if (!in_array($this->typereporte, [FilterReportsEnum::DIARIO->value, FilterReportsEnum::RANGO_DIAS->value])) {
            $this->reset(['date']);
        }
        if ($this->typereporte == FilterReportsEnum::ANUAL->value) {
            $this->reset(['date', 'dateto']);
        }
        if ($this->typereporte !== FilterReportsEnum::RANGO_DIAS->value) {
            $this->reset(['dateto']);
        }
        if ($this->typereporte !== FilterReportsEnum::RANGO_MESES->value) {
            $this->reset(['monthto']);
        }
        if ($this->typereporte == FilterReportsEnum::SEMANA_ACTUAL->value) {
            $this->reset(['week']);
        }
        if ($this->typereporte !== FilterReportsEnum::ANUAL->value) {
            $this->reset(['year']);
        }
        if ($this->typereporte !== FilterReportsEnum::MENSUAL->value || $this->typereporte == !FilterReportsEnum::RANGO_MESES->value) {
            $this->reset(['month']);
        }
        if ($this->typereporte !== FilterReportsEnum::DEFAULT->value) {
            $this->reset(['monthbox_id', 'openbox_id']);
        }
        if ($this->typereporte !== FilterReportsEnum::MESES_SELECCIONADOS->value) {
            $this->reset(['months']);
        }
        if ($this->typereporte !== FilterReportsEnum::DIAS_SELECCIONADOS->value) {
            $this->reset(['days']);
        }
    }

    public function updatingOpen()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function updatedTypemovement($value)
    {
        if (!empty($value)) {

            $conceptsArr = Concept::query()->select('id', 'typemovement')->whereHas('cajamovimientos')
                ->where('typemovement', $value)->pluck('id')->toArray();
            if (!empty($this->concept_id) && !in_array($this->concept_id, $conceptsArr)) {
                // if (!empty($this->concept_id)) {
                // }
                $this->reset(['concept_id']);
            }
        }
    }

    public function updatedSucursalId($value)
    {
        $this->reset(['monthboxes', 'monthbox_id', 'openboxes', 'openbox_id']);
        if (!empty($value)) {
            $this->monthboxes = Monthbox::whereHas('cajamovimientos')
                ->where('sucursal_id', $value)->orderByDesc('month')->get();
            $this->openboxes = Openbox::with('user')->whereHas('cajamovimientos')
                ->where('sucursal_id', $value)->orderByDesc('startdate')->get();
        }
    }

    public function updatedMonthboxId($value)
    {
        $this->reset(['openboxes', 'openbox_id']);
        $openboxes = Openbox::with('user')->whereHas('cajamovimientos')
            ->where('sucursal_id', $this->sucursal_id);
        if (!empty($value)) {
            $openboxes->where('monthbox_id', $value);
        }

        $this->openboxes = $openboxes->orderByDesc('startdate')->get();
    }

    public function resetvalues()
    {
        $this->resetExcept(['open']);
    }

    public function exportexcel()
    {
        $this->validateFilters();
        $dataFilters = $this->validate();
        // dd($dataFilters);
        return (new CajamovimientoExport($dataFilters))->download();
    }

    public function exportpdf()
    {
        $this->validateFilters();
        $filters = $this->validate();
        $filters = array_filter($filters, function ($v, $k) {
            if (gettype($v) == 'array') {
                return count($v) > 0;
            } else {
                return $v !== '';
            }
        }, ARRAY_FILTER_USE_BOTH);
        $filters["typereporte"] = Str::lower(FilterReportsEnum::from($this->typereporte)->name);

        return route('admin.reportes.movimientos') . '?' . http_build_query($filters);
        // return $filters;
        // return redirect()->route('admin.reportes.movimientos', [
        //     'filters' => $filters
        // ]);
        // $pdf = Pdf::loadView('admin.reports.report-cajamovimientos', compact('cajamovimientos'));
        // // return $pdf->stream('cajamovimientos.pdf');
        // return $pdf->download('reporte_movimientos.pdf');
    }

    public function addday()
    {
        $this->validate([
            'date' => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:today']
        ]);

        if (in_array($this->date, $this->days)) {
            $this->addError('date', 'día seleccionado ya se encuentra agregado.');
            return false;
        }

        $days = $this->days;
        $days[] = $this->date;
        $this->days = array_values($days);
        $this->resetValidation();
        $this->reset(['date']);
    }

    public function addmonth()
    {
        $this->validate([
            'month' => ['required', 'date', 'date_format:Y-m', 'before_or_equal:today']
        ]);

        if (in_array($this->month, $this->months)) {
            $this->addError('month', 'Mes seleccionado ya se encuentra agregado.');
            return false;
        }

        $months = $this->months;
        $months[] = $this->month;
        $this->months = array_values($months);
        $this->resetValidation();
        $this->reset(['month']);
    }

    public function deleteindex($index, $property)
    {
        unset($this->$property[$index]);
        $this->$property = array_values($this->$property);
        $this->resetValidation();
    }
}

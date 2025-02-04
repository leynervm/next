<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Enums\FilterReportsEnum;
use App\Exports\VentaDetalleExport;
use App\Exports\VentaExport;
use App\Models\Client;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Models\Sucursal;
use App\Models\Typecomprobante;
use App\Models\Typepayment;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Livewire\Component;
use Modules\Ventas\Entities\Venta;

class GenerarReporteVentas extends Component
{

    public $open = false;

    public $monthboxes = [], $openboxes = [], $days = [], $months = [];
    public $typereporte = FilterReportsEnum::DEFAULT->value;
    public $viewreporte = 0, $typecomprobante_id = '', $typepayment_id = '', $client_id = '', $sucursal_id = '', $methodpayment_id = '',
        $user_id = '', $monthbox_id = '', $openbox_id = '', $moneda_id = '';
    public $date = '', $dateto = '', $week = '', $month = '', $monthto = '', $year = '';

    protected function rules()
    {
        return [
            'typereporte' => ['required', 'integer', 'min:0', /* Rule::enum(FilterReportsEnum::all()) */],
            'viewreporte' => ['required', 'integer', 'min:0', 'max:1', /* Rule::enum(FilterReportsEnum::all()) */],
            'sucursal_id' => ['nullable', 'integer', 'min:1', 'exists:sucursals,id'],
            'typecomprobante_id' => ['nullable', 'integer', 'min:1', 'exists:typecomprobantes,id'],
            'client_id' => ['nullable', 'integer', 'min:1', 'exists:clients,id'],
            'typepayment_id' => ['nullable', 'integer', 'min:1', 'exists:typepayments,id'],
            // 'methodpayment_id' => ['nullable', 'integer', 'min:1', 'exists:methodpayments,id'],
            // 'monthbox_id' => ['nullable', 'integer', 'min:1', 'exists:monthboxes,id'],
            // 'openbox_id' => ['nullable', 'integer', 'min:1', 'exists:openboxes,id'],
            'user_id' => ['nullable', 'integer', 'min:1', 'exists:users,id'],
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
        $typereportes = response()->json(FilterReportsEnum::except())->getData();
        $sucursals = Sucursal::with(['monthboxes', 'openboxes'])->whereHas('ventas');
        if (!auth()->user()->isAdmin() || !auth()->user()->hasPermissionTo('admin.reportes.cajas.allsucursals')) {
            $sucursals->where('id', $this->sucursal_id);
        }
        $sucursals = $sucursals->get();
        $users = User::whereHas('ventas', function ($query) {
            $query->when(!empty($this->sucursal_id), function ($query) {
                $query->where('sucursal_id', $this->sucursal_id);
            });
        })->get();
        $methodpayments = Methodpayment::whereHas('cajamovimientos', function ($query) {
            $query->whereHasMorph('cajamovimientable', Venta::class);
        })->get();
        $monthboxes = Monthbox::whereHas('cajamovimientos', function ($query) {
            $query->whereHasMorph('cajamovimientable', Venta::class)->when(!empty($this->sucursal_id), function ($query) {
                $query->where('sucursal_id', $this->sucursal_id);
            });
        });
        $monedas = Moneda::whereHas('ventas')->get();
        $typepayments = Typepayment::whereHas('ventas')->get();
        $typecomprobantes = Typecomprobante::whereHas('seriecomprobantes', function ($query) {
            $query->whereHas('ventas');
        })->get();
        $clients = Client::query()->select('id', 'document', 'name')->whereHas('ventas')->get();
        $years = range(date('Y'), 1900);

        return view('livewire.admin.reports.generar-reporte-ventas', compact('sucursals', 'typepayments', 'typecomprobantes', 'clients', 'methodpayments', 'users', 'typereportes', 'years', 'monedas'));
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


    // public function updatedSucursalId($value)
    // {
    //     $this->reset(['monthboxes', 'monthbox_id', 'openboxes', 'openbox_id']);
    //     $openboxes = Openbox::whereHas('cajamovimientos', function ($query) {
    //         $query->whereHasMorph('cajamovimientable', Venta::class);
    //     })->when(!empty($this->sucursal_id), function ($query, $sucursal_id) {
    //         $query->where('sucursal_id', $sucursal_id);
    //     })->when(!empty($value), function ($query, $monthbox_id) {
    //         $query->where('monthbox_id', $monthbox_id);
    //     });


    //     if (!empty($value)) {
    //         $this->monthboxes = Monthbox::whereHas('cajamovimientos')
    //             ->where('sucursal_id', $value)->orderByDesc('month')->get();
    //         $this->openboxes = Openbox::with('user')->whereHas('cajamovimientos')
    //             ->where('sucursal_id', $value)->orderByDesc('startdate')->get();
    //     }
    // }

    // public function updatedMonthboxId($value)
    // {
    //     $this->reset(['openboxes', 'openbox_id']);
    //     $openboxes = Openbox::whereHas('cajamovimientos', function ($query) {
    //         $query->whereHasMorph('cajamovimientable', Venta::class);
    //     })->when(!empty($this->sucursal_id), function ($query, $sucursal_id) {
    //         $query->where('sucursal_id', $sucursal_id);
    //     })->when(!empty($value), function ($query, $monthbox_id) {
    //         $query->where('monthbox_id', $monthbox_id);
    //     });

    //     $this->openboxes = $openboxes->orderByDesc('startdate')->get();
    // }

    public function resetvalues()
    {
        $this->resetExcept(['open']);
    }

    public function exportexcel()
    {
        $this->validateFilters();
        $dataFilters = $this->validate();
        if ($this->viewreporte == 1) {
            return (new VentaDetalleExport($dataFilters))->download();
        } else {
            return (new VentaExport($dataFilters))->download();
        }
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

        return route('admin.reportes.ventas') . '?' . http_build_query($filters);
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

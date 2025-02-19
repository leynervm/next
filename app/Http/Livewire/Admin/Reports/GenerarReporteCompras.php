<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Enums\FilterReportsEnum;
use App\Exports\CompraExport;
use App\Models\Moneda;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\Typepayment;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Compra;
use Illuminate\Support\Str;

class GenerarReporteCompras extends Component
{

    public $open = false;

    public $typereporte = FilterReportsEnum::DEFAULT->value;
    public $viewreporte = 0, $typepayment_id = '', $moneda_id = '',
        $almacen_id = '', $proveedor_id = '', $sucursal_id = '',
        $user_id = '', $serie = '', $pricetype_id = '';
    public $date = '', $month = '', $year = '';


    protected function rules()
    {
        return [
            'typereporte' => ['required', 'integer', 'min:0', /* Rule::enum(FilterReportsEnum::all()) */],
            'viewreporte' => ['required', 'integer', 'min:0', 'max:1', /* Rule::enum(FilterReportsEnum::all()) */],
            'sucursal_id' => ['nullable', 'integer', 'min:1', 'exists:sucursals,id'],
            'typepayment_id' => ['nullable', 'integer', 'min:1', 'exists:typepayments,id'],
            'proveedor_id' => ['nullable', 'integer', 'min:1', 'exists:proveedors,id'],
            'moneda_id' => ['nullable', 'integer', 'min:1', 'exists:monedas,id'],
            'user_id' => ['nullable', 'integer', 'min:1', 'exists:users,id'],
            'month' => [Rule::requiredIf($this->typereporte == FilterReportsEnum::MENSUAL->value), 'date', 'date_format:Y-m',],
            'year' => [Rule::requiredIf($this->typereporte == FilterReportsEnum::ANUAL->value), 'date_format:Y',],
            'date' => [Rule::requiredIf($this->typereporte == FilterReportsEnum::DIARIO->value), 'date', 'date_format:Y-m-d',],

        ];
    }

    public function mount()
    {
        $this->typereporte = FilterReportsEnum::DEFAULT->value;
    }


    public function render()
    {
        $includes = [
            FilterReportsEnum::DEFAULT->value,
            FilterReportsEnum::DIARIO->value,
            FilterReportsEnum::MENSUAL->value,
            FilterReportsEnum::ULTIMO_MES->value,
            FilterReportsEnum::ULTIMO_ANIO->value,
            FilterReportsEnum::ANUAL->value,
        ];
        $typereportes = response()->json(FilterReportsEnum::in($includes))->getData();
        $users = User::whereHas('compras', function ($query) {
            // $query->when(!empty($this->sucursal_id), function ($query) {
            //     $query->where('sucursal_id', $this->sucursal_id);
            // });
        })->whereHas('sucursal')->get();

        $monedas = Moneda::whereHas('compras')->get();
        $proveedors = Proveedor::whereHas('compras')->get();
        $typepayments = Typepayment::whereHas('compras')->get();
        $sucursals = Sucursal::with(['monthboxes'])->whereHas('ventas');
        if (!auth()->user()->isAdmin() || !auth()->user()->hasPermissionTo('admin.reportes.cajas.allsucursals')) {
            $sucursals->where('id', $this->sucursal_id);
        }
        $sucursals = $sucursals->get();
        $months = Compra::selectRaw("TO_CHAR(date, 'YYYY-MM') as mes")
            ->groupBy('mes')->orderByDesc('mes')->pluck('mes');
        $years = Compra::selectRaw('EXTRACT(YEAR FROM date) as anual')
            ->groupBy('anual')->orderByDesc('anual')->pluck('anual');

        return view('livewire.admin.reports.generar-reporte-compras', compact('typereportes', 'sucursals', 'typepayments', 'proveedors', 'monedas', 'users', 'years', 'months'));
    }

    public function validateFilters()
    {
        if (!in_array($this->typereporte, [FilterReportsEnum::DIARIO->value])) {
            $this->reset(['date']);
        }
        if (!in_array($this->typereporte, [FilterReportsEnum::ANUAL->value, FilterReportsEnum::ULTIMO_ANIO->value])) {
            $this->reset(['year']);
        }
        if (!in_array($this->typereporte, [FilterReportsEnum::MENSUAL->value, FilterReportsEnum::ULTIMO_MES->value])) {
            $this->reset(['month']);
        }
    }

    public function updatingOpen()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function resetvalues()
    {
        $this->resetExcept(['open']);
    }

    public function exportexcel()
    {
        $this->validateFilters();
        $dataFilters = $this->validate();
        return (new CompraExport($dataFilters))->download();
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
        // dd($filters);
        return route('admin.reportes.compras') . '?' . http_build_query($filters);
    }
}

<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Enums\FilterReportsEnum;
use App\Exports\ProductoExport;
use App\Exports\ProductoKardexExport;
use App\Exports\ProductoMinstockExport;
use App\Exports\ProductoPromocionExport;
use App\Exports\ProductoTopExport;
use App\Models\Almacen;
use App\Models\Category;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class GenerarReporteProductos extends Component
{

    public $open = false;

    public $viewstock = '0';
    public $days = [], $months = [], $subcategories = [];
    public $typereporte = FilterReportsEnum::DEFAULT->value;
    public $viewreporte = 0, $producto_id = '', $category_id = '', $subcategory_id = '',
        $almacen_id = '', $client_id = '', $sucursal_id = '',
        $user_id = '', $serie = '', $pricetype_id = '';
    public $date = '', $dateto = '', $week = '', $month = '', $monthto = '', $year = '';


    protected function rules()
    {
        return [
            'typereporte' => ['required', 'integer', 'min:0', /* Rule::enum(FilterReportsEnum::all()) */],
            'viewreporte' => ['required', 'integer', 'min:0', 'max:1', /* Rule::enum(FilterReportsEnum::all()) */],
            'viewstock' => ['required', 'integer', 'min:0', 'max:2', /* Rule::enum(FilterReportsEnum::all()) */],
            'sucursal_id' => ['nullable', 'integer', 'min:1', 'exists:sucursals,id'],
            'producto_id' => ['nullable', 'integer', 'min:1', 'exists:productos,id'],
            'category_id' => ['nullable', 'integer', 'min:1', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'integer', 'min:1', 'exists:subcategories,id'],
            'producto_id' => ['nullable', Rule::requiredIf($this->typereporte == FilterReportsEnum::KARDEX_PRODUCTOS->value), 'integer', 'min:1', 'exists:productos,id'],
            'almacen_id' => ['nullable', 'integer', 'min:1', 'exists:almacens,id'],
            'client_id' => ['nullable', 'integer', 'min:1', 'exists:clients,id'],
             'user_id' => ['nullable', 'integer', 'min:1', 'exists:users,id'],
            'serie' => ['nullable', 'string', 'min:6'],
            'pricetype_id' => ['nullable', Rule::requiredIf($this->typereporte == FilterReportsEnum::CATALOGO_PRODUCTOS->value), 'integer', 'min:1', 'exists:pricetypes,id'],
          
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
            FilterReportsEnum::CATALOGO_PRODUCTOS->value,
            FilterReportsEnum::TOP_TEN_PRODUCTOS->value,
            FilterReportsEnum::KARDEX_PRODUCTOS->value,
            FilterReportsEnum::MIN_STOCK->value,
            FilterReportsEnum::PRODUCTOS_PROMOCIONADOS->value,
        ];
        $typereportes = response()->json(FilterReportsEnum::in($includes))->getData();
        $users = User::whereHas('tvitems', function ($query) {
            // $query->when(!empty($this->sucursal_id), function ($query) {
            //     $query->where('sucursal_id', $this->sucursal_id);
            // });
        })->whereHas('sucursal')->get();
        $almacens = Almacen::whereHas('kardexes')->get();
        $years = range(date('Y'), 1900);
        $productos = Producto::query()->select('productos.id', 'productos.name', 'marca_id', 'category_id', 'subcategory_id')
            ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            ->with('imagen')->visibles()->orderByDesc('novedad')
            ->orderBy('subcategories.orden', 'ASC')
            ->orderBy('categories.orden', 'ASC')->get();
        $categories = Category::query()->select('id', 'name', 'orden')
            ->orderBy('orden', 'asc')->get();

        $pricetypes = [];
        if (view()->shared('empresa')->usarLista()) {
            $pricetypes = Pricetype::activos()->orderBy('id', 'asc')->orderBy('default', 'asc')->get();
        }

        return view('livewire.admin.reports.generar-reporte-productos', compact('productos', 'almacens', 'categories', 'pricetypes', 'users', 'typereportes', 'years'));
    }

    public function updatedCategoryId($value)
    {
        $this->resetValidation();
        $this->reset(['subcategory_id', 'subcategories']);
        if (!empty($value)) {
            $this->subcategories = Category::with(['subcategories' => function ($query) {
                $query->orderBy('orden');
            }])->find($value)->subcategories;
        }
    }

    public function validateFilters()
    {
        if ($this->typereporte == FilterReportsEnum::TOP_TEN_PRODUCTOS->value || $this->typereporte == FilterReportsEnum::MIN_STOCK->value || $this->typereporte == FilterReportsEnum::PRODUCTOS_PROMOCIONADOS->value) {
            $this->reset(['producto_id', 'category_id', 'subcategory_id', 'subcategories', 'serie']);
        }

        if ($this->typereporte == FilterReportsEnum::PRODUCTOS_PROMOCIONADOS->value) {
            $this->reset(['almacen_id', 'user_id', 'producto_id', 'category_id', 'subcategory_id', 'subcategories',]);
        }

        if (!in_array($this->typereporte, [FilterReportsEnum::DEFAULT->value, FilterReportsEnum::CATALOGO_PRODUCTOS->value])) {
            $this->reset([
                'category_id',
                'subcategory_id',
                'subcategories',
            ]);
        }

        if (in_array($this->typereporte, [FilterReportsEnum::KARDEX_PRODUCTOS->value])) {
            $this->reset(['category_id', 'subcategory_id', 'user_id', 'subcategories', 'serie', 'almacen_id', 'sucursal_id']);
        }

        if ($this->typereporte == FilterReportsEnum::MIN_STOCK->value) {
            $this->resetExcept(['open', 'typereporte', 'viewreporte', 'almacen_id',]);
        }

        if (!empty($this->producto_id)) {
            $this->reset([
                'category_id',
                'subcategory_id',
                'subcategories',
            ]);
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
        if ($this->typereporte == FilterReportsEnum::TOP_TEN_PRODUCTOS->value) {
            return (new ProductoTopExport($dataFilters))->download();
        } elseif ($this->typereporte == FilterReportsEnum::KARDEX_PRODUCTOS->value) {
            return (new ProductoKardexExport($dataFilters))->download();
        } elseif ($this->typereporte == FilterReportsEnum::MIN_STOCK->value) {
            return (new ProductoMinstockExport($dataFilters))->download();
        } elseif ($this->typereporte == FilterReportsEnum::PRODUCTOS_PROMOCIONADOS->value) {
            return (new ProductoPromocionExport($dataFilters))->download();
        } else {
            return (new ProductoExport($dataFilters))->download();
            // admin.reports.productos.report-productos
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
        // dd($filters);
        return route('admin.reportes.productos') . '?' . http_build_query($filters);
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

<?php

namespace App\Http\Livewire\Admin\Mounthboxes;

use App\Models\Monthbox;
use App\Models\Sucursal;
use App\Rules\ValidateStartmonthbox;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ShowMounthboxes extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    protected $listeners = ['render'];

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'searchsucursal' => ['except' => '', 'as' => 'sucursal'],
    ];

    public $open = false;
    public $monthbox;

    public $search = '';
    public $searchsucursal = '';


    protected function rules()
    {
        return [
            'monthbox.name' => ['required', 'string', 'min:6'],
            'monthbox.startdate' => [
                'required', 'date_format:Y-m-d\TH:i',
                new ValidateStartmonthbox($this->monthbox->month)
            ],
            'monthbox.expiredate' => [
                'required', 'date_format:Y-m-d\TH:i',
                'after:startdate'
            ],
        ];
    }

    public function mount()
    {
        $this->monthbox = new Monthbox();
    }

    public function render()
    {

        $sucursalboxes = Sucursal::whereHas('monthboxes')->orderBy('name', 'asc')->get();
        $monthboxes = Monthbox::withTrashed()->withWhereHas('sucursal', function ($query) {
            $query->withTrashed();
            if ($this->searchsucursal !== '') {
                $query->where('id', $this->searchsucursal);
            } else {
                // $query->where('id', auth()->user()->sucursal_id);
            }
        });

        if (trim($this->search) !== '') {
            $monthboxes->where('month', $this->search);
        }

        $monthboxes =  $monthboxes->orderBy('month', 'desc')->paginate();

        return view('livewire.admin.mounthboxes.show-mounthboxes', compact('monthboxes', 'sucursalboxes'));
    }

    public function edit(Monthbox $monthbox)
    {
        $this->authorize('admin.cajas.mensuales.edit');
        $this->resetValidation();
        $this->resetExcept(['monthbox']);
        $this->monthbox = $monthbox;
        $this->monthbox->startdate = Carbon::parse($this->monthbox->startdate)->format('Y-m-d\TH:i');
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.cajas.mensuales.edit');
        $this->monthbox->name = trim($this->monthbox->name);
        $this->validate();
        $this->monthbox->save();
        $this->resetValidation();
        $this->resetExcept(['monthbox']);
        $this->dispatchBrowserEvent('updated');
    }

    public function usemonthbox(Monthbox $monthbox)
    {

        $this->authorize('admin.cajas.mensuales.edit');
        // if (Carbon::parse($this->month)->equalTo(Carbon::now()->format('Y-m'))) {
        // VALIDO SI EXISTEN CAJAS ACTIVAS EN SUCURSAL DE CAJA MENSUAL
        $exists = Monthbox::usando($monthbox->sucursal_id)->exists();
        if ($exists) {
            $mensaje = response()->json([
                'title' => 'No se pudo activar caja, ' . formatDate($monthbox->month, 'MMMM Y'),
                'text' => 'No se puede activar caja mensual, existen cajas activas en uso.'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {
            // VALIDAR SI CAJA SE ENCUENTRA DISPONIBLE 
            // (FECHA DE EXPIRACION MAYOR ACTUAL Y ESTADO REGISTRADO)
            if ($monthbox->isDisponible()) {
                // VALIDAR SI MES DE CAJA ES IGUAL AL MES ACTUAL
                if (Carbon::parse($monthbox->month)->equalTo(Carbon::now()->format('Y-m'))) {
                    $monthbox->status  =  Monthbox::EN_USO;
                    $monthbox->save();
                    $this->dispatchBrowserEvent('toast', toastJSON('Caja mensual aperturada correctamente'));
                } else {
                    $mensaje = response()->json([
                        'title' => 'Caja mensual no corresponde al mes actual',
                        'text' => 'Caja mensual de ' . formatDate($monthbox->month, 'MMMM Y') . ' no corresponde al mes actual ' . formatDate(now(), 'MMMM Y') . '.'
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                }
            } else {
                $mensaje = response()->json([
                    'title' => 'Caja mensual no diponible, ' . formatDate($monthbox->month, 'MMMM Y'),
                    'text' => 'Caja mensual no se encuentra disponible o la fecha de cierre ya caducó.'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
            }
        }
        // }
    }

    public function closemonthbox(Monthbox $monthbox)
    {

        $this->authorize('admin.cajas.mensuales.close');
        if (Carbon::parse($monthbox->expiredate)->lessThanOrEqualTo(Carbon::now()->format('Y-m-d H:i'))) {
            $monthbox->status  =  Monthbox::CERRADO;
            $monthbox->save();
            $this->dispatchBrowserEvent('toast', toastJSON('Caja mensual cerrada correctamente'));
        } else {
            $mensaje = response()->json([
                'title' => 'No se pudo cerrar caja, ' . formatDate($monthbox->month, 'MMMM Y'),
                'text' => 'Fecha de cierre es mayor a la fecha actual.'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        }
    }

    public function delete(Monthbox $monthbox)
    {

        $this->authorize('admin.cajas.mensuales.delete');
        if ($monthbox->isUsing()) {
            $mensaje = response()->json([
                'title' => 'La caja actualmente se encuentra activa',
                'text' => 'No se puede eliminar una caja mensual cuando se encuentra activa.'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if ($monthbox->cajamovimientos()->exists()) {
            $monthbox->delete();
        } else {
            $monthbox->forceDelete();
        }
        $this->dispatchBrowserEvent('deleted');
    }

    public function restoremonthbox($monthbox)
    {
        $this->authorize('admin.cajas.mensuales.restore');
        $monthbox = Monthbox::onlyTrashed()->find($monthbox);
        if ($monthbox->trashed()) {
            $monthbox->restore();
            $this->dispatchBrowserEvent('toast', toastJSON('Restaurado correctamente'));
        }
    }
}

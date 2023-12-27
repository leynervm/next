<?php

namespace App\Http\Livewire\Admin\Aperturas;

use App\Models\Caja;
use App\Models\Opencaja;
use App\Rules\ValidateCaja;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateApertura extends Component
{

    public $open = false;
    public $caja_id;
    public $startmount = 0;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'caja_id' => [
                'required', 'integer', 'min:1', 'exists:cajas,id',
                new ValidateCaja()
            ],
            'startmount' => [
                'required', 'numeric', 'min:0', 'decimal:0,2'
            ],
        ];
    }

    public function render()
    {

        $sucursals = auth()->user()->sucursalDefault()->select('sucursals.id')->pluck('sucursals.id');
        $cajas = Caja::whereDoesntHave('opencajas')->whereIn('sucursal_id', $sucursals)
            ->orWhereHas('opencajas', function ($query) {
                $query->whereNotNull('expiredate');
            })->whereIn('sucursal_id', $sucursals)->orderBy('name', 'asc')->get();

        return view('livewire.admin.aperturas.create-apertura', compact('cajas'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save()
    {
        $this->startmount = trim($this->startmount);
        $startdate = Carbon::now()->format('Y-m-d H:i:s');
        $this->validate();

        DB::beginTransaction();
        try {

            Opencaja::create([
                'startdate' => $startdate,
                'startmount' => $this->startmount,
                'user_id' =>  Auth::user()->id,
                'caja_id' => $this->caja_id,
                'status' => 0
            ]);

            DB::commit();
            $this->emitTo('admin.aperturas.show-aperturas', 'render');
            $this->dispatchBrowserEvent('created');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-apertura-select2');
    }
}

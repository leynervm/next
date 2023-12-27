<?php

namespace App\Http\Livewire\Admin\Sucursales;

use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use App\Models\Typecomprobante;
use App\Rules\ValidateSeriecomprobanteSucursal;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;

class ShowSeriecomprobantes extends Component
{

    public $sucursal;
    public $typecomprobante_id, $seriecomprobante_id, $default;
    public $seriecomprobantes = [];

    public function mount(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
    }

    public function render()
    {
        if (Module::isEnabled('Facturacion')) {
            $typecomprobantes = Typecomprobante::orderBy('code', 'asc')->paginate();
        } else {
            $typecomprobantes = Typecomprobante::Default()->paginate();
        }
        return view('livewire.admin.sucursales.show-seriecomprobantes', compact('typecomprobantes'));
    }

    public function updatedTypecomprobanteId($value)
    {
        $this->reset(['seriecomprobantes', 'seriecomprobante_id']);
        $this->resetValidation();
        if ($value) {
            $typecomprobante = Typecomprobante::with('seriecomprobantes')->find($value);
            $this->seriecomprobantes = $typecomprobante->seriecomprobantes;
            if (count($this->seriecomprobantes) == 1) {
                $this->seriecomprobante_id = $typecomprobante->seriecomprobantes->first()->id;
            }
        }
    }

    public function saveserie()
    {
        $this->default = $this->default == 1 ? 1 : 0;
        $this->validate([
            'typecomprobante_id' => [
                'required', 'integer', 'min:1', 'exists:typecomprobantes,id',
                new ValidateSeriecomprobanteSucursal($this->sucursal->id, 'typecomprobante_id', Seriecomprobante::find($this->seriecomprobante_id)->code ?? null)
            ],
            'seriecomprobante_id' => [
                'required', 'integer', 'min:1', 'exists:seriecomprobantes,id',
                new ValidateSeriecomprobanteSucursal($this->sucursal->id),
            ],
            'default' => [
                'nullable', 'integer', 'min:0', 'max:1',
            ],
        ]);

        try {
            DB::beginTransaction();
            if ($this->default) {
                $this->sucursal->seriecomprobantes()->each(function ($seriesucursal) {
                    $this->sucursal->seriecomprobantes()->updateExistingPivot($seriesucursal->id, [
                        'default' => 0
                    ]);
                });
            }

            $this->sucursal->seriecomprobantes()->attach(
                $this->seriecomprobante_id,
                ['default' => $this->default]
            );

            DB::commit();
            $this->reset(['typecomprobante_id', 'seriecomprobante_id', 'default', 'seriecomprobantes']);
            $this->resetValidation();
            $this->sucursal->refresh();
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Sucursal $sucursal, Seriecomprobante $seriecomprobante)
    {
        $sucursal->seriecomprobantes()->detach($seriecomprobante);
        $this->sucursal->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-show-seriecomprobante');
    }
}

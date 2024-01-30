<?php

namespace App\Http\Livewire\Admin\Sucursales;

use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use App\Models\Typecomprobante;
use App\Rules\ValidateSeriecomprobante;
use App\Rules\ValidateTypecomprobante;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;

class ShowSeriecomprobantes extends Component
{

    public $sucursal;
    public $typecomprobante_id, $seriecomprobante_id, $default;
    public $seriecomprobantes = [];

    protected function rules()
    {
        return [
            'typecomprobante_id' => [
                'required', 'integer', 'min:1', 'exists:typecomprobantes,id',
                new ValidateTypecomprobante($this->sucursal->id)
            ],
            'seriecomprobante_id' => [
                'required', 'integer', 'min:1', 'exists:seriecomprobantes,id',
                new ValidateSeriecomprobante, new ValidateSeriecomprobante($this->sucursal->id)
            ],
            'default' => [
                'nullable', 'integer', 'min:0', 'max:1',
            ],
        ];
    }

    public function mount(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
    }

    public function render()
    {
        if (Module::isEnabled('Facturacion')) {
            $typecomprobantes = Typecomprobante::orderBy('code', 'asc')->get();
        } else {
            $typecomprobantes = Typecomprobante::Default()->orderBy('code', 'asc')->get();
        }

        $sucursalcomprobantes = $this->sucursal->seriecomprobantes()
            ->withWherehas('typecomprobante', function ($query) {
                if (Module::isDisabled('Facturacion')) {
                    $query->default();
                }
            })->orderBy('code', 'desc')->orderByPivot('default', 'desc')->get();

        return view('livewire.admin.sucursales.show-seriecomprobantes', compact('typecomprobantes', 'sucursalcomprobantes'));
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
        $this->validate();
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

    public function setcomprobantedefault(Seriecomprobante $seriecomprobante)
    {
        try {
            DB::beginTransaction();

            $this->sucursal->seriecomprobantes()->each(function ($seriecomprobante) {
                $seriecomprobante->pivot->update(['default' => 0]);
            });
            $this->sucursal->seriecomprobantes()->updateExistingPivot($seriecomprobante->id, ['default' => 1]);
            DB::commit();
            $this->dispatchBrowserEvent('updated');
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
        $this->dispatchBrowserEvent('render-show-seriecomprobante');
    }
}

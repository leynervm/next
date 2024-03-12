<?php

namespace App\Http\Livewire\Modules\Facturacion\Guias;

use App\Helpers\GetClient;
use App\Models\Almacen;
use App\Models\Guia;
use App\Models\Kardex;
use App\Models\Transportdriver;
use App\Models\Transportvehiculo;
use App\Models\Tvitem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ShowGuia extends Component
{

    public $open = false;
    public $guia, $documentdriver, $namedriver, $lastname, $placa, $licencia;   

    public function mount(Guia $guia)
    {
        $this->guia = $guia;
    }

    public function render()
    {
        return view('livewire.modules.facturacion.guias.show-guia');
    }

    public function savevehiculo()
    {
        $this->placa = trim(mb_strtoupper($this->placa, "UTF-8"));
        $this->validate([
            'placa' => [
                'required', 'string', 'min:6', 'max:8',
                Rule::unique('transportvehiculos', 'placa')
                    ->where('guia_id', $this->guia->id)
            ],
        ]);

        DB::beginTransaction();
        try {
            $principal = $this->guia->transportvehiculos()->principal()->count() == 0 ? Transportvehiculo::PRINCIPAL : Transportvehiculo::SECUNDARIO;
            $this->guia->transportvehiculos()->create([
                'placa' => $this->placa,
                'principal' => $principal
            ]);
            DB::commit();
            $this->resetValidation();
            $this->reset(['placa']);
            $this->guia->refresh();
            $mensaje = response()->json([
                'title' => 'Vehículo de transporte registrado correctamente',
                'icon' => 'success'
            ]);
            $this->dispatchBrowserEvent('toast', $mensaje->getData());
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletevehiculo(Transportvehiculo $transportvehiculo)
    {
        if ($transportvehiculo->guia->codesunat == '0') {
            $mensaje = response()->json([
                'title' => 'Guía de remisión ya se encuentra enviada a SUNAT !',
                'text' => 'La guia de remision ' . $transportvehiculo->guia->seriecompleta . ' ya se encuentra notificada a SUNAT, no puede alterar el comprobante.'
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        } else {
            DB::beginTransaction();
            try {
                $transportvehiculo->delete();

                if ($this->guia->transportvehiculos()->count() > 0) {
                    if ($this->guia->transportvehiculos()->principal()->count() == 0) {
                        $this->guia->transportvehiculos()->first()->update([
                            'principal' => Transportvehiculo::PRINCIPAL
                        ]);
                    }
                }
                DB::commit();
                $this->guia->refresh();
                $this->dispatchBrowserEvent('deleted');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function savedriver()
    {
        $this->documentdriver = trim($this->documentdriver);
        $this->namedriver = trim(mb_strtoupper($this->namedriver, "UTF-8"));
        $this->lastname = trim(mb_strtoupper($this->lastname, "UTF-8"));
        $this->licencia = trim(mb_strtoupper($this->licencia, "UTF-8"));

        $this->validate([
            'documentdriver' => [
                'required', 'numeric', 'digits:8', 'regex:/^\d{8}$/',
                Rule::unique('transportdrivers', 'document')
                    ->where('guia_id', $this->guia->id)
            ],
            'namedriver' => ['required', 'string', 'min:6'],
            'lastname' => ['required', 'string', 'min:6'],
            'licencia' => ['required', 'string', 'min:9', 'max:10'],
        ]);

        DB::beginTransaction();
        try {
            $principal = $this->guia->transportdrivers()->principal()->count() == 0 ? Transportdriver::PRINCIPAL : Transportdriver::SECUNDARIO;
            $this->guia->transportdrivers()->create([
                'document' => $this->documentdriver,
                'name' => $this->namedriver,
                'lastname' => $this->lastname,
                'licencia' => $this->licencia,
                'principal' => $principal
            ]);
            DB::commit();
            $this->guia->refresh();
            $this->resetValidation();
            $this->resetExcept(['guia']);
            $mensaje = response()->json([
                'title' => 'Vehículo de transporte registrado correctamente',
                'icon' => 'success'
            ]);
            $this->dispatchBrowserEvent('toast', $mensaje->getData());
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletedriver(Transportdriver $driver)
    {
        if ($driver->guia->codesunat == '0') {
            $mensaje = response()->json([
                'title' => 'Guía de remisión ya se encuentra enviada a SUNAT !',
                'text' => 'La guia de remision ' . $driver->guia->seriecompleta . ' ya se encuentra notificada a SUNAT, no puede alterar el comprobante.'
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        } else {
            DB::beginTransaction();
            try {
                $driver->delete();
                if ($this->guia->transportdrivers()->count() > 0) {
                    if ($this->guia->transportdrivers()->principal()->count() == 0) {
                        $this->guia->transportdrivers()->first()->update([
                            'principal' => Transportdriver::PRINCIPAL
                        ]);
                    }
                }
                DB::commit();
                $this->guia->refresh();
                $this->dispatchBrowserEvent('deleted');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function generatecomprobante()
    {
    }

    public function deleteitem(Tvitem $tvitem)
    {
        DB::beginTransaction();
        try {
            if (count($tvitem->itemseries) > 0) {
                $tvitem->itemseries()->each(function ($itemserie) use ($tvitem) {
                    if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                        $itemserie->serie->dateout = null;
                        $itemserie->serie->status = 0;
                        $itemserie->serie->save();
                    }
                    $itemserie->delete();
                });
            }

            if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                $stock = $tvitem->producto->almacens->find($tvitem->almacen_id)->pivot->cantidad;
                $tvitem->producto->almacens()->updateExistingPivot($tvitem->almacen_id, [
                    'cantidad' => $stock + $tvitem->cantidad,
                ]);
            }

            if ($tvitem->kardex) {
                $tvitem->kardex->delete();
            }

            $tvitem->delete();
            DB::commit();
            $this->guia->refresh();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function confirmardevolucion(Tvitem $tvitem)
    {
        DB::beginTransaction();
        try {
            $stock = $tvitem->producto->almacens->find($tvitem->almacen_id)->pivot->cantidad;
            $tvitem->producto->almacens()->updateExistingPivot($tvitem->almacen_id, [
                'cantidad' => $stock + $tvitem->cantidad,
            ]);

            if (count($tvitem->itemseries) > 0) {
                $tvitem->itemseries()->each(function ($itemserie) {
                    $itemserie->serie->status = 0;
                    $itemserie->serie->dateout = null;
                    $itemserie->serie->save();
                });
            }

            if ($tvitem->kardex) {
                $tvitem->kardex->delete();
            }

            // $tvitem->cantidad = 0;
            $tvitem->alterstock = Almacen::NO_ALTERAR_STOCK;
            $tvitem->save();
            $this->guia->refresh();
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

    public function confirmarsalida(Tvitem $tvitem)
    {
        DB::beginTransaction();
        try {
            if ($tvitem->kardex) {
                $tvitem->kardex->detalle = Kardex::SALIDA_GUIA;
                $tvitem->kardex->save();
            }
            if (count($tvitem->itemseries) > 0) {
                $tvitem->itemseries()->each(function ($itemserie) {
                    $itemserie->serie->status = 2;
                    $itemserie->serie->save();
                });
            }
            $tvitem->alterstock = Almacen::DISMINUIR_STOCK;
            $tvitem->save();
            $this->guia->refresh();
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

    public function getDriver()
    {

        $this->documentdriver = trim($this->documentdriver);
        $this->validate([
            'documentdriver' => [
                'nullable',
                Rule::requiredIf($this->guia->modalidadtransporte->code == '02'),
                'numeric', 'regex:/^\d{8}(?:\d{3})?$/'
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->documentdriver);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['documentdriver', 'namedriver']);
                $this->namedriver = $response->getData()->name;
            } else {
                $this->resetValidation(['documentdriver']);
                $this->addError('documentdriver', $response->getData()->message);
            }
        } else {
            dd($response);
        }
    }
}

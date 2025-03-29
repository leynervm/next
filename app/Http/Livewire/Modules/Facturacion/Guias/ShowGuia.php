<?php

namespace App\Http\Livewire\Modules\Facturacion\Guias;

use App\Models\Almacen;
use App\Models\Guia;
use App\Models\Itemserie;
use App\Models\Kardex;
use App\Models\Serie;
use App\Models\Transportdriver;
use App\Models\Transportvehiculo;
use App\Models\Tvitem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
                'required',
                'string',
                'min:6',
                'max:8',
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
                'required',
                'numeric',
                'digits:8',
                'regex:/^\d{8}$/',
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

    public function generatecomprobante() {}

    public function deleteitem(Tvitem $tvitem)
    {
        DB::beginTransaction();
        try {
            $tvitem->load(['itemseries.serie', 'producto.almacens', 'kardexes']);
            foreach ($tvitem->itemseries as $itemserie) {
                if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                    $tvitem->updateSerieDisponible($itemserie->serie);
                }
                $itemserie->delete();
            }

            foreach ($tvitem->kardexes as $kardex) {
                $kardex->incrementOrDecrementStock($tvitem->producto, $tvitem);
                $kardex->delete();
            }

            // $tvitem->forceDelete();
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

    public function deleteitemserie(Itemserie $itemserie)
    {
        DB::beginTransaction();
        try {
            $itemserie->load(['seriable.kardexes', 'serie'  => function ($query) {
                $query->with(['producto.almacens']);
            }]);
            $tvitem = $itemserie->seriable;
            $almacen_id = $itemserie->serie->almacen_id;
            $kardex = $tvitem->kardexes->where('almacen_id', $almacen_id)->first();

            if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                $tvitem->updateSerieDisponible($itemserie->serie);
                if ($kardex) {
                    $itemserie->serie->producto->incrementarStockProducto($almacen_id, 1);
                }
            }

            if ($kardex) {
                $kardex->cantidad = $kardex->cantidad - 1;
                $kardex->newstock = $kardex->newstock - 1;
                if ($kardex->cantidad == 0) {
                    $kardex->delete();
                } else {
                    $kardex->save();
                }
            }
            $itemserie->delete();
            // $tvitem->cantidad = $tvitem->cantidad - 1;
            // if ($tvitem->cantidad == 0) {
            //     $tvitem->delete();
            // } else {
            //     $tvitem->save();
            // }
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

    public function deletekardex(Tvitem $tvitem, Kardex $kardex)
    {
        DB::beginTransaction();
        try {
            $tvitem->load(['producto.almacens']);
            if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                $tvitem->producto->incrementarStockProducto($kardex->almacen_id, $kardex->cantidad);
            }
            $kardex->delete();
            $tvitem->cantidad = $tvitem->cantidad - $kardex->cantidad;
            // if ($tvitem->cantidad == 0) {
            //     $tvitem->delete();
            // } else {
            //     $tvitem->save();
            // }
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
            $tvitem->load(['kardexes', 'producto', 'itemseries.serie']);
            $tvitem->alterstock = Almacen::NO_ALTERAR_STOCK;
            $tvitem->save();

            // if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
            if (count($tvitem->kardexes) > 0) {
                $tvitem->kardexes()->each(function ($kardex) use ($tvitem) {
                    $tvitem->producto->incrementarStockProducto($kardex->almacen_id, $kardex->cantidad);
                });
            }
            if (count($tvitem->itemseries) > 0) {
                $tvitem->itemseries()->each(function ($itemserie) use ($tvitem) {
                    $tvitem->updateSerieDisponible($itemserie->serie);
                });
            }
            // }
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
            $tvitem->load(['kardexes', 'itemseries.serie']);
            $tvitem->alterstock = Almacen::DISMINUIR_STOCK;
            $tvitem->save();
            if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                if (count($tvitem->itemseries) > 0) {
                    $tvitem->itemseries()->each(function ($itemserie) {
                        $itemserie->serie->status = Serie::SALIDA;
                        $itemserie->serie->save();
                    });
                }
            }
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
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/'
            ],
        ]);

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->documentdriver,
            'searchbd' => true,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                $this->namedriver = $cliente->name;
                if ($cliente->birthday) {
                    $this->dispatchBrowserEvent('birthday', $cliente->name);
                }
            } else {
                $this->namedriver = '';
                $this->addError('document', $cliente->error);
            }
        } else {
            $mensaje =  response()->json([
                'title' => 'Error:' . $response->status() . ' ' . $response->json(),
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }

    public function enviarsunat($id)
    {

        $guia =  Guia::with(['sucursal.empresa','tvitems'])->find($id);

        if ($guia && !$guia->isSendSunat()) {
            $response = $guia->enviarGuiaRemision();

            if ($response->success) {
                if (empty($response->mensaje)) {
                    $mensaje = response()->json([
                        'title' => $response->title,
                        'icon' => 'success'
                    ]);
                    $this->dispatchBrowserEvent('toast', $mensaje->getData());
                } else {
                    $mensaje = response()->json([
                        'title' => $response->title,
                        'text' => $response->mensaje,
                    ]);
                    $this->dispatchBrowserEvent('validation', $mensaje->getData());
                }
                $this->guia->refresh();
            } else {
                $mensaje = response()->json([
                    'title' => $response->title,
                    'text' => $response->mensaje,
                ]);
                $this->dispatchBrowserEvent('validation', $mensaje->getData());
            }
        } else {
            $mensaje = response()->json([
                'title' => 'GUÍA DE REMISIÓN ELECTRÓNICA ' . $guia->seriecompleta . ' YA FUÉ EMITIDO A SUNAT.',
                'text' => null,
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        }
    }
}

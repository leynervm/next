<?php

namespace App\Http\Livewire\Admin\Sucursales;

use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use App\Models\Typecomprobante;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;

class ShowSeriecomprobantes extends Component
{

    use AuthorizesRequests;

    public $sucursal, $seriecomprobante;
    public $typecomprobante_id, $serie;
    public $contador = 0;

    public function mount(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
        $this->seriecomprobante = new Seriecomprobante();
    }

    public function render()
    {
        if (Module::isEnabled('Facturacion')) {
            $typecomprobantes = Typecomprobante::orderBy('code', 'asc')->get();
        } else {
            $typecomprobantes = Typecomprobante::Default()->orderBy('code', 'asc')->get();
        }

        $seriecomprobantes = $this->sucursal->seriecomprobantes()
            ->withTrashed()->withWherehas('typecomprobante', function ($query) {
                if (Module::isDisabled('Facturacion')) {
                    $query->default();
                }
            })->orderBy('default', 'desc')->get();

        return view('livewire.admin.sucursales.show-seriecomprobantes', compact('typecomprobantes', 'seriecomprobantes'));
    }

    public function updatecontador(Seriecomprobante $seriecomprobante, $cantidad)
    {
        if ($cantidad >= 0) {
            $seriecomprobante->contador = $cantidad;
            $seriecomprobante->save();
            $this->dispatchBrowserEvent('updated');
        }
    }

    public function saveserie()
    {
        $this->authorize('admin.administracion.sucursales.seriecomprobantes.edit');
        $this->serie = trim($this->serie);
        $regex = '';
        $code = null;

        if ($this->typecomprobante_id) {
            $typecomprobante = Typecomprobante::find($this->typecomprobante_id);

            switch ($typecomprobante->code) {
                case '01':
                    $regex = 'regex:/^F[A-Z0-9][0-9]{2}$/';
                    $code = null;
                    break;
                case '03':
                    $regex = 'regex:/^B[A-Z0-9][0-9]{2}$/';
                    $code = null;
                    break;
                case '07':
                    $regex = $typecomprobante->referencia == '01' ? 'regex:/^[F][A-Z0-9][0-9]{2}$/' : 'regex:/^[B][A-Z0-9][0-9]{2}$/';
                    $code = $typecomprobante->referencia;
                    break;
                case '09':
                    $regex = 'regex:/^[TE][A-Z0-9][0-9]{2}$/';
                    $code = null;
                    break;
                case 'VT':
                    $regex = 'regex:/^V[A-Z0-9][0-9]{2}$/';
                    $code = null;
                    break;
                default:
                    $regex = '';
                    $code = null;
                    break;
            }
        }

        $this->validate([
            'typecomprobante_id' => [
                'required', 'integer', 'min:1', 'exists:typecomprobantes,id',
                new CampoUnique('seriecomprobantes', 'typecomprobante_id', $this->seriecomprobante->id ?? null, true, 'sucursal_id', $this->sucursal->id)
            ],
            'serie' => [
                'required', 'string', 'size:4', $regex,
                new CampoUnique('seriecomprobantes', 'serie', $this->seriecomprobante->id ?? null, true)
            ],
            'contador' => ['required', 'integer', 'min:0']
        ]);

        try {
            DB::beginTransaction();
            $exists = Seriecomprobante::onlyTrashed()->where('serie', $this->serie)->exists();
            if ($exists) {
                $mensaje = response()->json([
                    'title' => 'Serie ' . $this->serie . ' se encuentra deshabilitada.',
                    'text' => "Existen registros de comprobante con la misma serie en la base de datos.",
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
            $this->sucursal->seriecomprobantes()->create([
                'serie' => $this->serie,
                'contador' => $this->contador,
                'code' => $code,
                'typecomprobante_id' => $this->typecomprobante_id,
            ]);
            DB::commit();
            $this->reset(['typecomprobante_id', 'contador', 'serie']);
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

    public function delete(Seriecomprobante $seriecomprobante)
    {
        $this->authorize('admin.administracion.sucursales.seriecomprobantes.edit');
        $seriecomprobante->delete();
        $this->sucursal->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function setcomprobantedefault(Seriecomprobante $seriecomprobante)
    {
        $this->authorize('admin.administracion.sucursales.seriecomprobantes.edit');
        try {
            DB::beginTransaction();
            $this->sucursal->seriecomprobantes()->update(['default' => 0]);
            $seriecomprobante->default = 1;
            $seriecomprobante->save();
            DB::commit();
            $this->sucursal->refresh();
            $this->dispatchBrowserEvent('updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function restoreserie($seriecomprobante_id)
    {

        $this->authorize('admin.administracion.sucursales.seriecomprobantes.edit');
        $seriecomprobante = Seriecomprobante::onlyTrashed()->find($seriecomprobante_id);
        if ($seriecomprobante) {
            if (Seriecomprobante::where('serie', $seriecomprobante->serie)->exists()) {
                $mensaje = response()->json([
                    'title' => 'Ya existe comprobante con serie ' . $seriecomprobante->serie . '.',
                    'text' => "Existen registros de comprobante con la misma serie en la base de datos.",
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $seriecomprobante->default = 0;
            $seriecomprobante->restore();
            $this->resetValidation();
            $this->dispatchBrowserEvent('toast', toastJSON('Serie comprobante habilitado correctamente'));
        }
    }
}

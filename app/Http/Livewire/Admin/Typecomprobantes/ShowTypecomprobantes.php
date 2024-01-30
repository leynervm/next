<?php

namespace App\Http\Livewire\Admin\Typecomprobantes;

use App\Helpers\FormatoPersonalizado;
use App\Models\Seriecomprobante;
use App\Models\Typecomprobante;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Nwidart\Modules\Facades\Module;

class ShowTypecomprobantes extends Component
{

    use WithPagination;

    public $open = false;
    public $serie, $contador;
    public $typecomprobante, $seriecomprobante;

    protected $listeners = ['delete'];

    public function mount()
    {
        $this->typecomprobante = new Typecomprobante();
        $this->seriecomprobante = new Seriecomprobante();
    }

    public function render()
    {

        $typecomprobantes = Typecomprobante::orderBy('code', 'asc');
        if (!Module::isEnabled('Facturacion')) {
            $typecomprobantes->Default();
        }
        $typecomprobantes =  $typecomprobantes->paginate();

        return view('livewire.admin.typecomprobantes.show-typecomprobantes', compact('typecomprobantes'));
    }

    public function openmodal(Typecomprobante $typecomprobante)
    {
        $this->reset(['serie', 'seriecomprobante']);
        $this->typecomprobante = $typecomprobante;
        $this->seriecomprobante = new Seriecomprobante();
        $this->resetValidation();
        $this->open = true;
    }

    public function edit(Typecomprobante $typecomprobante, Seriecomprobante $seriecomprobante)
    {
        $this->reset(['serie', 'contador']);
        $this->resetValidation();
        $this->typecomprobante = $typecomprobante;
        $this->seriecomprobante = $seriecomprobante;
        $this->serie = $seriecomprobante->serie;
        $this->contador = $seriecomprobante->contador;
        $this->open = true;
    }

    public function save()
    {
        $this->serie = trim($this->serie);
        $this->validate([
            'serie' => [
                'required', 'string', 'size:4',
                new CampoUnique('seriecomprobantes', 'serie', $this->seriecomprobante->id ?? null, true)
            ],
            'contador' => ['required', 'integer', 'min:0']
        ]);

        try {
            DB::beginTransaction();
            switch ($this->typecomprobante->code) {
                case '07':
                    $code = substr($this->serie, 0, 1) == 'F' || substr($this->serie, 0, 1) == 'f' ? '01' : '03';
                    break;

                default:
                    $code = null;
                    break;
            }

            $this->typecomprobante->seriecomprobantes()->updateOrCreate(
                ['id' => $this->seriecomprobante->id ?? null],
                [
                    'serie' => $this->serie,
                    'contador' => $this->contador,
                    'code' => $code
                ]
            );

            DB::commit();
            $this->reset(['open', 'contador', 'serie']);
            $this->resetValidation();
            $this->dispatchBrowserEvent($this->seriecomprobante->id ? 'updated' : 'created');
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

        $ventas = $seriecomprobante->ventas()->count();
        $comprobantes = $seriecomprobante->comprobantes()->count();
        $guias = $seriecomprobante->guias()->count();

        $cadena = FormatoPersonalizado::extraerMensaje([
            'Ventas' => $ventas,
            'Comprobantes' => $comprobantes,
            'Guias_Remision' => $guias,
        ]);

        if ($ventas > 0 || $comprobantes > 0 || $guias > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar serie ' . $seriecomprobante->serie,
                'text' => "Existen registros vinculados $cadena, eliminarlo causaría un conflicto en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {
            $seriecomprobante->forceDelete();
            $this->dispatchBrowserEvent('deleted');
        }
    }
}

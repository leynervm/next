<?php

namespace Modules\Almacen\Http\Livewire\Garantias;

use App\Helpers\FormatoPersonalizado;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Typegarantia;
use Livewire\WithPagination;

class ShowTypeGarantias extends Component
{

    use WithPagination;

    public $open = false;
    public $typegarantia;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'typegarantia.name' => [
                'required',
                'min:3',
                'max:100',
                new Letter,
                new CampoUnique('typegarantias', 'name', $this->typegarantia->id, true),
            ],
            'typegarantia.timestring' => [
                'required',
                'min:3',
                'max:100',
                'string'
            ],
            'typegarantia.time' => [
                'required',
                'integer',
                'min:1'
            ]
        ];
    }

    public function mount()
    {
        $this->typegarantia = new Typegarantia();
    }

    public function render()
    {
        $typegarantias = Typegarantia::orderBy('name', 'asc')->paginate();
        return view('almacen::livewire.garantias.show-type-garantias', compact('typegarantias'));
    }

    public function edit(Typegarantia $typegarantia)
    {
        $this->typegarantia = $typegarantia;
        $this->open = true;
    }

    public function update()
    {
        $this->typegarantia->name = trim($this->typegarantia->name);
        $this->typegarantia->timestring = trim($this->typegarantia->timestring);
        $this->typegarantia->time = $this->typegarantia->time;
        $this->validate();
        $this->typegarantia->save();
        $this->reset(['open']);
    }

    public function delete(Typegarantia $typegarantia)
    {
        $productos = $typegarantia->garantiaproductos()->count();
        $cadena = FormatoPersonalizado::extraerMensaje([
            'Garantia_Productos' => $productos
        ]);

        if ($productos > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar tipo garantía, ' . $typegarantia->name,
                'text' => "Existen registros vinculados $cadena, eliminarlo causaría un conflicto en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {
            DB::beginTransaction();
            try {
                $typegarantia->deleteOrFail();
                DB::commit();
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
}

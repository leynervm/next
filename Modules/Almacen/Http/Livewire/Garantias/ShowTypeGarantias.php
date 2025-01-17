<?php

namespace Modules\Almacen\Http\Livewire\Garantias;

use App\Helpers\FormatoPersonalizado;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Typegarantia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;

class ShowTypeGarantias extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $open = false;
    public $typegarantia;

    protected $listeners = ['render'];

    protected $validationAttributes = [
        'typegarantia.time' => 'tiempo de garantía',
    ];

    protected function rules()
    {
        return [
            'typegarantia.name' => [
                'required', 'min:3', 'max:100', new CampoUnique('typegarantias', 'name', $this->typegarantia->id),
            ],
            'typegarantia.datecode' => [
                'required', 'string', 'min:2', 'max:4',
            ],
            'typegarantia.time' => [
                'required', 'integer', 'min:0', 'max:100'
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
        $this->authorize('admin.almacen.typegarantias.edit');
        $this->typegarantia = $typegarantia;
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.almacen.typegarantias.edit');
        $this->typegarantia->name = trim($this->typegarantia->name);
        $this->typegarantia->datecode = trim($this->typegarantia->datecode);
        $this->typegarantia->time = $this->typegarantia->time;
        $this->validate();
        $this->typegarantia->save();
        $this->reset(['open']);
        $this->dispatchBrowserEvent('updated');
    }

    public function delete(Typegarantia $typegarantia)
    {

        $this->authorize('admin.almacen.typegarantias.delete');
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

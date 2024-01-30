<?php

namespace App\Http\Livewire\Admin\Sucursales;

use App\Helpers\FormatoPersonalizado;
use App\Models\Sucursal;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use App\Models\Almacen;
use App\Models\User;

class ShowAlmacens extends Component
{

    public $open = false;
    public $sucursal, $almacen;
    public $name, $default;

    protected $listeners = ['delete'];

    protected function rules()
    {
        return [
            'almacen.name' => [
                'required', 'string', 'min:3', 'max:100',
                new CampoUnique('almacens', 'name', $this->almacen->id, true, 'sucursal_id', $this->sucursal->id)
            ],
            'almacen.default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('almacens', 'default', $this->almacen->id ?? null, true, 'sucursal_id', $this->sucursal->id),
            ],
            'almacen.sucursal_id' => [
                'required', 'integer', 'min:1', 'exists:sucursals,id'
            ]
        ];
    }

    public function mount(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
        $this->almacen = new Almacen();
    }

    public function render()
    {
        return view('livewire.admin.sucursales.show-almacens');
    }

    public function save()
    {

        $this->name = mb_strtoupper(trim($this->name), "UTF-8");
        $this->default = $this->default == 1 ? 1 : 0;
        $validateData = $this->validate([
            'name' => [
                'required', 'string', 'min:3', 'max:100',
                new CampoUnique('almacens', 'name', $this->almacen->id, true, 'sucursal_id', $this->sucursal->id)
            ],
            'default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('almacens', 'default', $this->almacen->id ?? null, true, 'sucursal_id', $this->sucursal->id),
            ],
            'sucursal.id' => [
                'required', 'integer', 'min:1', 'exists:sucursals,id'
            ]
        ]);

        DB::beginTransaction();
        try {

            if ($this->default) {
                $this->sucursal->almacens()->update([
                    'default' => 0
                ]);
            }

            $almacen = $this->sucursal->almacens()->withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))
                ->first();

            if ($almacen) {
                $almacen->default = $this->default;
                $almacen->restore();
            } else {
                $this->sucursal->almacens()->create($validateData);
            }

            DB::commit();
            $this->reset(['name', 'default']);
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

    public function edit(Almacen $almacen)
    {
        $this->resetValidation();
        $this->almacen = $almacen;
        $this->almacen->default = $this->almacen->default == 1 ? 1 : 0;
        $this->open = true;
    }

    public function update()
    {

        $this->almacen->default = $this->almacen->default == 1 ? 1 : 0;
        $this->validate();
        DB::beginTransaction();
        try {

            if ($this->almacen->default) {
                $this->sucursal->almacens()->update([
                    'default' => 0
                ]);
            }
            $this->almacen->save();
            DB::commit();
            $this->sucursal->refresh();
            $this->resetValidation();
            $this->reset(['open']);
            $this->dispatchBrowserEvent('updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Almacen $almacen)
    {
        $series = $almacen->series()->count();
        $productos = $almacen->productos()->count();
        $tvitems = $almacen->tvitems()->count();
        $compraitems = $almacen->compraitems()->count();
        // dd("Series => ", $series, "Productos => ", $productos, "TVitems => ", $tvitems, "Compraitems => ", $compraitems);

        $cadena = FormatoPersonalizado::extraerMensaje([
            'Series' => $series,
            'Productos' => $productos,
            'Items_de_ventas' => $tvitems,
            'Items_de_compras' => $compraitems
        ]);

        if ($series > 0 || $productos > 0 || $tvitems > 0 || $compraitems > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar el registro con nombre ' . $almacen->name,
                'text' => "Existen registros vinculados al almacén $cadena, eliminarlo causaría un conflicto en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {

            DB::beginTransaction();
            try {

                if ($almacen->users()->count() > 0) {
                    User::query()->where('almacen_id', $almacen->id)->update(['almacen_id' => null]);
                }

                $almacen->forceDelete();
                DB::commit();
                $this->sucursal->refresh();
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

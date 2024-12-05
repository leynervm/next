<?php

namespace App\Http\Livewire\Modules\Marketplace\Usersweb;

use App\Models\Direccion;
use App\Models\Ubigeo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowAdress extends Component
{

    public $open = false;
    public $direccion, $name, $referencia, $ubigeo_id;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:6'],
            'referencia' => ['nullable', 'string', 'min:3'],
            'ubigeo_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
        ];
    }

    protected $validationAttributes = [
        'name' => 'dirección'
    ];

    public function mount()
    {
        // $this->direccion = new Direccion();
    }

    public function render()
    {
        $direccions = auth()->user()->direccions()->with('ubigeo')
            ->orderByDesc('default')->orderBy('id', 'asc')->get();
        $ubigeos = Ubigeo::query()->select('id', 'region', 'provincia', 'distrito', 'ubigeo_reniec')
            ->orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        return view('livewire.modules.marketplace.usersweb.show-adress', compact('direccions', 'ubigeos'));
    }

    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            if (!empty($this->direccion)) {
                $this->direccion->name = $this->name;
                $this->direccion->referencia = $this->referencia;
                $this->direccion->ubigeo_id = $this->ubigeo_id;
                $this->direccion->save();
                $mensaje = response()->json([
                    'title' => 'DIRECCIÓN DE ENTREGA ACTUALIZADO !',
                    'icon' => 'success',
                    'timer' => 1500
                ])->getData();
            } else {
                $default = auth()->user()->direccions()->default()->count();
                auth()->user()->direccions()->create([
                    'name' => $this->name,
                    'referencia' => $this->referencia,
                    'default' => $default > 0 ? 0 : Direccion::DEFAULT,
                    'ubigeo_id' => $this->ubigeo_id
                ]);
                $mensaje = response()->json([
                    'title' => 'DIRECCIÓN DE ENTREGA REGISTRADO CORRECTAMENTE !',
                    'icon' => 'success',
                    'timer' => 1500
                ])->getData();
            }
            $this->dispatchBrowserEvent('toast', $mensaje);
            DB::commit();
            $this->resetValidation();
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->resetExcept(['open']);
        }
    }

    public function edit(Direccion $direccion)
    {
        $this->resetValidation();
        $this->resetExcept(['open']);
        $this->open = true;
        $this->direccion = $direccion;
        $this->name = $direccion->name;
        $this->referencia = $direccion->referencia;
        $this->ubigeo_id = $direccion->ubigeo_id;
    }

    public function delete($direccion_id)
    {
        Direccion::find($direccion_id)->delete();
        if (auth()->user()->direccions()->default()->count() == 0 && count(auth()->user()->direccions) > 0) {
            auth()->user()->direccions()->first()->update([
                'default' => Direccion::DEFAULT
            ]);
        }
        $this->resetValidation();
    }

    public function savedefault($direccion_id)
    {
        auth()->user()->direccions()->each(function ($item) use ($direccion_id) {
            $item->update([
                'default' => $item->id == $direccion_id ? Direccion::DEFAULT : 0,
            ]);
        });
        $mensaje = response()->json([
            'title' => 'DIRECCIÓN DE ENTREGA ACTUALIZADO !',
            'icon' => 'success',
            'timer' => 1500
        ])->getData();
        $this->dispatchBrowserEvent('toast', $mensaje);
        $this->resetValidation();
    }
}

<?php

namespace App\Http\Livewire\Modules\Administracion\Employers;

use App\Helpers\GetClient;
use App\Models\Area;
use App\Models\Employer;
use App\Models\Sucursal;
use App\Rules\CampoUnique;
use App\Rules\ValidateNacimiento;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateEmployer extends Component
{

    public $open = true;

    public $document, $name, $nacimiento, $sueldo,
        $horaingreso, $horasalida, $sexo, $areawork_id, $sucursal_id, $telefono;


    protected function rules()
    {
        return [
            'document' => [
                'required', 'numeric', 'digits:8', 'regex:/^\d{8}?$/',
                new CampoUnique('employers', 'document', null, true)
            ],
            'name' => [
                'required', 'string', 'min:6'
            ],
            'nacimiento' => [
                'required', 'date', new ValidateNacimiento(13)
            ],
            'telefono' => [
                'required', 'numeric', 'digits_between:7,9', 'regex:/^\d{7}(?:\d{2})?$/'
            ],
            'sexo' => [
                'required', 'string', 'min:1', 'max:1',  Rule::in(['M', 'F', 'E'])
            ],
            'sueldo' => [
                'required', 'numeric', 'min:0', 'gt:0', 'decimal:0,2'
            ],
            'horaingreso' => [
                'required', 'time'
            ],
            'horasalida' => [
                'required', 'time'
            ],
            'areawork_id' => [
                'required', 'integer', 'min:1', 'exists:areaworks,id'
            ],
            'sucursal_id' => [
                'required', 'integer', 'min:1', 'exists:sucursals,id'
            ],
        ];
    }

    public function render()
    {
        $sucursals = Sucursal::orderBy('name', 'asc')->get();
        $areaworks = Area::orderBy('name', 'asc')->get();
        return view('livewire.modules.administracion.employers.create-employer', compact('sucursals', 'areaworks'));
    }

    public function save()
    {
        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->telefono = trim($this->telefono);
        $this->sueldo = trim($this->sueldo);

        $validateData = $this->validate();
        $employer = Employer::create($validateData);
        $employer->telephones()->create([
            'phone' => $this->telefono,
        ]);
        $this->dispatchBrowserEvent('created');
    }

    public function getClient($event = true)
    {

        $this->document = trim($this->document);
        $this->validate([
            'document' => [
                'required', 'numeric', 'digits:8', 'regex:/^\d{8}?$/',
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['document', 'name']);
                $this->name = $response->getData()->name;

                if ($event) {
                    if ($response->getData()->birthday) {
                        $this->dispatchBrowserEvent('birthday', $response->getData()->name);
                    }
                }
            } else {
                $this->resetValidation(['document']);
                $this->addError('document', $response->getData()->message);
            }
        } else {
            $this->addError('document', 'Error de respuesta');
        }
    }
}

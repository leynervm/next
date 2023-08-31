<?php

namespace Modules\Soporte\Http\Livewire\Centerservices;

use App\Models\Marca;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use App\Rules\ValidateContacto;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Soporte\Entities\Centerservice;
use Modules\Soporte\Entities\Condition;

class CreateCenterservice extends Component
{

    public $mostrarcontacto = false;
    public $document, $name, $ubigeo_id, $direccion, $marca_id,
        $condition_id, $moneda_id, $email, $telefono;

    public $documentContact, $nameContact, $telefonoContact;

    protected function rules()
    {
        return [
            'document' => [
                'required', 'integer', 'numeric', 'unique:centerservices,document'
            ],
            'name' => [
                'required', 'string'
            ],
            'ubigeo_id' => [
                'nullable'
            ],
            'direccion' => [
                'required'
            ],
            'marca_id' => [
                'required', 'integer', 'exists:marcas,id', 'unique:centerservices,marca_id'
            ],
            'condition_id' => [
                'required', 'integer', 'exists:conditions,id'
            ],
            'moneda_id' => [
                'nullable', 'integer', 'exists:monedas,id'
            ],
            'email' => [
                'nullable', 'email'
            ],
            'telefono' => [
                'required'
            ],
            'documentContact' => [
                new ValidateContacto($this->document)
            ],
            'nameContact' => [
                new ValidateContacto($this->document)
            ],
            'telefonoContact' => [
                new ValidateContacto($this->document)
            ],
        ];
    }

    public function render()
    {
        $marcas = Marca::where('delete', 0)->orderBy('name', 'asc')->get();
        $conditions = Condition::where('delete', 0)->orderBy('name', 'asc')->get();
        return view('soporte::livewire.centerservices.create-centerservice', compact('marcas', 'conditions'));
    }

    public function updatedDocument($value)
    {
        if (strlen(trim($this->document)) == 11) {
            $this->mostrarcontacto = true;
        } else {
            $this->mostrarcontacto = false;
        }
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'equipamentrequire']);
        }
    }

    public function save()
    {
        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);
        $this->telefono = trim($this->telefono);
        $this->email = trim($this->email);
        $this->validate();

        $centerservice = Centerservice::where('document', $this->name)
            ->where('delete', 1)->first();

        if ($centerservice) {
            $centerservice->delete = 0;
            $centerservice->name = $this->name;
            $centerservice->ubigeo_id = $this->ubigeo_id;
            $centerservice->direccion = $this->direccion;
            $centerservice->marca_id = $this->marca_id;
            $centerservice->condition_id = $this->condition_id;
            $centerservice->moneda_id = $this->moneda_id;
            $centerservice->telefono = $this->telefono;
            $centerservice->email = $this->email;
            $centerservice->user_id = Auth::user()->id;
            $centerservice->date = now('America/Lima');
            $centerservice->save();
        } else {
            $centerservice = Centerservice::create([
                'date' => now('America/Lima'),
                'document' => $this->document,
                'name' => $this->name,
                'ubigeo_id' => $this->ubigeo_id,
                'direccion' => $this->direccion,
                'marca_id' => $this->marca_id,
                'condition_id' => $this->condition_id,
                'moneda_id' => $this->moneda_id,
                'email' => $this->email,
                'user' => Auth::user()->id,
            ]);
        }

        $centerservice->telephones()->create([
            'phone' => $this->telefono,
            'user_id' => Auth::user()->id,
        ]);

        if (strlen(trim($this->document)) == 11) {
            $contact = $centerservice->contacts()->create([
                'document' => $this->documentContact,
                'name' => $this->nameContact,
                'user_id' => Auth::user()->id,
            ]);

            $contact->telephones()->create([
                'phone' => $this->telefonoContact,
                'user_id' => Auth::user()->id,
            ]);
        }

        $this->reset();
        return redirect()->route('centerservices');
    }
}

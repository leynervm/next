<?php

namespace App\Http\Livewire\Admin\Proveedores;

use App\Models\Proveedor;
use App\Models\Proveedortype;
use App\Models\Ubigeo;
use App\Rules\CampoUnique;
use App\Rules\ValidateDocument;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateProveedor extends Component
{

    use AuthorizesRequests;

    public $document, $name, $direccion, $ubigeo_id,
        $proveedortype_id, $telefono, $email, $document2, $name2, $telefono2;

    public $addcontacto = false;

    protected function rules()
    {
        return [
            'document' => ['required', 'numeric', 'digits:11', 'regex:/^\d{11}$/', new ValidateDocument, new CampoUnique('proveedors', 'document', null, true)],
            'name' => ['required', 'string', 'min:3'],
            'direccion' => ['required', 'string', 'min:3'],
            'ubigeo_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'proveedortype_id' => ['required', 'integer', 'min:1', 'exists:proveedortypes,id'],
            'telefono' => ['nullable', 'numeric', 'digits:9', 'regex:/^\d{9}$/'],
            'email' => ['nullable', 'email', 'min:6'],
            'document2' => ['nullable', Rule::requiredIf($this->addcontacto), 'numeric', 'digits:8', 'regex:/^\d{8}$/'],
            'name2' => ['nullable', Rule::requiredIf($this->addcontacto), 'string', 'min:3'],
            'telefono2' => ['nullable', 'numeric', 'digits:9', 'regex:/^\d{9}$/'],
        ];
    }

    public function render()
    {
        $ubigeos = Ubigeo::query()->select('id', 'region', 'provincia', 'distrito', 'ubigeo_reniec')
            ->orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $proveedortypes = Proveedortype::orderBy('name', 'asc')->get();
        return view('livewire.admin.proveedores.create-proveedor', compact('ubigeos', 'proveedortypes'));
    }

    public function save($closemodal = true)
    {

        $this->authorize('admin.proveedores.create');
        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);
        $this->telefono = trim($this->telefono);
        $this->email = trim($this->email);
        $this->document2 = trim($this->document2);
        $this->name2 = trim($this->name2);
        $this->telefono2 = trim($this->telefono2);

        $validateData = $this->validate();
        try {

            DB::beginTransaction();
            $proveedor = Proveedor::create($validateData);

            if (trim($this->telefono) !== '') {
                $proveedor->telephones()->create([
                    'phone' => $this->telefono
                ]);
            }

            if ($this->addcontacto) {
                $representante = $proveedor->contacts()->create([
                    'document' => $this->document2,
                    'name' => $this->name2
                ]);

                if (trim($this->telefono2) !== '') {
                    $representante->telephone()->create([
                        'phone' => $this->telefono2
                    ]);
                }
            }
            DB::commit();
            $this->dispatchBrowserEvent('created');
            $this->resetValidation();
            if ($closemodal) {
                return redirect()->route('admin.proveedores');
            } else {
                $this->reset();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function searchclient($property, $name)
    {
        $this->authorize('admin.clientes.create');
        $this->resetValidation();
        $this->$property = trim($this->$property);

        if ($property == 'document') {
            $rules = [
                'document' => ['required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/', new ValidateDocument, new CampoUnique('clients', 'document', null, true)]
            ];
        } else {
            $rules = [
                'document2' => ['required', 'numeric', 'digits:8', 'regex:/^\d{8}$/']
            ];
        }

        $this->validate($rules);
        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->$property,
            'searchbd' => true,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                $this->$name = $cliente->name;
                if ($property == 'document') {
                    $this->telefono = $cliente->telefono;
                    $this->direccion = $cliente->direccion;
                    $this->ubigeo_id = $cliente->ubigeo_id;
                } else {
                    $this->telefono2 = $cliente->telefono;
                }
            } else {
                $this->$name = '';
                if ($property == 'document') {
                    $this->telefono = '';
                    $this->ubigeo_id = '';
                } else {
                    $this->telefono2 = '';
                }
                $this->addError($property, $cliente->error);
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
}

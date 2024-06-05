<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Helpers\GetClient;
use App\Models\Ubigeo;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;

class ConfiguracionInicial extends Component
{

    public $step = 1;

    public $empresa = [], $sucursals = [], $telephones = [];
    public $icono, $logo, $cert, $idcert, $idlogo, $idicono;

    public $document, $name, $direccion, $telefono, $ubigeo_id,
        $estado, $condicion, $email, $web, $montoadelanto;
    public $usuariosol, $clavesol, $passwordcert, $sendmode, $clientid, $clientsecret;

    public $validatemail;
    public $dominiocorreo;
    public $uselistprice = 0;
    public $usepricedolar = 0;
    public $tipocambio;
    public $viewpricedolar = 0;
    public $tipocambioauto = 0;
    public $igv = '18.00';


    public function render()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        return view('livewire.admin.empresas.configuracion-inicial', compact('ubigeos'));
    }

    public function validatestep($step)
    {
        // dd($step);

        if (Module::isDisabled('Facturacion')) {
            $this->sendmode = 0;
        }
        $this->uselistprice = $this->uselistprice == 1 ?  1 : 0;
        $this->usepricedolar = $this->usepricedolar == true ?  1 : 0;
        $this->viewpricedolar = $this->viewpricedolar == true ?  1 : 0;
        $this->tipocambioauto = $this->tipocambioauto == true ?  1 : 0;

        if ($this->usepricedolar == 0) {
            $this->usepricedolar = 0;
            $this->viewpricedolar = 0;
            $this->tipocambioauto = 0;
            $this->tipocambio = null;
        }

        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);

        if ($step == 1) {
            $this->validate([
                'document' => ['required', 'numeric', 'digits:11', 'regex:/^\d{11}$/'],
                'name' => ['required', 'string', 'min:3'],
                'direccion' => ['required', 'string', 'min:3'],
                'ubigeo_id' => ['required', 'integer', 'min:0', 'exists:ubigeos,id'],
                'estado' => ['required', 'string'],
                'condicion' => ['required', 'string'],
                'igv' => ['required', 'numeric', 'decimal:0,4', 'min:0'],
            ]);
        } elseif ($step == 2) {
            $this->validate([
                'uselistprice' => ['integer', 'min:0', 'max:1'],
                'usepricedolar' => ['integer', 'min:0', 'max:1'],
                'tipocambio' => ['nullable', 'required_if:usepricedolar,1', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
                'viewpricedolar' => ['integer', 'min:0', 'max:1'],
                'tipocambioauto' => ['integer', 'min:0', 'max:1'],
            ]);
        } else {
            $this->validate([]);
        }

        $this->empresa =  [
            'document' => $this->document,
            'name' => $this->name,
            'estado' => $this->estado,
            'condicion' => $this->condicion,
            'direccion' => $this->direccion,
            'email' => $this->email,
            'web' => $this->web,
            // 'icono' => $urlicono,
            'montoadelanto' => $this->montoadelanto,
            'uselistprice' => $this->uselistprice,
            'usepricedolar' => $this->usepricedolar,
            'viewpricedolar' => $this->viewpricedolar,
            'tipocambio' => $this->tipocambio,
            'tipocambioauto' => $this->tipocambioauto,
            'default' => 1,
            'igv' => $this->igv,
            'ubigeo_id' => $this->ubigeo_id,
            'sendmode' => $this->sendmode,
            'passwordcert' => $this->passwordcert,
            'usuariosol' => $this->usuariosol,
            'clavesol' => $this->clavesol,
            'clientid' => $this->clientid,
            'clientsecret' => $this->clientsecret,
            // 'cert' => $urlcert,
        ];


        $this->step++;
    }


    public function searchclient()
    {
        $this->document = trim($this->document);
        $this->validate([
            'document' => 'required|numeric|digits:11|regex:/^\d{11}$/'
        ]);

        $this->name = null;
        $this->direccion = null;
        $this->telefono = null;
        $this->ubigeo_id = null;
        $this->estado = null;
        $this->condicion = null;
        $this->resetValidation(['document', 'name', 'direccion', 'telefono', 'ubigeo_id', 'estado', 'condicion']);

        $http = new GetClient();
        $response = $http->getSunat($this->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->name = $response->getData()->name;
                $this->direccion = $response->getData()->direccion;
                $this->estado = $response->getData()->estado;
                $this->condicion = $response->getData()->condicion;
                $this->ubigeo_id = $response->getData()->ubigeo_id;
            } else {
                $this->addError('document', $response->getData()->message);
            }
        } else {
            $this->addError('document', 'Error al buscar cliente.');
        }
    }

    public function addphone()
    {
        $this->telefono = trim($this->telefono);
        $this->validate([
            'telefono' => ['required', 'numeric', 'digits:9', 'regex:/^\d{9}$/'],
        ]);
        $this->telephones[] = $this->telefono;
        $this->reset(['telefono']);
    }
}

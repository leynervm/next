<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Helpers\FormatoPersonalizado;
use App\Models\Empresa;
use App\Rules\ValidateFileKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConfiguracionFacturacion extends Component
{

    use WithFileUploads;

    public $empresa;
    public $cert, $idcert, $usuariosol, $clavesol, $passwordcert, $clientid, $clientsecret;

    protected function rules()
    {
        return [
            'empresa.sendmode' => ['required', 'integer', Rule::in([Empresa::PRUEBA, Empresa::PRODUCCION])],
            'empresa.afectacionigv' => ['required', 'integer', 'min:0', 'max:1'],
            'cert' => ['nullable', 'file', new ValidateFileKey("pfx")],

            'usuariosol' => $this->empresa->sendmode == Empresa::PRODUCCION ?
                ['string'] : ['nullable'],
            'clavesol' => $this->empresa->sendmode == Empresa::PRODUCCION ?
                ['string'] : ['nullable'],
            'passwordcert' => $this->empresa->sendmode == Empresa::PRODUCCION ?
                ['string', 'min:3'] : ['nullable'],
            'clientid' => $this->empresa->sendmode == Empresa::PRODUCCION ?
                ['string', 'min:3'] : ['nullable'],
            'clientsecret' => $this->empresa->sendmode == Empresa::PRODUCCION ?
                ['string', 'min:3'] : ['nullable'],

            'empresa.usuariosol' => ['required', 'string'],
            'empresa.clavesol' => ['required', 'string'],
            'empresa.passwordcert' => ['required', 'string', 'min:3'],
            'empresa.clientid' => ['required', 'string', 'min:3'],
            'empresa.clientsecret' => ['required', 'string', 'min:3'],
        ];
    }

    public function mount(Empresa $empresa)
    {
        $this->empresa = $empresa;
        $this->usuariosol = $this->empresa->usuariosol;
        $this->clavesol = $this->empresa->clavesol;
        $this->passwordcert =  $this->empresa->passwordcert;
        $this->clientid = $this->empresa->clientid;
        $this->clientsecret = $this->empresa->clientsecret;
    }

    public function render()
    {
        return view('livewire.admin.empresas.configuracion-facturacion');
    }

    public function update()
    {
        try {
            $this->validate();
            DB::beginTransaction();
            if (!Storage::directoryExists(storage_path('app/company/cert/'))) {
                Storage::disk('local')->makeDirectory('company/cert');
            }

            $urlcert = $this->empresa->cert ?? null;
            if ($this->cert) {
                $urlOld = 'company/cert/' . $this->empresa->cert;
                $extcert = FormatoPersonalizado::getExtencionFile($this->cert->getClientOriginalName());
                $urlcert = 'cert_' . $this->empresa->document . '.' . $extcert;
                if (Storage::disk('local')->exists($urlOld)) {
                    Storage::disk('local')->delete($urlOld);
                }
                $this->cert->storeAs('company/cert/', $urlcert, 'local');
            }

            if (empty($urlcert)) {
                $this->addError('cert', 'El campo certificado digital es obligatorio.');
                return false;
            }

            $this->empresa->cert = $urlcert;
            if ($this->empresa->isProduccion()) {
                $this->empresa->usuariosol = $this->usuariosol;
                $this->empresa->clavesol = $this->clavesol;
                $this->empresa->passwordcert = $this->passwordcert;
                $this->empresa->clientid = $this->clientid;
                $this->empresa->clientsecret = $this->clientsecret;
            }
            $this->empresa->save();
            DB::commit();
            $this->resetValidation();
            $this->reset(['cert', 'idcert']);
            $this->dispatchBrowserEvent('updated');
            $this->empresa->refresh();
            $this->idcert = rand();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletecert()
    {
        if ($this->empresa->cert) {
            if (Storage::disk('local')->exists('company/cert/' . $this->empresa->cert)) {
                Storage::disk('local')->delete('company/cert/' . $this->empresa->cert);
            }
        }

        $this->empresa->cert = null;
        $this->empresa->save();
        $this->idcert = rand();
        $this->empresa->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function clearCert()
    {
        $this->idcert = rand();
        $this->reset(['cert']);
    }

    public function updatedCert($file)
    {
        try {
            $this->resetValidation();
        } catch (\Exception $e) {
            $this->reset(['cert']);
            $this->addError('cert', $e->getMessage());
            return;
        }
    }

    public function updatedEmpresaSendmode($value)
    {
        $this->resetValidation();
        if ($value == Empresa::PRODUCCION) {
            $this->usuariosol = null;
            $this->clavesol = null;
            $this->passwordcert = null;
            $this->clientid = null;
            $this->clientsecret = null;
            // $this->empresa->usuariosol = $this->usuariosol;
            // $this->empresa->clavesol = $this->clavesol;
            // $this->empresa->passwordcert = $this->passwordcert;
            // $this->empresa->clientid = $this->clientid;
            // $this->empresa->clientsecret = $this->clientsecret;
        } else {
            $this->empresa->usuariosol = Empresa::USER_SOL_PRUEBA;
            $this->empresa->clavesol = Empresa::PASSWORD_SOL_PRUEBA;
            $this->empresa->clientid = Empresa::CLIENT_ID_GRE_PRUEBA;
            $this->empresa->clientsecret = Empresa::CLIENT_SECRET_GRE_PRUEBA;
            $this->empresa->passwordcert = Empresa::PASSWORD_CERT_PRUEBA;
        }
    }
}

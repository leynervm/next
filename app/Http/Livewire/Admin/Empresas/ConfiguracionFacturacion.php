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
    public $cert, $idcert;

    protected function rules()
    {
        return [
            'empresa.usuariosol' => [
                'nullable',
                Rule::requiredIf($this->empresa->isProduccion()),
                'string'
            ],
            'empresa.clavesol' => [
                'nullable',
                Rule::requiredIf($this->empresa->isProduccion()),
                'string'
            ],
            'empresa.passwordcert' => [
                'nullable',
                Rule::requiredIf($this->empresa->isProduccion()),
                'string',
                'min:3'
            ],
            'empresa.clientid' => [
                'nullable',
                Rule::requiredIf($this->empresa->isProduccion()),
                'string',
                'min:3'
            ],
            'empresa.clientsecret' => [
                'nullable',
                Rule::requiredIf($this->empresa->isProduccion()),
                'string',
                'min:3'
            ],
            'cert' => ['nullable', 'file', new ValidateFileKey("pfx")],
            'empresa.sendmode' => ['integer', 'min:0', 'max:1'],
            'empresa.afectacionigv' => ['integer', 'min:0', 'max:1'],
        ];
    }

    public function mount(Empresa $empresa)
    {
        $this->empresa = $empresa;
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
                $this->cert->storeAs('company/cert/', $urlcert, 'local');
                if (Storage::disk('local')->exists($urlOld)) {
                    Storage::disk('local')->delete($urlOld);
                }
            }

            if (empty($urlcert)) {
                $this->addError('cert', 'El campo certificado es obligatorio.');
                return false;
            }

            $this->empresa->cert = $urlcert;
            $this->empresa->usuariosol = $this->empresa->usuariosol;
            $this->empresa->clavesol = $this->empresa->clavesol;
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
                $this->empresa->cert = null;
                $this->empresa->save();
                $this->idcert = rand();
                $this->empresa->refresh();
                $this->dispatchBrowserEvent('deleted');
            }
        }
    }

    public function clearCert()
    {
        $this->idcert = rand();
        $this->reset(['cert']);
    }

    public function updatedCert($file)
    {
        try {
            $url = $file->temporaryUrl();
            $this->resetValidation();
        } catch (\Exception $e) {
            $this->reset(['cert']);
            $this->addError('cert', $e->getMessage());
            return;
        }
    }
}

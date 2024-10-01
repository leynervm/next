<?php

namespace App\Http\Livewire\Admin\Rangos;

use App\Imports\RangoImport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportarRangos extends Component
{

    use WithFileUploads, AuthorizesRequests;


    public $open = false;
    public $file, $identificador;
    public $failures = [];

    protected function rules()
    {
        return [
            'file' => ['required', 'mimes:xlsx,csv']
        ];
    }


    public function mount()
    {
        $this->identificador = rand();
    }

    public function render()
    {
        return view('livewire.admin.rangos.importar-rangos');
    }

    public function updatingOpen()
    {
        $this->authorize('admin.administracion.rangos.import');
        if (!$this->open) {
            $this->resetValidation();
            $this->reset(['file', 'failures']);
        }
    }

    public function import()
    {
        $this->authorize('admin.administracion.rangos.import');
        $this->reset(['failures']);
        $this->validate();

        try {

            // Excel::import(new RangoImport, $this->file);
            $import = new RangoImport();
            $import->import($this->file);
            $this->resetValidation();
            $this->emitTo('admin.rangos.show-rangos', 'render');
            $this->dispatchBrowserEvent('toast', toastJSON('Importado correctamente'));
            $this->reset(['open', 'file', 'failures']);
        } catch (ValidationException $e) {
            $failures = $e->failures() ?? [];
            foreach ($failures as $failure) {
                $this->failures[] = [
                    'attribute' => $failure->attribute() ?? '',
                    'row' => $failure->row() ?? '',
                    'errors' => $failure->errors() ?? [],
                    'values' => $failure->values() ?? [],
                ];
            }
        } catch (\Throwable $e) {
            $mensaje =  response()->json([
                'title' => 'ERROR AL IMPORTAR LISTA DE RANGOS !',
                'text' => $e->getMessage()
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
            throw $e;
        }
    }

    public function resetFile()
    {
        $this->authorize('admin.administracion.rangos.import');
        $this->reset(['file', 'failures']);
    }


    public function updatedFile()
    {
        try {
            $this->reset(['failures']);
            $this->validate();
        } catch (\Exception $e) {
            $this->reset(['file', 'failures']);
            $this->addError('file', $e->getMessage());
        }
    }
}

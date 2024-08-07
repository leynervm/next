<?php

namespace App\Http\Livewire\Modules\Almacen\Productos;

use App\Imports\ProductoImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportarProductos extends Component
{

    use WithFileUploads;

    public $open = false;
    public $file;
    public $identificador;
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
        return view('livewire.modules.almacen.productos.importar-productos');
    }

    public function updatingOpen()
    {
        if (!$this->open) {
            $this->resetValidation();
            $this->reset(['file', 'failures']);
        }
    }

    public function import()
    {
        // $this->reset(['failures']);
        $this->validate();

        try {
            $import = new ProductoImport();
            $import->import($this->file);
            $this->resetValidation();
            $this->emitTo('modules.almacen.productos.show-productos', 'render');
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
                'title' => 'ERROR AL IMPORTAR LISTA DE PRODUCTOS !',
                'text' => $e->getMessage()
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
            throw $e;
        }
    }

    public function resetFile()
    {
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

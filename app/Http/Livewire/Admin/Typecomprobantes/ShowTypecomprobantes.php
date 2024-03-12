<?php

namespace App\Http\Livewire\Admin\Typecomprobantes;

use App\Helpers\FormatoPersonalizado;
use App\Models\Seriecomprobante;
use App\Models\Typecomprobante;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Nwidart\Modules\Facades\Module;

class ShowTypecomprobantes extends Component
{

    use WithPagination;
    use AuthorizesRequests;


    public $open = false;
    public $serie, $contador;
    public $typecomprobante, $seriecomprobante;

    protected $listeners = ['delete'];

    public function mount()
    {
        $this->typecomprobante = new Typecomprobante();
        $this->seriecomprobante = new Seriecomprobante();
    }

    public function render()
    {

        $typecomprobantes = Typecomprobante::orderBy('code', 'asc');
        if (!Module::isEnabled('Facturacion')) {
            $typecomprobantes->Default();
        }
        $typecomprobantes =  $typecomprobantes->paginate();

        return view('livewire.admin.typecomprobantes.show-typecomprobantes', compact('typecomprobantes'));
    }
}

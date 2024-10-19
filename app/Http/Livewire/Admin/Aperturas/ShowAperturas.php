<?php

namespace App\Http\Livewire\Admin\Aperturas;

use App\Enums\MovimientosEnum;
use App\Models\Box;
use App\Models\Employer;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Models\Sucursal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Nwidart\Modules\Facades\Module;

class ShowAperturas extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $open = false;
    public $openbox, $employer;
    public $date = '', $dateto = '', $searchuser = '', $searchbox = '',
        $searchsucursal = '', $searchmonthbox = '';
    protected $listeners = ['render'];
    protected $queryString = [
        'date' => [
            'except' => '',
            'as' => 'fecha-apertura'
        ],
        'dateto' => [
            'except' => '',
            'as' => 'hasta-fecha-apertura'
        ],
        'searchsucursal' => [
            'except' => '',
            'as' => 'sucursal'
        ],
        'searchuser' => [
            'except' => '',
            'as' => 'usuario'
        ],
        'searchbox' => [
            'except' => '',
            'as' => 'caja'
        ],
        'searchmonthbox' => [
            'except' => '',
            'as' => 'caja-mensual'
        ]
    ];

    protected function messages()
    {
        return [
            'openbox.expiredate.before_or_equal' => ":attribute máximo debe ser una hora después del turno asignado."
        ];
    }

    protected $validationAttributes = [
        'openbox.expiredate' => ' fecha de cierre',
    ];

    protected function rules()
    {
        return [
            'openbox.apertura' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'openbox.expiredate' => [
                'required',
                'date',
                'after:' . Carbon::parse($this->openbox->startdate)->format('Y-m-d h:i'),
                !empty($this->employer) && !auth()->user()->isAdmin() ? 'before_or_equal:' . Carbon::parse($this->employer->turno->horasalida)->addHour()->format('Y-m-d H:i') : '',
            ],
        ];
    }

    public function mount()
    {
        $this->openbox = new Openbox();
    }

    public function render()
    {

        $openboxes = Openbox::with(['user', 'monthbox', 'cajamovimiento.moneda', 'cajamovimientos' => function ($query) {
            $query->with('moneda')->select(
                'openbox_id',
                'moneda_id',
                // DB::raw("SUM(CASE WHEN typemovement = 'INGRESO' THEN totalamount ELSE 0 END) as total_ingresos"),
                // DB::raw("SUM(CASE WHEN typemovement = 'EGRESO' THEN totalamount ELSE 0 END) as total_egresos"),
                DB::raw("COALESCE(SUM(CASE WHEN typemovement = '" . MovimientosEnum::INGRESO->value . "' THEN totalamount ELSE -totalamount END), 0) as diferencia")
            )->groupBy('openbox_id', 'moneda_id')->orderBy('moneda_id', 'asc');
        }])->withWhereHas('sucursal', function ($query) {
            if (auth()->user()->isAdmin()) {
                if (trim($this->searchsucursal !== '')) {
                    $query->where('id', $this->searchsucursal);
                }
            } else {
                $query->where('id', auth()->user()->sucursal_id);
            }
        })->withWhereHas('box', function ($query) {
            if (trim($this->searchbox !== '')) {
                $query->where('id', $this->searchbox);
            }
        });

        if ($this->date) {
            if ($this->dateto) {
                $openboxes->whereDateBetween('startdate', $this->date, $this->dateto);
            } else {
                $openboxes->whereDate('startdate', $this->date);
            }
        }

        if ($this->searchsucursal !== '') {
            $openboxes->where('sucursal_id', $this->searchsucursal);
        }

        if ($this->searchuser !== '') {
            $openboxes->where('user_id', $this->searchuser);
        }

        if ($this->searchmonthbox !== '') {
            $openboxes->where('monthbox_id', $this->searchmonthbox);
        }
        $openboxes = $openboxes->orderBy('startdate', 'desc')->paginate();

        $users = User::whereHas('openboxes', function ($query) {
            $query->whereHas('cajamovimientos');
        })->get();
        $boxes = Box::whereHas('openboxes', function ($query) {
            $query->whereHas('cajamovimientos');
        })->get();
        $monthboxes = Monthbox::whereHas('openboxes', function ($query) {
            $query->whereHas('cajamovimientos');
        })->get();
        $sucursals = Sucursal::whereHas('openboxes', function ($query) {
            $query->whereHas('cajamovimientos');
        })->get();

        return view('livewire.admin.aperturas.show-aperturas', compact('openboxes', 'sucursals', 'users', 'boxes', 'monthboxes'));
    }

    public function updatedDate()
    {
        $this->resetPage();
    }

    public function updatedDateto()
    {
        $this->resetPage();
    }

    public function updatedSearchuser()
    {
        $this->resetPage();
    }

    public function updatedSearchbox()
    {
        $this->resetPage();
    }

    public function updatedSearchsucursal()
    {
        $this->resetPage();
    }

    public function updatedSearchmonthbox()
    {
        $this->resetPage();
    }

    public function edit(Openbox $openbox)
    {
        $this->authorize('admin.cajas.aperturas.edit');
        $this->resetValidation();
        $this->openbox = $openbox;
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.cajas.aperturas.edit');

        if (Module::isEnabled('Employer')) {
            $this->openbox->load(['user' => function ($query) {
                $query->with(['employer.turno']);
            }]);
            $this->employer = $this->openbox->user->employer;
        }
        $this->validate();
        $this->openbox->save();
        $this->resetValidation();
        $this->open = false;
        $this->dispatchBrowserEvent('updated');
    }

    public function close(Openbox $openbox)
    {
        try {
            $this->authorize('admin.cajas.aperturas.close');
            $this->openbox = $openbox;
            $this->openbox->closedate = now("America/Lima");
            $this->openbox->status = 1;
            $this->openbox->save();
            $this->openbox->box->user_id = null;
            $this->openbox->box->update();
            DB::commit();
            $this->dispatchBrowserEvent('updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

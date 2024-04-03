<?php

namespace App\Http\Livewire\Admin\Mounthboxes;

use App\Models\Monthbox;
use App\Models\Sucursal;
use App\Rules\ValidateMonthbox;
use App\Rules\ValidateStartmonthbox;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateMounthbox extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $seleccionartodo = false;
    public $month, $name, $startdate, $expiredate, $status, $sucursal_id;
    public $sucursalselected = [];

    protected function rules()
    {
        return [
            'month' => [
                'required', 'date_format:Y-m', 'after_or_equal:' . now('America/Lima')->format('Y-m'),
                new ValidateMonthbox($this->sucursalselected)
            ],
            'name' => ['required', 'string', 'min:6'],
            'startdate' => [
                'required', 'date_format:Y-m-d\TH:i',
                new ValidateStartmonthbox($this->month)
            ],
            'expiredate' => [
                'required', 'date_format:Y-m-d\TH:i',
                'after:startdate', 'after:' . now('America/Lima')->format('Y-m-d H:i'),
            ],
            'status' => ['required', 'integer', 'min:0', 'max:1'],
            'sucursalselected' => ['required', 'array', 'min:1', 'exists:sucursals,id'],
            // 'sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
        ];
    }

    public function render()
    {
        if (auth()->user()->isAdmin()) {
            $sucursals = Sucursal::orderBy('name', 'asc')->get();
        } else {
            $sucursals = Sucursal::where('id', auth()->user()->sucursal_id)->orderBy('name', 'asc')->get();
        }
        return view('livewire.admin.mounthboxes.create-mounthbox', compact('sucursals'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.cajas.mensuales.create');
            $this->reset();
            $this->resetValidation();

            $primerDia = Carbon::now()->firstOfMonth()->format('Y-m-d\TH:i');
            $ultimoDia = Carbon::now()->lastOfMonth()
                ->setHour(23)->setMinute(59)->setSecond(59)->format('Y-m-d\TH:i');

            $this->startdate = $primerDia;
            $this->expiredate = $ultimoDia;
            $this->month = now()->format('Y-m');
            $this->name = formatDate(now()->format('Y-m'), 'MMMM Y');
        }
    }

    public function updatedMonth($value)
    {
        if ($value) {
            // dd($value);
            $primerDia = Carbon::parse($value)->firstOfMonth()->format('Y-m-d\TH:i');
            $ultimoDia = Carbon::parse($value)->lastOfMonth()
                ->setHour(23)->setMinute(59)->setSecond(59)->format('Y-m-d\TH:i');

            $this->startdate = $primerDia;
            $this->expiredate = $ultimoDia;
            $this->month = Carbon::parse($value)->format('Y-m');
            $this->name = formatDate(Carbon::parse($value)->format('Y-m'), 'MMMM Y');
        }
    }

    public function save()
    {

        $this->authorize('admin.cajas.mensuales.create');
        $this->name = trim($this->name);
        $this->status = 0;
        if (Carbon::parse($this->month)->equalTo(Carbon::now()->format('Y-m'))) {
            $exists = Monthbox::usando(auth()->user()->sucursal_id)->where('sucursal_id', $this->sucursal_id)->exists();
            if (!$exists) {
                $this->status = 1;
            }
        }

        $validateData = $this->validate();
        DB::beginTransaction();
        try {

            foreach ($this->sucursalselected as $sucursal) {
                $monthbox = Monthbox::onlyTrashed()->where('month', $this->month)
                    ->where('sucursal_id', $sucursal)->first();

                if ($monthbox) {
                    $monthbox->status = $this->status;
                    $monthbox->startdate = $this->startdate;
                    $monthbox->expiredate = $this->expiredate;
                    $monthbox->name = $this->name;
                    $monthbox->restore();
                } else {
                    // Monthbox::create($validateData);
                    Monthbox::create([
                        'name' => $this->name,
                        'month' => $this->month,
                        'startdate' => $this->startdate,
                        'expiredate' => $this->expiredate,
                        'sucursal_id' => $sucursal,
                        'status' => $this->status,
                    ]);
                }
            }

            DB::commit();
            $this->emitTo('admin.mounthboxes.show-mounthboxes', 'render');
            $this->resetValidation();
            $this->reset();
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function allsucursals($value)
    {
        if ($value) {
            $this->sucursalselected = Sucursal::all()->pluck('id')->toArray();
        } else {
            $this->reset(['sucursalselected']);
        }
    }
}

<?php

namespace App\Http\Livewire\Admin\Aperturas;

use App\Models\Openbox;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAperturas extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $open = false;
    public $openbox;
    public $searchcaja = '';
    public $searchsucursal = '';

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'openbox.apertura' => [
                'required', 'numeric', 'min:0', 'decimal:0,4'
            ],
            'openbox.expiredate' => [
                'required', 'date', 'after:' . Carbon::parse($this->openbox->startdate)->format('Y-m-d h:i'),
            ],
        ];
    }

    public function mount()
    {
        $this->openbox = new Openbox();
    }

    public function render()
    {
        $openboxes = Openbox::withWhereHas('box', function ($query) {
            $query->withTrashed()->withWhereHas('sucursal', function ($query) {
                $query->withTrashed();
                if (trim($this->searchsucursal !== '')) {
                    $query->where('id', $this->searchsucursal);
                } else {
                    $query->where('id', auth()->user()->sucursal_id);
                }
            });

            if (trim($this->searchcaja !== '')) {
                $query->where('id', $this->searchcaja);
            }
        })->orderBy('startdate', 'desc')->paginate();
        return view('livewire.admin.aperturas.show-aperturas', compact('openboxes'));
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

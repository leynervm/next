<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ShowUsers extends Component
{

    use WithPagination;

    public $open = false;

    public function render()
    {
        $users = User::with('sucursals')->orderBy('name', 'asc')->paginate();
        return view('livewire.admin.users.show-users', compact('users'));
    }
}

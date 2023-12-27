<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{

    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::all();
        $permisos = Permission::all();
        $sucursales = Sucursal::all();
        return view('admin.users.create', compact('roles', 'sucursales'));
    }

    public function store(Request $request)
    {

        $validateData = $request->validate([
            'name' => ['required', 'min:3', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role_id' => ['nullable', 'integer', 'min:1', 'exists:roles,id'],
            'sucursals' => ['nullable', 'array', 'min:1', 'exists:sucursals,id']
        ]);

        try {
            DB::beginTransaction();
            // $password = bcrypt($request->password);
            $user = User::create($validateData);

            $sucursals = [];
            if ($request->sucursals) {
                if (count($request->sucursals)) {
                    foreach ($request->sucursals as $key) {
                        $sucursal = Sucursal::with('almacens')->find($key);
                        $sucursals[$key] = [
                            'default' => count($request->sucursals) == 1 ? 1 : $sucursal->default,
                            'almacen_id' => count($sucursal->almacenDefault) ? $sucursal->almacenDefault->first()->id : null
                        ];
                    }
                }
            }

            $user->sucursals()->sync($sucursals);
            DB::commit();
            return redirect()->route('admin.users')->with('mensaje', 'nuevo usuario registrado correctamente !');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function edit(User $user)
    {

        $roles = Role::all();
        $permisos = Permission::all();
        $sucursales = Sucursal::all();
        session(['redirectpage_url' => url()->previous()]);
        return view('admin.users.show', compact('user', 'roles', 'sucursales'));
    }

    public function update(Request $request, User $user)
    {

        $validateData = $request->validate([
            'name' => ['required', 'min:3', 'string'],
            'email' => "required|unique:users,email,$user->id",
            // 'password' => ['required', 'min:8', 'confirmed'],
            'role_id' => ['nullable', 'integer', 'min:1', 'exists:roles,id'],
            'sucursals' => ['nullable', 'array', 'min:1', 'exists:sucursals,id']
        ]);

        try {
            DB::beginTransaction();
            $user->update($validateData);

            $sucursals = [];

            if ($request->sucursals) {
                if (count($request->sucursals)) {
                    foreach ($request->sucursals as $key) {

                        $arrayId = $user->sucursals->pluck('id')->toArray();
                        $sucursal = Sucursal::with('almacens')->find($key);

                        if (count($arrayId) > 0) {
                            if (in_array($key, $arrayId)) {
                                $sucursals[$key] = $key;
                            } else {
                                $sucursals[$key] = [
                                    'default' => count($request->sucursals) == 1 ? 1 : $sucursal->default,
                                    'almacen_id' => count($sucursal->almacenDefault) ? $sucursal->almacenDefault->first()->id : null
                                ];
                            }
                        } else {
                            $sucursals[$key] = [
                                'default' => count($request->sucursals) == 1 ? 1 : $sucursal->default,
                                'almacen_id' => count($sucursal->almacenDefault) ? $sucursal->almacenDefault->first()->id : null
                            ];
                        }
                    }
                }
            }
// dd($sucursals);
            $user->sucursals()->sync($sucursals);
            DB::commit();
            $redirectpage_url = session('redirectpage_url');
            if ($redirectpage_url) {
                session()->forget($redirectpage_url);
                return redirect()->to($redirectpage_url)->with('message', 'Usuario actualizado correctamente !');;
            } else {
                return redirect()->route('admin.users')->with('message', 'Usuario actualizado correctamente !');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(User $user)
    {
        // $user->delete();
        // return redirect()->route('admin.users')->with('mensaje', 'nuevo usuario registrado correctamente !');
    }

    public function permisos()
    {
        return view('admin.permisos.index');
    }

    public function roles()
    {
        return view('admin.roles.index');
    }

    public function history()
    {
        return view('admin.historial.logins');
    }

    public function historypassword()
    {
        return view('admin.historial.passwords');
    }
}

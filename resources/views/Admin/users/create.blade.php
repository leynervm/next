<x-app-layout>
    <form
        class="w-full mx-auto flex flex-col gap-8 lg:max-w-4xl xl:max-w-7xl py-10 lg:px-10 animate__animated animate__fadeIn animate__faster"
        method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @method('post')

        <x-form-card titulo="PERFIL USUARIO" widthBefore="before:w-24"
            subtitulo="Complete todos los campos para registrar un nuevo usuario.">
            <div class="w-full grid grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full col-span-2">
                    <x-label value="Nombres :" />
                    <x-input class="block w-full" name="name" value="{{ old('name') }}"
                        placeholder="Nombres del usuario..." />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full col-span-2 sm:col-span-1">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" name="email" value="{{ old('email') }}"
                        placeholder="Correo del usuario..." />
                    <x-jet-input-error for="email" />
                </div>
                <div class="w-full col-span-2 sm:col-span-1">
                    <x-label value="Rol :" />
                    <x-select id="role_id" name="role_id" class="w-full block">
                        <x-slot name="options">
                            @if (count($roles))
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="role_id" />
                </div>
                <div class="w-full col-span-2 sm:col-span-1">
                    <x-label value="Contraseña :" />
                    <x-input type="password" class="block w-full" name="password" value="{{ old('password') }}"
                        placeholder="Ingrese contraseña..." />
                    <x-jet-input-error for="password" />
                </div>
                <div class="w-full col-span-2 sm:col-span-1">
                    <x-label value="Confirmar contraseña :" />
                    <x-input type="password" class="block w-full" name="password_confirmation"
                        value="{{ old('password_confirmation') }}" placeholder="Confirmar contraseña..." />
                    <x-jet-input-error for="password_confirmation" />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="SUCURSALES Y ALMACÉN" widthBefore="before:w-32"
            subtitulo="Asignar sucursales y alamacenes para administrar por el usuario.">
            <div class="w-full">
                @if (count($sucursales))
                    <div class="w-full flex flex-wrap gap-2">
                        @foreach ($sucursales as $item)
                            <div class="inline-flex">
                                <x-button-checkbox class="tracking-widest" for="sucursal_{{ $item->name }}"
                                    :text="$item->name">
                                    <input class="sr-only peer" type="checkbox" id="sucursal_{{ $item->name }}"
                                        name="sucursals[]" value="{{ $item->id }}"
                                        {{ in_array($item->id, old('sucursals', [])) ? 'checked' : '' }} />
                                </x-button-checkbox>
                            </div>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="sucursals" />
            </div>
        </x-form-card>

        <x-form-card titulo="PERMISOS ADICIONALES" widthBefore="before:w-32"
            subtitulo="Agregar permisos adicionales al rol asignado al usuario.">
        </x-form-card>


        <div class="w-full flex pt-4 justify-end">
            <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                {{ __('REGISTRAR') }}
            </x-button>
        </div>

    </form>
</x-app-layout>

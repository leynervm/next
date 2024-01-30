<div>
    <form class="w-full flex flex-col gap-8" wire:submit.prevent="update">
        <x-form-card titulo="PERFIL USUARIO">
            <div class="w-full grid grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full col-span-2">
                    <x-label value="Nombres :" />
                    <x-input class="block w-full" name="name" wire:model.defer="user.name"
                        placeholder="Nombres del usuario..." />
                    <x-jet-input-error for="user.name" />
                </div>
                <div class="w-full col-span-2 sm:col-span-1">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" name="email" wire:model.defer="user.email"
                        placeholder="Correo del usuario..." />
                    <x-jet-input-error for="user.email" />
                </div>
                <div class="w-full col-span-2 sm:col-span-1">
                    <x-label value="Rol :" />
                    <x-select id="role_id" name="role_id" class="w-full block" wire:model.defer="user.role_id">
                        <x-slot name="options">
                            {{-- @if (count($roles))
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id }}"
                                        {{ in_array($user->role_id, $roles) ? 'checked' : '' }}>
                                        {{ $rol->name }}</option>
                                @endforeach
                            @endif --}}
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="user.role_id" />
                </div>

                @if ($user->email_verified_at)
                    <div class="w-full col-span-2 sm:col-span-1">
                        <x-label value="Fecha verificación :" />
                        <x-disabled-text :text="dateFormat($user->email_verified_at)" />
                    </div>
                @endif
            </div>
        </x-form-card>

        <x-form-card titulo="SUCURSALES">
            <div class="w-full">
                @if (count($sucursales))
                    <div class="w-full flex flex-wrap gap-2">
                        <x-input-radio class="py-2" for="sucursal_null" text="NINGUNO" textSize="xs">
                            <input class="sr-only peer peer-disabled:opacity-25" type="radio" id="sucursal_null"
                                name="sucursal_id" value="" wire:model.defer="user.sucursal_id" />
                        </x-input-radio>
                        @foreach ($sucursales as $item)
                            <x-input-radio class="py-2" :for="'sucursal_' . $item->id" :text="$item->name" textSize="xs">
                                <input class="sr-only peer peer-disabled:opacity-25" type="radio"
                                    id="sucursal_{{ $item->id }}" name="sucursal_id" value="{{ $item->id }}"
                                    wire:model.defer="user.sucursal_id" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="user.sucursal_id" />
            </div>
        </x-form-card>

        {{-- @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach --}}

        <x-form-card titulo="PERMISOS ADICIONALES" subtitulo="Agregar permisos adicionales al rol asignado al usuario.">
        </x-form-card>

        <div class="w-full flex pt-4 justify-end">
            <x-button type="submit" wire:loading.attr="disabled">
                {{ __('ACTUALIZAR') }}</x-button>
        </div>
    </form>
</div>

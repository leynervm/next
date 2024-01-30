<div>
    <form class="w-full flex flex-col gap-8" wire:submit.prevent="save">
        <x-form-card titulo="PERFIL USUARIO">
            <div class="w-full grid grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full col-span-2">
                    <x-label value="Nombres :" />
                    <x-input class="block w-full" name="name" wire:model.defer="name"
                        placeholder="Nombres del usuario..." />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full col-span-2 sm:col-span-1">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" name="email" wire:model.defer="email"
                        placeholder="Correo del usuario..." />
                    <x-jet-input-error for="email" />
                </div>
                <div class="w-full col-span-2 sm:col-span-1">
                    <x-label value="Rol :" />
                    <x-select id="role_id" name="role_id" class="w-full block" wire:model.defer="role_id">
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
                    <x-input type="password" class="block w-full" name="password" wire:model.defer="password"
                        placeholder="Ingrese contraseña..." />
                    <x-jet-input-error for="password" />
                </div>
                <div class="w-full col-span-2 sm:col-span-1">
                    <x-label value="Confirmar contraseña :" />
                    <x-input type="password" class="block w-full" name="password_confirmation"
                        wire:model.defer="password_confirmation" placeholder="Confirmar contraseña..." />
                    <x-jet-input-error for="password_confirmation" />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="SUCURSALES">
            <div class="w-full">
                @if (count($sucursales))
                    <div class="w-full flex flex-wrap gap-2">
                        <x-input-radio class="py-2" for="sucursal_null" text="NINGUNO" textSize="xs">
                            <input class="sr-only peer peer-disabled:opacity-25" type="radio" id="sucursal_null"
                                name="sucursal_id" value="" />
                        </x-input-radio>
                        @foreach ($sucursales as $item)
                            <x-input-radio class="py-2" :for="'sucursal_' . $item->id" :text="$item->name" textSize="xs">
                                <input class="sr-only peer peer-disabled:opacity-25" type="radio"
                                    id="sucursal_{{ $item->id }}" name="sucursal_id" value="{{ $item->id }}"
                                    wire:model.defer="sucursal_id" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="sucursal_id" />
            </div>
        </x-form-card>

        <x-form-card titulo="PERMISOS ADICIONALES" subtitulo="Agregar permisos adicionales al rol asignado al usuario.">
        </x-form-card>

        <div class="w-full flex justify-end">
            <x-button type="submit" wire:loading.attr="disabled">
                {{ __('REGISTRAR') }}
            </x-button>
        </div>
    </form>
</div>

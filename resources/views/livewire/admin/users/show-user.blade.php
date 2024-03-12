<div>
    <form class="w-full flex flex-col gap-8" wire:submit.prevent="update">
        <x-form-card titulo="PERFIL USUARIO">
            <div class="w-full grid grid-cols-2 xl:grid-cols-3 gap-2">

                <div class="w-full">
                    <x-label value="Documento :" />
                    <x-disabled-text :text="$user->document" />
                    <x-jet-input-error for="user.document" />
                </div>

                <div class="w-full col-span-2">
                    <x-label value="Nombres completos :" />
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

                @if ($user->email_verified_at)
                    <div class="w-full col-span-2 sm:col-span-1">
                        <x-label value="Fecha verificación :" />
                        <x-disabled-text :text="dateFormat($user->email_verified_at)" />
                    </div>
                @endif
            </div>
        </x-form-card>

        @if ($user->employer)
            <x-form-card titulo="PERFIL TRABAJADOR" class="animate__animated animate__fadeInDown animate__faster">
                <x-simple-card class="w-full flex flex-col gap-1 rounded-md cursor-default p-3">
                    <div class="w-full">
                        <h1 class="font-semibold text-sm leading-4 text-primary">
                            {{ $user->employer->name }}</h1>

                        <h1 class="text-colorlabel font-medium text-xs">
                            SUCURSAL : {{ $user->employer->sucursal->name }}
                            @if ($user->employer->sucursal->trashed())
                                <x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal inline-block" />
                            @endif
                        </h1>

                        @if ($user->employer->areawork)
                            <h1 class="text-colorlabel font-medium text-xs">
                                AREA DE TRABAJO : {{ $user->employer->areawork->name }}</h1>
                        @endif

                        <div class="w-full font-semibold text-colorlabel text-2xl flex flex-wrap gap-x-5">
                            <p class="inline-block leading-normal">
                                <small class="text-[10px] font-medium">INGRESO :</small>
                                {{ formatDate($user->employer->horaingreso, 'HH:mm ') }}
                                <small
                                    class="text-[10px] font-medium">{{ formatDate($user->employer->horaingreso, 'A') }}</small>
                            </p>
                            <p class="inline-block leading-normal">
                                <small class="text-[10px] font-medium">SALIDA :</small>
                                {{ formatDate($user->employer->horasalida, 'HH:mm ') }}
                                <small
                                    class="text-[10px] font-medium">{{ formatDate($user->employer->horasalida, 'A') }}</small>
                            </p>
                        </div>
                    </div>
                    <div class="w-full flex justify-end">
                        <x-button-delete wire:click="$emit('user.desvicularEmployer', {{ $user }})"
                            wire:loading.attr="disabled" />
                    </div>
                </x-simple-card>
            </x-form-card>

            <x-form-card titulo="ROLES" class="animate__animated animate__fadeInDown animate__faster">
                <div class="w-full">
                    @if (count($roles))
                        <div class="w-full flex flex-wrap gap-2">
                            @foreach ($roles as $item)
                                <x-input-radio class="py-2" :for="'role_' . $item->id" :text="$item->name">
                                    <input class="sr-only peer peer-disabled:opacity-25" type="checkbox"
                                        id="role_{{ $item->id }}" name="roles[]" value="{{ $item->id }}"
                                        wire:model.defer="selectedRoles" />
                                </x-input-radio>
                            @endforeach
                        </div>
                    @endif
                    <x-jet-input-error for="selectedRoles" />
                </div>
            </x-form-card>

            <x-form-card titulo="PERMISOS ADICIONALES" class="animate__animated animate__fadeInDown animate__faster">
            </x-form-card>
        @endif

        <div class="w-full flex pt-4 justify-end">
            <x-button type="submit" wire:loading.attr="disabled">
                {{ __('ACTUALIZAR') }}</x-button>
        </div>
    </form>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('user.desvicularEmployer', data => {
                swal.fire({
                    title: 'Desvincular registro del trabajador relacionado al usuario ?',
                    text: "El trabajador dejará de estar disponible para el usuario vinculado.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deleteemployer(data.id);
                    }
                })
            })
        })
    </script>
</div>

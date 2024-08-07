<div class="w-full flex flex-col gap-10">
    @if (count($permisos) > 0)
        @foreach ($permisos as $module => $modulePermission)
            <div class="w-full">
                <h1
                    class="text-colortitleform uppercase text-xs font-semibold relative before:absolute before:w-14 before:h-1 before:bg-colortitleform before:-bottom-1">
                    MÓDULO : {{ $module }}</h1>
                <div class="w-full flex flex-wrap gap-3 justify-start mt-2">
                    @foreach ($modulePermission->groupBy('table') as $table => $permissions)
                        <x-simple-card class="w-full sm:max-w-xs p-1 !shadow-none">
                            <h1 class="text-primary uppercase text-xs font-semibold py-1">{{ $table }}</h1>
                            <div class="w-full flex flex-col justify-start items-start gap-2 mt-1">
                                @foreach ($permissions as $permiso)
                                    <div class="w-full flex items-center gap-2">
                                        @can('admin.roles.permisos.edit')
                                            <x-button-edit wire:click="edit({{ $permiso->id }})"
                                                wire:loading.attr="disabled" />
                                        @endcan
                                        <h1
                                            class="relative cursor-pointer leading-3 text-colorsubtitleform text-xs hover:text-colortitleform  transition-all ease-in-out duration-150">
                                            {{ $permiso->descripcion }}</h1>
                                    </div>
                                @endforeach
                            </div>
                        </x-simple-card>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <p><x-span-text text="NO EXISTEN PERMISOS REGISTRADOS..." class="inline-block bg-transparent" /></p>
    @endif


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar permiso') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">

                <p class="text-colorminicard text-xl font-semibold">
                    <span class="text-3xl uppercase">{{ $permission->module }}</span>
                    - {{ $permission->table }}
                </p>

                <div class="w-full mt-3">
                    <x-label value="Descripción del permiso :" />
                    <x-input class="block w-full" wire:model.defer="permission.descripcion"
                        placeholder="Descripción del permiso..." />
                    <x-jet-input-error for="permission.descripcion" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>

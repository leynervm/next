<div class="w-full flex flex-col gap-10" x-data="updaterol">
    <x-form-card titulo="INFORMACIÓN DEL ROL">
        <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
            <div class="w-full">
                <x-label value="Nombre del rol :" />
                <x-input wire:keydown.enter="update" class="block w-full" wire:model.defer="role.name"
                    placeholder="Nombre de rol..." />
                <x-jet-input-error for="role.name" />
                <x-jet-input-error for="selectedPermisos" />
            </div>
            {{-- <div class="w-full flex pt-4 justify-end">
                <x-button type="submit" wire:loading.attr="disabled">
                    {{ __('Save') }}</x-button>
            </div> --}}
        </form>
    </x-form-card>

    <div class="w-full left-0 fixed z-10 bottom-0 p-3 px-8 bg-body">
        <div class="max-w-full mx-auto flex justify-end">
            <x-button wire:click="update" wire:loading.attr="disabled">
                {{ __('Save') }}</x-button>
        </div>
    </div>

    @if (count($permisos) > 0)
        @foreach ($permisos as $module => $modulePermission)
            <div class="w-full pb-16" wire:ignore>
                <h1
                    class="text-colortitleform uppercase text-xs font-semibold relative before:absolute before:w-10 before:h-1 before:bg-colortitleform before:-bottom-1">
                    MÓDULO : {{ $module }}</h1>
                <div class="w-full flex flex-wrap gap-3 justify-start mt-2">
                    @foreach ($modulePermission->groupBy('table') as $table => $permissions)
                        <x-simple-card class="w-full sm:max-w-xs p-1 bg-body">
                            <h1 class="text-primary uppercase text-xs font-semibold">{{ $table }}</h1>
                            <div class="w-full flex flex-col justify-start items-start gap-2 mt-1">
                                @php
                                    $id_all = rand();
                                @endphp
                                <label
                                    class="relative cursor-pointer hover:text-primary inline-flex items-center gap-2 text-colorsubtitleform text-xs transition-all ease-in-out duration-150"
                                    for="{{ $id_all }}">
                                    <input type="checkbox" class="sr-only peer" id="{{ $id_all }}"
                                        @change="allpermisostable($event.target, '{{ $table }}')"
                                        name="tables" />
                                    <span
                                        class="w-8 h-5 flex items-center bg-gray-300 rounded-full peer peer-checked:after:translate-x-4 after:absolute after:left-[0px] peer-checked:after:border-primary after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-next-500"></span>
                                    Seleccionar todo
                                </label>

                                @foreach ($permissions as $permiso)
                                    <label
                                        class="relative cursor-pointer hover:text-primary inline-flex items-center gap-2 text-colorsubtitleform text-xs transition-all ease-in-out duration-150"
                                        for="{{ $permiso->id }}">
                                        <input type="checkbox" class="sr-only peer" id="{{ $permiso->id }}"
                                            value="{{ $permiso->id }}" wire:model.defer="selectedPermisos"
                                            name="{{ $id_all }}" @change="togglePermiso($event.target)" />
                                        <span
                                            class="w-8 h-5 flex items-center bg-gray-300 rounded-full peer peer-checked:after:translate-x-4 after:absolute after:left-[0px] peer-checked:after:border-primary after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-next-500"></span>
                                        {{ $permiso->descripcion }}
                                    </label>
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
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('updaterol', () => ({
                selectedPermisos: @entangle('selectedPermisos').defer,

                init() {
                    var checkboxes = document.querySelectorAll(`input[type="checkbox"][name="tables"]`);
                    checkboxes.forEach(function(checkbox) {

                        let checkedall = 0;
                        const groupcheckboxes = document.querySelectorAll(
                            `input[type="checkbox"][name="${checkbox.id}"]`);
                        groupcheckboxes.forEach(function(checkboxgroup) {
                            if (checkboxgroup.checked) {
                                checkedall++;
                            }
                        });

                        document.getElementById(checkbox.id).checked = checkedall ==
                            groupcheckboxes.length ? true : false;

                    });
                },
                allpermisostable(target, table) {

                    let selectedPermisos = JSON.parse(JSON.stringify(this.selectedPermisos));
                    let permisos = [];
                    var checkboxes = document.querySelectorAll(
                        `input[type="checkbox"][name="${target.id}"]`);

                    let checkedall = 0;
                    checkboxes.forEach(function(checkbox) {
                        if (checkbox.checked) {
                            checkedall++;
                        }

                        // verificar si ya esta agregado no volver agregar
                        if (checkbox.checked !== target.checked) {
                            checkbox.checked = target.checked;
                            permisos.push(checkbox.value);
                        }
                    });

                    permisos.forEach((value) => {
                        this.toggleItem(selectedPermisos, parseInt(value));
                    })
                },
                toggleItem(selectedPermisos, permiso_id) {
                    let index = selectedPermisos.indexOf(parseInt(permiso_id));
                    if (index !== -1) {
                        selectedPermisos.splice(index, 1);
                    } else {
                        selectedPermisos.push(parseInt(permiso_id));
                    }
                    this.selectedPermisos = selectedPermisos;
                },
                togglePermiso(target) {
                    let checkedall = 0;
                    const selectedPermisos = JSON.parse(JSON.stringify(this.selectedPermisos));
                    const checkboxes = document.querySelectorAll(
                        `input[type="checkbox"][name="${target.name}"]`);
                    checkboxes.forEach(function(checkbox) {
                        if (checkbox.checked) {
                            checkedall++;
                        }
                    });

                    document.getElementById(target.name).checked = checkedall == checkboxes
                        .length ? true : false;
                    this.toggleItem(selectedPermisos, target.value);
                }
            }))
        })
    </script>
</div>

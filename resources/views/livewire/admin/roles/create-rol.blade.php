<div class="w-full flex flex-col gap-10" x-data="datarol">
    <x-form-card titulo="INFORMACIÓN DEL ROL">
        <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
            <div class="w-full">
                <x-label value="Nombre del rol :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Nombre de rol..." />
                <x-jet-input-error for="name" />
                <x-jet-input-error for="selectedPermisos" />
            </div>
            <div class="w-full flex pt-4 justify-end">
                <x-button type="submit" wire:loading.attr="disabled">
                    {{ __('REGISTRAR') }}
                </x-button>
            </div>
        </form>
    </x-form-card>

    @if (count($permisos) > 0)
        @foreach ($permisos as $module => $modulePermission)
            <div class="w-full" wire:ignore>
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
                                        @change="allpermisostable($event.target, '{{ $table }}')" />
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
            Alpine.data('datarol', () => ({
                selectedPermisos: @entangle('selectedPermisos').defer,

                allpermisostable(target, table) {

                    const selectedPermisos = JSON.parse(JSON.stringify(this.selectedPermisos));
                    let permisos = [];
                    var checkboxes = document.querySelectorAll(
                        `input[type="checkbox"][name="${target.id}"]`);

                    checkboxes.forEach(function(checkbox) {
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
                        // quitarlo del array
                        selectedPermisos.splice(index, 1);
                    } else {
                        // agregarlo al array
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

                    document.getElementById(target.name).checked = checkedall == checkboxes.length ?
                        true : false;
                    this.toggleItem(selectedPermisos, target.value);
                }
            }))
        })
    </script>
</div>

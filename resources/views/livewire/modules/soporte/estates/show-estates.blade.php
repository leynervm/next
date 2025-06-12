<div class="">
    @if (count($estates))
        <div class="pb-2">
            {{ $estates->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">

        @if (count($estates))
            @foreach ($estates as $item)
                {{-- <div class="flex text-[10px] w-full sm:w-80 font-semibold shadow rounded-xl bg-fondominicard p-2 gap-2">
                    <div class="w-2/6 flex gap-2 flex-col justify-between">
                        <div class="flex flex-wrap gap-2 justify-center items-center h-full">
                            <p class="relative text-center text-colortitle" style=" {{ $item->color }}">
                                {{ $item->name }}
                                <span class="block absolute w-4 h-2 rounded left-1/2 -translate-x-1/2"
                                    style="background: {{ $item->color }}"></span>
                            </p>

                            @if ($item->finish == 1)
                                <div class="mt-1 flex items-center justify-center w-full">
                                    <span
                                        class="bg-fondospancardproduct text-textspancardproduct font-medium p-1 rounded-md">Fin
                                        Proceso</span>
                                </div>
                            @endif

                        </div>
                        <div class="w-full flex justify-between">
                            @if ($item->default == 1)
                                <span class="bg-green-100 text-green-500 p-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </span>
                            @endif

                            <div class="ml-auto">
                                <x-button-edit wire:loading.attr="disabled" wire:target="edit({{ $item->id }})"
                                    wire:click="edit({{ $item->id }})"></x-button-edit>
                                <x-button-delete wire:loading.attr="disabled"
                                    wire:target="confirmDelete({{ $item->id }})"
                                    wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                            </div>
                        </div>
                    </div>
                    <div class="w-4/6 border-l pl-1">
                        <div class="w-full flex flex-wrap gap-1 justify-start">

                            @if (count($item->atencions))
                                @foreach ($item->atencions as $atencion)
                                    <div
                                        class="inline-flex gap-1 items-center p-1 rounded-lg bg-fondospancardproduct text-textspancardproduct">
                                        <span>{{ $atencion->name }}</span>
                                        <x-button-delete></x-button-delete>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div> --}}

                <x-minicard :title="$item->name" :alignFooter="$item->default == 1 ? 'justify-between' : 'justify-end'" size="md">

                    <x-slot name="spanColor">
                        <span class="block absolute w-4 h-2 rounded left-1/2 -translate-x-1/2 -top-2"
                            style="background: {{ $item->color }}"></span>
                    </x-slot>

                    @if ($item->finish == 1)
                        <x-slot name="content">
                            Fin Proceso
                        </x-slot>
                    @endif

                    <x-slot name="buttons">

                        @if ($item->default == 1)
                            <span class="bg-green-100 text-green-500 p-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                        @endif

                        <div class="ml-auto">
                            <x-button-edit wire:loading.attr="disabled" wire:target="edit({{ $item->id }})"
                                wire:click="edit({{ $item->id }})"></x-button-edit>
                            <x-button-delete wire:loading.attr="disabled"
                                wire:target="confirmDelete({{ $item->id }})"
                                wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar status') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <x-label value="Nombre del status :" />
                <x-input class="block w-full" wire:model.defer="estate.name"
                    placeholder="Ingrese nombre del status..." />
                <x-jet-input-error for="estate.name" />

                <x-label value="Descripción :" class="mt-2" />
                <x-input class="block w-full" wire:model.defer="estate.descripcion"
                    placeholder="Ingrese descripción del status..." />
                <x-jet-input-error for="estate.descripcion" />

                <div class="w-full">
                    <x-label value="Color :" class="mt-2" />
                    <input wire:model.defer="estate.color" type="color" value="#000000" class="h-14 w-14" />
                    <x-jet-input-error for="estate.color" />
                </div>

                <x-label class="mt-2 inline-flex items-center gap-1 cursor-pointer" for="default_edit">
                    <x-input wire:model.defer="estate.default" type="checkbox" id="default_edit" value="1" />
                    Definir valor como predeterminado
                </x-label>

                <x-label class="mt-2 inline-flex items-center gap-1 cursor-pointer" for="finish_edit">
                    <x-input wire:model.defer="estate.finish" type="checkbox" id="finish_edit" value="1" />
                    Definir status como finalización
                </x-label>
                <x-jet-input-error for="estate.finish" />
                <x-jet-input-error for="estate.default" />

                <x-label class="mt-6 border-b" value="Atenciones asignar" />

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('livewire:load', function() {

            var toastMixin = Swal.mixin({
                toast: true,
                icon: "success",
                title: "Mensaje",
                position: "top-right",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });

            window.addEventListener('soporte::status.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.detail.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(data.detail.id);
                        Livewire.emit('deleteStatus', data.detail.id);
                    }
                })
            })

            window.addEventListener('soporte::status.deleted', event => {
                toastMixin.fire({
                    title: 'Eliminado correctamente'
                });
            })
        })
    </script>
</div>

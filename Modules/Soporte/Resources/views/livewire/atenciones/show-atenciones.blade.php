<div class="">

    @if (count($atenciones))
        <div class="pb-2">
            {{ $atenciones->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">

        @if (count($atenciones))
            @foreach ($atenciones as $item)
                <x-card-next :alignFooter="$item->equipamentrequire ? 'justify-between' : 'justify-end'" :titulo="$item->name" class="w-full sm:w-60 border border-fondotitlecardnext">

                    @if (count($item->entornos))
                        <x-label class="" value="Tipos de atención" />
                        <div class="flex flex-wrap gap-1">
                            @foreach ($item->entornos as $entorno)
                                <span
                                    class="bg-fondospancardproduct text-textspancardproduct rounded-lg p-1 py-0.5 text-[10px]">{{ $entorno->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <x-label class="mt-2" value="Areas asignadas" />
                    @if (count($item->areas))
                        <div class="flex flex-wrap gap-1">
                            @foreach ($item->areas as $area)
                                <span
                                    class="bg-fondospancardproduct text-textspancardproduct rounded-lg p-1 py-0.5 text-[10px]">{{ $area->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- @if (count($item->estates))
                        <x-label class="mt-2" value="Status asignados" />
                        <div class="flex flex-wrap gap-1">
                            @foreach ($item->estates as $estate)
                                <span
                                    class="bg-fondospancardproduct text-textspancardproduct rounded-lg p-1 py-0.5 text-[10px]">{{ $estate->name }}</span>
                            @endforeach
                        </div>
                    @endif --}}

                    <x-slot name="footer">
                        @if ($item->equipamentrequire)
                            <span
                                class="text-[10px] font-medium bg-fondospancardproduct text-textspancardproduct p-1 rounded-md">
                                Requiere ingreso de equipo</span>
                        @endif
                        <div>
                            <x-button-edit wire:loading.attr="disabled" wire:target="edit({{ $item->id }})"
                                wire:click="edit({{ $item->id }})"></x-button-edit>
                            <x-button-delete wire:loading.attr="disabled"
                                wire:target="confirmDelete({{ $item->id }})"
                                wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                        </div>
                    </x-slot>
                </x-card-next>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar atención') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <x-label value="Atención :" />
                <x-input class="block w-full" wire:model.defer="atencion.name"
                    placeholder="Ingrese nombre de atención..." />
                <x-jet-input-error for="atencion.name" />

                <x-label class="mt-2 flex items-center gap-1 cursor-pointer" for="equipamentrequire_edit">
                    <x-input wire:model.defer="atencion.equipamentrequire" type="checkbox" id="equipamentrequire_edit"
                        value="1" />
                    Requiere ingresar equipamiento.
                </x-label>

                <x-label class="mt-6" value="Areas responsables" />
                @if (count($areas))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($areas as $item)
                            <x-label class="flex items-center gap-1 cursor-pointer bg-next-100 rounded-lg p-1"
                                for="area_edit_{{ $item->id }}">
                                <x-input wire:model.defer="selectedAreas" name="areas[]" type="checkbox"
                                    id="area_edit_{{ $item->id }}" value="{{ $item->id }}" />
                                {{ $item->name }}
                            </x-label>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedAreas" />

                <x-label class="mt-6" value="Entornos atención" />
                @if (count($entornos))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($entornos as $item)
                            <x-label class="flex items-center gap-1 cursor-pointer bg-next-100 rounded-lg p-1"
                                for="entorno_edit_{{ $item->id }}">
                                <x-input wire:model.defer="selectedEntornos" name="entornos[]" type="checkbox"
                                    id="entorno_edit_{{ $item->id }}" value="{{ $item->id }}" />
                                {{ $item->name }}
                            </x-label>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedEntornos" />

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

            window.addEventListener('soporte::atenciones.confirmDelete', data => {
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
                        Livewire.emit('deleteAtencion', data.detail.id);
                    }
                })
            })

            window.addEventListener('soporte::atenciones.deleted', event => {
                toastMixin.fire({
                    title: 'Eliminado correctamente'
                });
            })
        })
    </script>
</div>

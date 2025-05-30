<div class="">
    <x-loading-web-next x-cloak wire:loading.flex wire:key="loadingatencions" />

    @if (count($atencions))
        <div class="pb-2">
            {{ $atencions->links() }}
        </div>
    @endif

    @if (count($atencions))
        <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(200px,1fr))] self-start gap-2">
            @foreach ($atencions as $item)
                <x-form-card :titulo="$item->name" class="w-full gap-2" wire:key="ts_{{ $item->id }}">

                    <div class="w-full flex-1 flex flex-col gap-2">
                        <div>
                            <x-label value="Tipos de atención" />
                            @if (count($item->entornos))
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($item->entornos as $entorno)
                                        <x-span-text :text="$entorno->name" class="font-semibold" />
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div>
                            <x-label value="Areas asignadas" />
                            @if (count($item->areaworks))
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($item->areaworks as $area)
                                        <x-span-text :text="$area->name" class="font-semibold" />
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div
                        class="w-full flex-1 flex gap-2 items-end {{ $item->addequipo() ? 'justify-between' : 'justify-end' }}">
                        @if ($item->addequipo())
                            <div class="inline-flex gap-1 items-center bg-next-500 text-white p-1 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="block size-4">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12.5 17h-8.5a1 1 0 0 1 -1 -1v-12a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v8.5" />
                                    <path d="M3 13h13.5" />
                                    <path d="M8 21h4.5" />
                                    <path d="M10 17l-.5 4" />
                                    <path d="M16 19h6" />
                                    <path d="M19 16v6" />
                                </svg>
                                <span class="text-[10px]">AGREGAR EQUIPO</span>
                            </div>
                        @endif
                        <div class="inline-flex items-end">
                            <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                            <x-button-delete wire:loading.attr="disabled"
                                onclick="confirmDeleteAtencion({{ $item }})" />
                        </div>
                    </div>
                </x-form-card>
            @endforeach
        </div>
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar Atención') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Atención :" />
                    <x-input class="block w-full" wire:model.defer="atencion.name"
                        placeholder="Ingrese nombre de atención..." />
                    <x-jet-input-error for="atencion.name" />
                </div>

                <div>
                    <x-label-check for="equipamentrequire_edit">
                        <x-input wire:model.defer="atencion.equipamentrequire" type="checkbox"
                            id="equipamentrequire_edit" value="1" />REQUIERE AGREGAR EQUIPO</x-label-check>
                    <x-jet-input-error for="atencion.equipamentrequire" />
                </div>

                <x-label class="mt-4" value="ÁREA RESPONSABLE" />
                @if (count($areaworks))
                    <div class="flex flex-wrap gap-2">
                        @foreach ($areaworks as $item)
                            <x-input-radio class="py-2" for="editareawork_{{ $item->id }}" :text="$item->name">
                                <input wire:model.defer="selectedareaworks"
                                    class="sr-only peer peer-disabled:opacity-25" type="checkbox"
                                    id="editareawork_{{ $item->id }}" name="editareaworks"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedareaworks" />

                <x-label class="mt-6" value="ENTORNO ATENCIÓN" />
                @if (count($entornos))
                    <div class="flex flex-wrap gap-2">
                        @foreach ($entornos as $item)
                            <x-input-radio class="py-2" for="editentorno_{{ $item->id }}" :text="$item->name">
                                <input wire:model.defer="selectedentornos" class="sr-only peer peer-disabled:opacity-25"
                                    type="checkbox" id="editentorno_{{ $item->id }}" name="editentornos"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedentornos" />

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function confirmDeleteAtencion(atencion) {
            swal.fire({
                title: `ELIMINAR ATENCIÓN DE "${atencion.name}" ?`,
                text: null,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(atencion.id);
                }
            })
        }
    </script>
</div>

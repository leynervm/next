<div class="">

    @if (count($pricetypes))
        <div class="pb-2">
            {{ $pricetypes->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($pricetypes))
            @foreach ($pricetypes as $item)
                <x-minicard :title="$item->name" :content="'Formato: ' . number_format(0, $item->decimalrounded)" :alignFooter="$item->default == 1 || $item->web == 1 ? 'justify-between' : 'justify-end'" size="md">
                    {{-- :content="number_format($item->ganancia, $item->decimalrounded) . ' %'" --}}
                    <x-slot name="buttons">

                        <div class="inline-flex">
                            @if ($item->default)
                                <span class="bg-green-100 text-green-500 p-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                            @endif
                            @if ($item->web)
                                <span
                                    class="bg-green-100 text-green-500 p-1 rounded-full @if ($item->default) absolute left-6 ring-2 ring-white @endif">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="2" x2="22" y1="12" y2="12" />
                                        <path
                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                    </svg>
                                </span>
                            @endif
                        </div>

                        <div class="">
                            <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                                wire:click="edit({{ $item->id }})"></x-button-edit>
                            <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete"
                                wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar lista precio') }}
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
                <x-label value="Lista precio :" />
                <x-input class="block w-full" wire:model.defer="pricetype.name"
                    placeholder="Nombre de lista precio..." />
                <x-jet-input-error for="pricetype.name" />

                <div class="w-full flex flex-wrap sm:flex-nowrap gap-2 mt-2">
                    <div class="w-full sm:w-1/2">
                        <x-label value="Porcentaje ganancia (%) :" />
                        <x-input class="block w-full" wire:model.defer="pricetype.ganancia" type="number"
                            step="0.1" min="0" />
                        <x-jet-input-error for="pricetype.ganancia" />
                    </div>
                    <div class="w-full sm:w-1/2">
                        <x-label value="Redondear decimales :" />
                        <x-input class="block w-full" wire:model.defer="pricetype.decimalrounded" type="number"
                            step="1" min="0" max="4" />
                        <x-jet-input-error for="pricetype.decimalrounded" />
                    </div>
                </div>

                <div class="mt-3 mb-1">
                    <x-label textSize="[10px]"
                        class="inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                        for="default_edit">
                        <x-input wire:model.defer="pricetype.default" name="default" type="checkbox" id="default_edit" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label>
                </div>
                <x-jet-input-error for="pricetype.default" />

                <div class="mt-1 mb-1">
                    <x-label textSize="[10px]"
                        class="inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                        for="web_edit">
                        <x-input wire:model.defer="pricetype.web" name="web" type="checkbox" id="web_edit" />
                        PREDETERMINADO PARA VENTAS POR INTERNET
                    </x-label>
                </div>
                <x-jet-input-error for="pricetype.web" />


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
            window.addEventListener('pricetypes.confirmDelete', data => {
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
                        Livewire.emitTo('admin.pricetypes.show-pricetypes', 'delete', data.detail
                            .id);
                    }
                })
            })
        })
    </script>
</div>

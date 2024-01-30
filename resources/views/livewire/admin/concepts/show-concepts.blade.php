<div class="">

    @if ($concepts->hasPages())
        <div class="pb-2">
            {{ $concepts->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($concepts))
            @foreach ($concepts as $item)
                <x-minicard :title="$item->name" :alignFooter="$item->default == 1 ? 'justify-between' : 'justify-end'" size="md">
                    <p class="text-center">
                        <x-span-text :text="getTextConcept($item->default)" class="inline-block leading-3" />
                    </p>

                    <x-slot name="buttons">
                        <div class="inline-flex">
                            @if ($item->default == 1)
                                <x-icon-default />
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
            {{ __('Actualizar concepto pago') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <x-label value="Descripción concepto :" />
                <x-input class="block w-full" wire:model.defer="concept.name"
                    placeholder="Descripción del concepto pago..." />
                <x-jet-input-error for="concept.name" />

                <x-title-next titulo="VALOR PREDETERMINADO" class="mt-3" />
                <div class="w-full flex gap-1 items-start p-1">

                    <div class="block">
                        <x-label-check for="edit_ninguno">
                            <x-input wire:model.defer="concept.default" name="default" type="radio" value="0"
                                id="edit_ninguno" class="checked:rounded-full" />
                            NINGUNO
                        </x-label-check>
                    </div>

                    <div class="block">
                        <x-label-check for="edit_ventas">
                            <x-input wire:model.defer="concept.default" name="default" type="radio" value="1"
                                id="edit_ventas" class="checked:rounded-full" />
                            PAGO VENTAS
                        </x-label-check>
                    </div>

                    <div class="block">
                        <x-label-check for="edit_internet">
                            <x-input wire:model.defer="concept.default" name="default" type="radio" value="2"
                                id="edit_internet" class="checked:rounded-full" />
                            PAGO INTERNET
                        </x-label-check>
                    </div>

                    <div class="block">
                        <x-label-check for="edit_cuota">
                            <x-input wire:model.defer="concept.default" name="default" type="radio" value="3"
                                id="edit_cuota" class="checked:rounded-full" />
                            PAGO CUOTA
                        </x-label-check>
                    </div>
                </div>
                <x-jet-input-error for="concept.default" />

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        document.addEventListener('livewire:load', function() {
            window.addEventListener('concepts.confirmDelete', data => {
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
                        Livewire.emitTo('admin.concepts.show-concepts', 'delete', data.detail
                            .id);
                    }
                })
            })
        })
    </script>
</div>

<div>
    @if ($concepts->hasPages())
        <div class="pb-2">
            {{ $concepts->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($concepts))
            @foreach ($concepts as $item)
                <x-minicard :title="$item->name" alignFooter="justify-between" size="lg">
                    {{-- @if ($item->default->value > 0)
                        <p class="text-center">
                            <x-span-text :text="getTextConcept($item->default->value)" class="inline-block text-[9px] leading-3 !tracking-normal" />
                        </p>
                    @endif --}}

                    <x-slot name="buttons">
                        <div class="inline-flex">
                            <x-span-text :text="$item->typemovement->value" class="inline-block leading-3 !tracking-normal"
                                type="{{ $item->isIngreso() ? 'green' : 'red' }}" />

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
                            @if ($item->isDefault())
                                @can('admin.cajas.conceptos.edit')
                                    <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                                @endcan

                                @can('admin.cajas.conceptos.delete')
                                    <x-button-delete wire:loading.attr="disabled"
                                        wire:click="$emit('concept.confirmDelete',{{ $item }})" />
                                @endif
                            @endcan
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar concepto pago') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                @if ($concept->isDefault())
                    <div class="w-full">
                        <x-label value="Tipo movimiento :" />
                        @if (count(getTiypemovimientos()))
                            <div class="w-full flex flex-wrap gap-2 items-start">
                                @foreach (getTiypemovimientos() as $movimiento)
                                    <x-input-radio class="py-2" for="edit_{{ $movimiento->value }}" :text="$movimiento->value">
                                        <input wire:model.defer="concept.typemovement"
                                            class="sr-only peer peer-disabled:opacity-25" type="radio"
                                            id="edit_{{ $movimiento->value }}" name="edittypemovement"
                                            value="{{ $movimiento->value }}" />
                                    </x-input-radio>
                                @endforeach
                            </div>
                        @endif
                        <x-jet-input-error for="concept.typemovement" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Descripción concepto :" />
                    <x-input class="block w-full" wire:model.defer="concept.name"
                        placeholder="Descripción del concepto pago..." />
                    <x-jet-input-error for="concept.name" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('concept.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar concepto ' + data.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(data.id);
                    }
                })
            })
        })
    </script>
</div>

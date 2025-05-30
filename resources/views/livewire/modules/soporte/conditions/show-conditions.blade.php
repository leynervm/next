<div class="">

    @if (count($conditions))
        <div class="pb-2">
            {{ $conditions->links() }}
        </div>
    @endif

    @if (count($conditions))
        <div class="flex gap-2 flex-wrap justify-around md:justify-start">
            @foreach ($conditions as $item)
                <x-minicard :title="$item->name" size="md" wire:key="cs_{{ $item->id }}">
                    @if ($item->pagable())
                        <x-toast align="top">Pago obligatorio del Ticket</x-toast>
                    @endif

                    <x-slot name="buttons">
                        <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                        <x-button-delete wire:loading.attr="disabled"
                            onclick="confirmDeleteCondition({{ $item }})" />
                    </x-slot>
                </x-minicard>
            @endforeach
        </div>
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar Condición Atención') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Condición atención :" />
                    <x-input class="block w-full" wire:model.defer="condition.name"
                        placeholder="Ingrese descripción de condición..." />
                    <x-jet-input-error for="condition.name" />
                </div>

                <div>
                    <x-label-check for="editflagpagable">
                        <x-input wire:model.defer="condition.flagpagable" name="flagpagable" type="checkbox"
                            id="editflagpagable" value="1" />DEFINIR COMO PAGABLE</x-label-check>
                    <x-jet-input-error for="flagpagable" />
                </div>

                {{-- <x-label class="mt-6" value="Asignar marcas autorizadas" /> --}}

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function confirmDeleteCondition(condition) {
            swal.fire({
                title: `ELIMINAR CONDICIÓN "${condition.name}" `,
                text: null,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(condition.id);
                }
            })
        }
    </script>
</div>

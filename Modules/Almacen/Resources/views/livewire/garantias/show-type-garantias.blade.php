<div class="relative">

    @if (count($typegarantias))
        <div class="pb-2">
            {{ $typegarantias->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($typegarantias))
            @foreach ($typegarantias as $item)
                @php
                    $timemes = $item->time > 11 ? ' (' . $item->time . ' Meses)' : '';
                @endphp
                <x-minicard :title="$item->name" :content="$item->timestring . $timemes" size="lg">
                    <x-slot name="buttons">
                        <div class="ml-auto">
                            <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled" />
                            <x-button-delete wire:click="$emit('typegarantias.confirmDelete',{{ $item }})"
                                wire:loading.attr="disabled" />
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <div wire:loading.flex class="loading-overlay rounded hidden">
        <x-loading-next />
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar garantía producto') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">

                <x-label value="Descripción :" />
                <x-input class="block w-full" wire:model.defer="typegarantia.name"
                    placeholder="Ingrese descripción garantía..." />
                <x-jet-input-error for="typegarantia.name" />

                <x-label class="mt-2" value="Tiempo predeterminado (Letras):" />
                <x-input class="block w-full" wire:model.defer="typegarantia.timestring"
                    placeholder="Ingrese tiempo garantía en letras..." />
                <x-jet-input-error for="typegarantia.timestring" />

                <x-label class="mt-2" value="Tiempo predeterminado (Meses):" />
                <x-input type="number" class="block w-full" wire:model.defer="typegarantia.time" step="1"
                    min="1" />
                <x-jet-input-error for="typegarantia.time" />

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
            Livewire.on('typegarantias.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.name,
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
                        Livewire.emitTo('almacen::garantias.show-type-garantias', 'delete', data
                            .id);
                    }
                })
            })
        })
    </script>
</div>

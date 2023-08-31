<div class="">

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
            {{ __('Actualizar garantía producto') }}
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
            window.addEventListener('typegarantias.confirmDelete', data => {
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
                        Livewire.emitTo('almacen::garantias.show-type-garantias', 'delete', data
                            .detail.id);
                    }
                })
            })
        })
    </script>
</div>

<div class="">

    @if (count($units))
        <div class="pb-2">
            {{ $units->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($units))
            @foreach ($units as $item)
                <x-minicard :title="$item->name" :content="$item->code">
                    <x-slot name="buttons">
                        <div class="ml-auto">
                            <x-button-edit wire:loading.attr="disabled" wire:target="edit({{ $item->id }})"
                                wire:click="edit({{ $item->id }})"></x-button-edit>
                            <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete({{ $item->id }})"
                                wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar unidad medida') }}
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

                <x-label value="Unidad medida :" />
                <x-input class="block w-full" wire:model.defer="unit.name" placeholder="Ingrese nombre unidad..." />
                <x-jet-input-error for="unit.name" />

                <x-label value="Código :" class="mt-2"/>
                <x-input class="block w-full" wire:model.defer="unit.code" placeholder="Ingrese nombre unidad..." />
                <x-jet-input-error for="unit.code" />

                {{-- <div class="w-full flex gap-2">
                    <div class="w-1/2 mt-2">
                        <x-label value="Abreviatura :" />
                        <x-input class="block w-full" wire:model.defer="unit.abreviatura"
                            placeholder="Ingrese nombre unidad..." />
                        <x-jet-input-error for="unit.abreviatura" />
                    </div>
                    <div class="w-1/2 mt-2">
                        
                    </div>
                </div> --}}

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
            window.addEventListener('units.confirmDelete', data => {
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
                        Livewire.emitTo('almacen::units.show-units', 'delete', data
                            .detail.id);
                    }
                })
            })
        })
    </script>
</div>

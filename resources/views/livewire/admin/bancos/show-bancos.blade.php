<div class="">

    @if ($bancos->hasPages())
        <div class="pb-2">
            {{ $bancos->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">

        <livewire:admin.bancos.create-banco />

        @if (count($bancos))
            @foreach ($bancos as $item)
                <x-minicard :title="$item->name">
                    <x-slot name="buttons">
                        <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                            wire:click="edit({{ $item->id }})" />
                        <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete"
                            wire:click="confirmDelete({{ $item->id }})" />
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar tipo banco') }}
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
                <x-label value="Nombre banco :" />
                <x-input class="block w-full" wire:model.defer="banco.name"
                    placeholder="Descripción del banco pago..." />
                <x-jet-input-error for="banco.name" />

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        document.addEventListener('livewire:load', function() {
            window.addEventListener('bancos.confirmDelete', data => {
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
                        Livewire.emitTo('admin.bancos.show-bancos', 'delete', data.detail
                            .id);
                    }
                })
            })
        })
    </script>
</div>

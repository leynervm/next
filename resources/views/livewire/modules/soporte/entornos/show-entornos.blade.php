<div class="">

    @if (count($entornos))
        <div class="pb-2">
            {{ $entornos->links() }}
        </div>
    @endif

    @if (count($entornos))
        <div class="flex gap-2 flex-wrap justify-start">
            @foreach ($entornos as $item)
                <x-minicard :title="$item->name" alignFooter="justify-end" size="md">

                    @if ($item->isDoa())
                        <x-toast align="top">Agregar dirección para Tickets</x-toast>
                    @endif

                    <x-slot name="buttons">
                        @if ($item->isDefault())
                            <span class="bg-green-100 text-green-500 p-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                        @endif
                        <div class="ml-auto">
                            <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                            <x-button-delete wire:loading.attr="disabled"
                                onclick="confirmDeleteEntorno({{ $item }})" />
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        </div>
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar entorno atención') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Entorno Atención :" />
                    <x-input class="block w-full" wire:model.defer="entorno.name"
                        placeholder="Ingrese entorno de atención..." />
                    <x-jet-input-error for="entorno.name" />
                </div>

                <div>
                    <x-label-check for="editrequiredirection">
                        <x-input wire:model.defer="entorno.requiredirection" name="editrequiredirection" type="checkbox"
                            id="editrequiredirection" value="1" />REQUIERE AGREGAR DIRECCIÓN</x-label-check>
                    <x-jet-input-error for="entorno.requiredirection" />
                </div>

                {{-- <x-label class="mt-2 flex items-center gap-1 cursor-pointer" for="default_edit">
                    <x-input wire:model.defer="entorno.default" type="checkbox" id="default_edit" value="1" />
                    Definir valor como predeterminado.
                </x-label>
                <x-jet-input-error for="entorno.default" /> --}}

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function confirmDeleteEntorno(entorno) {
            swal.fire({
                title: `ELIMINAR ENTORNO "${entorno.name}" ?`,
                text: null,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(entorno.id);
                }
            })
        }
    </script>
</div>

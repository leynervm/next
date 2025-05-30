<div class="">

    @if (count($priorities))
        <div class="pb-2">
            {{ $priorities->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($priorities))
            @foreach ($priorities as $item)
                <x-minicard :title="$item->name" alignFooter="justify-end" size="md" class="border"
                    style="border-color:{{ $item->color }};color:{{ $item->color }}">
                    <x-slot name="buttons">
                        <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                        <x-button-delete wire:loading.attr="disabled"
                            onclick="confirmDeletePriority({{ $item }})" />
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar prioridad atención') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Prioridad :" />
                    <x-input class="block w-full" wire:model.defer="priority.name"
                        placeholder="Ingrese nombre de prioridad..." />
                    <x-jet-input-error for="priority.name" />
                </div>

                <div class="w-full">
                    <x-label value="Sleccionar color :" />
                    <input wire:model.defer="priority.color" type="color" value="#000000" class="h-14 w-14" />
                    <x-jet-input-error for="priority.color" />
                </div>

                {{-- <x-label class="mt-2 inline-flex items-center gap-1 cursor-pointer" for="default">
                    <x-input wire:model.defer="default" type="checkbox" id="default" value="1" />
                    Definir valor como predeterminado
                </x-label>
                <x-jet-input-error for="default" /> --}}

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function confirmDeletePriority(priority) {
            swal.fire({
                title: `ELIMINAR PRIORIDAD "${priority.name}"`,
                text: null,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(priority.id)
                }
            })
        }
    </script>
</div>

<div class="">

    @if (count($areas))
        <div class="pb-2">
            {{ $areas->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($areas))
            @foreach ($areas as $item)
                <x-minicard :title="$item->name" :content="$item->visible == 1 ? 'Add. ordenes' : null" :alignFooter="$item->default == 1 ? 'justify-between' : 'justify-end'" size="md">

                    <div class="w-full text-center">
                        @if (count($item->entornos))
                            @foreach ($item->entornos as $entorno)
                                <span
                                    class="text-[10px] p-1 rounded-lg text-textspancardproduct bg-fondospancardproduct">{{ $entorno->name }}</span>
                            @endforeach
                        @endif
                    </div>
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
            {{ __('Actualizar área') }}
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
                <x-label value="Área :" />
                <x-input class="block w-full" wire:model.defer="area.name" placeholder="Ingrese nombre de área..." />
                <x-jet-input-error for="area.name" />

                <x-label class="mt-2" value="Slug :" />
                <x-input class="block w-full" wire:model.defer="area.slug" />
                <x-jet-input-error for="area.slug" />

                <x-label class="mt-2 flex items-center gap-1 cursor-pointer" for="visible_edit">
                    <x-input wire:model.defer="area.visible" type="checkbox" id="visible_edit" value="1" />
                    Permitir vincular ordenes de trabajo.
                </x-label>
                <x-jet-input-error for="area.visible" />

                <x-label class="mt-6" value="Entornos atención" />
                @if (count($entornos))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($entornos as $item)
                            <x-label class="flex items-center gap-1 cursor-pointer bg-next-100 rounded-lg p-1"
                                for="entorno_edit_{{ $item->id }}">
                                <x-input wire:model.defer="selectedEntornos" name="entornos[]" type="checkbox"
                                    id="entorno_edit_{{ $item->id }}" value="{{ $item->id }}" />
                                {{ $item->name }}
                            </x-label>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedEntornos" />

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
            window.addEventListener('areas.confirmDelete', data => {
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
                        Livewire.emitTo('admin.areas.show-areas', 'delete', data.detail.id);

                    }
                })
            })
        })
    </script>
</div>

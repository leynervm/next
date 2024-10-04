<div class="relative">
    @if (count($typegarantias))
        <div class="pb-2">
            {{ $typegarantias->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="w-full flex gap-2 flex-wrap justify-start">
        @if (count($typegarantias))
            @foreach ($typegarantias as $item)
                @php
                    if ($item->datecode == 'MM') {
                        $timestring = $item->time > 1 ? ' MESES' : ' MES';
                    } else {
                        $timestring = $item->time > 1 ? ' AÑOS' : ' AÑO';
                    }
                @endphp
                <x-minicard :title="$item->name" :content="$item->time . $timestring" size="lg">
                    <x-slot name="buttons">
                        <div class="ml-auto">
                            @can('admin.almacen.typegarantias.edit')
                                <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled" />
                            @endcan

                            @can('admin.almacen.typegarantias.delete')
                                <x-button-delete onclick="confirmDelete({{ $item }})" wire:loading.attr="disabled" />
                            @endcan
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar garantía producto') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Descripción :" />
                    <x-input class="block w-full" wire:model.defer="typegarantia.name"
                        placeholder="Ingrese descripción garantía..." />
                    <x-jet-input-error for="typegarantia.name" />
                </div>

                <div>
                    <x-label value="Medida garantía :" />
                    <div class="w-full flex flex-wrap gap-2">
                        <x-input-radio class="py-2" for="edit_month" text="MESES">
                            <input wire:model.defer="typegarantia.datecode"
                                class="sr-only peer peer-disabled:opacity-25" type="radio" id="edit_month"
                                name="datecode" value="MM" />
                        </x-input-radio>
                        <x-input-radio class="py-2" for="edit_year" text="AÑOS">
                            <input wire:model.defer="typegarantia.datecode"
                                class="sr-only peer peer-disabled:opacity-25" type="radio" id="edit_year"
                                name="datecode" value="YYYY" />
                        </x-input-radio>
                    </div>
                    <x-jet-input-error for="typegarantia.datecode" />
                </div>

                <div>
                    <x-label value="Tiempo predeterminado (Meses):" />
                    <x-input type="number" class="block w-full input-number-none" wire:model.defer="typegarantia.time" step="1"
                        min="1" onkeypress="return validarNumero(event)" />
                    <x-jet-input-error for="typegarantia.time" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        function confirmDelete(typegarantia) {
            swal.fire({
                title: 'Eliminar tipo de garantía, ' + typegarantia.name,
                text: "Se eliminará el tipo de garantía de la base de datos, incluyendo todas las garantías vinculadas a los productos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(typegarantia.id);
                }
            })
        }
    </script>
</div>

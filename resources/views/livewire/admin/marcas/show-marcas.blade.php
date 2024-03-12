<div class="">
    @if ($marcas->hasPages())
        <div class="w-full pb-2">
            {{ $marcas->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-around md:justify-start mt-2">
        @if (count($marcas))
            @foreach ($marcas as $item)
                <x-minicard :title="$item->name" size="lg">
                    @if ($item->image)
                        <x-slot name="imagen">
                            <img class="w-full h-full object-scale-down"
                                src="{{ asset('storage/marcas/' . $item->image->url) }}" alt="">
                        </x-slot>
                    @endif

                    @canany(['admin.almacen.marcas.edit', 'admin.almacen.marcas.delete'])
                        <x-slot name="buttons">
                            @can('admin.almacen.marcas.edit')
                                <x-button-edit wire:loading.attr="disabled" wire:target="edit({{ $item->id }})"
                                    wire:click="edit({{ $item->id }})" />
                            @endcan
                            @can('admin.almacen.marcas.delete')
                                <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete({{ $item->id }})"
                                    onclick="confirmDelete({{ $item }})" />
                            @endcan
                        </x-slot>
                    @endcanany
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar marca') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="relative">
                    <div wire:loading.flex class="loading-overlay rounded hidden">
                        <x-loading-next />
                    </div>

                    @if (isset($logo))
                        <x-simple-card class="w-40 h-40 md:max-w-md mx-auto mb-1 border border-borderminicard">
                            <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                src="{{ $logo->temporaryUrl() }}" />
                        </x-simple-card>
                    @else
                        @if ($marca->image)
                            <x-simple-card class="w-40 h-40 md:max-w-md mx-auto mb-1 border border-borderminicard">
                                <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                    src="{{ asset('storage/marcas/' . $marca->image->url) }}" />
                            </x-simple-card>
                        @else
                            <x-icon-file-upload type="file" class="w-36 h-36 text-gray-300" />
                        @endif
                    @endif

                    <div class="w-full flex flex-wrap gap-2 justify-center">
                        @if (isset($logo))
                            <x-button class="inline-flex px-6" size="xs" wire:loading.attr="disabled"
                                wire:click="clearImage">LIMPIAR</x-button>
                        @else
                            <x-input-file for="{{ $identificador }}" :titulo="$marca->image ? 'CAMBIAR IMAGEN' : 'SELECCIONAR IMAGEN'" wire:loading.remove
                                wire:target="logo">
                                <input type="file" class="hidden" wire:model="logo" id="{{ $identificador }}"
                                    accept="image/jpg, image/jpeg, image/png" />
                            </x-input-file>
                        @endif
                    </div>
                </div>
                <x-jet-input-error wire:loading.remove wire:target="logo" for="logo" class="text-center" />

                <x-label class="mt-3" value="Marca :" />
                <x-input class="block w-full" wire:model.defer="marca.name" placeholder="Ingrese nombre de marca..." />
                <x-jet-input-error for="marca.name" />

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function confirmDelete(marca) {
            swal.fire({
                title: 'Eliminar marca ' + marca.name,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(marca.id);
                }
            })
        }
    </script>

</div>

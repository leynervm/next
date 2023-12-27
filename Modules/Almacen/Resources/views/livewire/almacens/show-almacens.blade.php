<div class="">
    {{-- @if (count($sucursales))
        <div class="pb-2">
            {{ $sucursales->links() }}
        </div>
    @endif --}}

    <div class="flex gap-2 flex-col ">
        @if (count($sucursales))
            @foreach ($sucursales as $suc)
                <div class="w-full p-3 rounded bg-fondominicard">
                    <div class="flex gap-1 items-center">
                        <h1
                            class="text-xs @if ($suc->status) text-red-500 @else text-colortitlecardnext @endif">
                            {{ $suc->name }}</h1>
                        @if ($suc->default)
                            <small class="block p-1 leading-3 text-xs rounded bg-green-500 text-white ">
                                Sucursal principal
                            </small>
                        @elseif ($suc->status)
                            <small class="block p-1 leading-3 text-xs rounded bg-red-500 text-white ">
                                Baja</small>
                        @endif
                    </div>
                    <div class="w-full flex flex-col gap-2 mt-2">
                        @if (count($suc->almacens))
                            <div class="w-full flex flex-wrap gap-2">
                                @foreach ($suc->almacens as $item)
                                    <div
                                        class="w-full xs:w-48 p-3 rounded bg-body shadow-sm shadow-shadowminicard flex flex-col gap-1 justify-between">
                                        <div class="w-full">
                                            <h1 class="text-xs text-colorminicard">{{ $item->name }}</h1>
                                            {{-- <p class="text-[10px] text-colorminicard">SUCURSAL :
                                                {{ $item->sucursal->name }}
                                            </p> --}}
                                        </div>
                                        <div class="w-full flex gap-1 items-center justify-between">
                                            @if ($item->default)
                                                <span class="">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-4 h-4 text-next-500 scale-125" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path fill="currentColor"
                                                            d="M18.9905 19H19M18.9905 19C18.3678 19.6175 17.2393 19.4637 16.4479 19.4637C15.4765 19.4637 15.0087 19.6537 14.3154 20.347C13.7251 20.9374 12.9337 22 12 22C11.0663 22 10.2749 20.9374 9.68457 20.347C8.99128 19.6537 8.52349 19.4637 7.55206 19.4637C6.76068 19.4637 5.63218 19.6175 5.00949 19C4.38181 18.3776 4.53628 17.2444 4.53628 16.4479C4.53628 15.4414 4.31616 14.9786 3.59938 14.2618C2.53314 13.1956 2.00002 12.6624 2 12C2.00001 11.3375 2.53312 10.8044 3.59935 9.73817C4.2392 9.09832 4.53628 8.46428 4.53628 7.55206C4.53628 6.76065 4.38249 5.63214 5 5.00944C5.62243 4.38178 6.7556 4.53626 7.55208 4.53626C8.46427 4.53626 9.09832 4.2392 9.73815 3.59937C10.8044 2.53312 11.3375 2 12 2C12.6625 2 13.1956 2.53312 14.2618 3.59937C14.9015 4.23907 15.5355 4.53626 16.4479 4.53626C17.2393 4.53626 18.3679 4.38247 18.9906 5C19.6182 5.62243 19.4637 6.75559 19.4637 7.55206C19.4637 8.55858 19.6839 9.02137 20.4006 9.73817C21.4669 10.8044 22 11.3375 22 12C22 12.6624 21.4669 13.1956 20.4006 14.2618C19.6838 14.9786 19.4637 15.4414 19.4637 16.4479C19.4637 17.2444 19.6182 18.3776 18.9905 19Z" />
                                                        <path stroke="#fff" fill="#fff"
                                                            d="M9 12.8929C9 12.8929 10.2 13.5447 10.8 14.5C10.8 14.5 12.6 10.75 15 9.5" />
                                                    </svg>
                                                </span>
                                            @endif
                                            <div class="w-full flex flex-wrap gap-2 justify-end">
                                                @if ($suc->status == 0)
                                                    <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                                                        wire:click="edit({{ $suc->id }},{{ $item->id }})" />
                                                @endif
                                                <x-button-delete wire:loading.attr="disabled"
                                                    wire:target="confirmDelete"
                                                    wire:click="$emit('almacens.confirmDelete', {{ $item }})" />
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if ($suc->isActive())
                            <div class="w-full flex pt-4 justify-end">
                                <x-button wire:click="openalmacen({{ $suc->id }})" wire:loading.attr="disabled"
                                    wire:target="save">
                                    {{ __('AGREGAR ALMACÉN') }}
                                </x-button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar almacén') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-1">
                <div class="w-full">
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre almacén..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="block">
                    <x-label-check for="editdefault">
                        <x-input wire:model.defer="default" value="1" type="checkbox" id="editdefault" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label-check>
                    <x-jet-input-error for="default" />
                    <x-jet-input-error for="sucursal.id" />
                </div>

                <div class="w-full flex pt-4 justify-end ">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('almacens.confirmDelete', data => {
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
                        Livewire.emitTo('almacen::almacens.show-almacens', 'delete', data.id);
                    }
                })
            })
        })
    </script>
</div>

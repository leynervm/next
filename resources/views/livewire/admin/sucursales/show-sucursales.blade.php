<div>
    @if ($sucursales->hasPages())
        <div class="pb-2">
            {{ $sucursales->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <x-table>
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>NOMBRE SUCURSAL</span>

                        <svg class="h-3 w-3" viewBox="0 0 10 11" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" stroke="currentColor" stroke-width="0.1">
                            <path
                                d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" />
                            <path
                                d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" />
                            <path
                                d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                stroke-width="0.3" />
                        </svg>
                    </button>
                </th>

                <th scope="col" class="p-2 font-medium text-left">
                    DIRECCIÓN</th>

                <th scope="col" class="p-2 font-medium">
                    CÓDIGO ANEXO</th>

                <th scope="col" class="p-2 font-medium">
                    DETALLE</th>

                <th scope="col" class="p-2 font-medium">
                    ESTADO</th>

                <th scope="col" class="p-2 font-medium text-center">
                    OPCIONES
                </th>
            </tr>
        </x-slot>
        @if (count($sucursales))
            <x-slot name="body">
                @foreach ($sucursales as $item)
                    <tr>
                        <td class="p-2 text-xs">
                            <a href="{{ route('admin.administracion.sucursales.edit', $item) }}"
                                class="text-linktable hover:text-hoverlinktable hover:underline transition-all ease-in-out duration-150">{{ $item->name }}</a>
                        </td>
                        <td class="p-2 text-[10px]">
                            <p>{{ $item->direccion }}</p>
                            @if ($item->ubigeo)
                                <p>
                                    {{ $item->ubigeo->distrito }},
                                    {{ $item->ubigeo->provincia }},
                                    {{ $item->ubigeo->region }} -
                                    {{ $item->ubigeo->ubigeo_reniec }}
                                </p>
                            @endif
                        </td>

                        <td class="p-2 text-[10px] text-center">
                            {{ $item->codeanexo }}
                        </td>

                        <td class="p-2 text-xs align-middle text-center">
                            @if ($item->default)
                                <small class="p-1 text-xs leading-3 rounded bg-green-500 text-white inline-block">
                                    Sucursal principal</small>
                            @else
                                {{-- @if ($item->status == 0)
                                    <x-button wire:click="$emit('sucursales.confirmSetDefault', {{ $item }})"
                                        class="inline-block" wire:loading.attr="disabled">
                                        {{ __('DEFINIR PRINCIPAL') }}
                                    </x-button>
                                @endif --}}
                            @endif
                        </td>

                        <td class="p-2 text-xs text-center align-middle">
                            @if ($item->status == 0)
                                <span class="inline-block text-center text-green-500">
                                    <svg class="w-4 h-4 scale-125" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path fill="currentColor"
                                            d="M19 12C19 13.6569 17.6569 15 16 15C14.3431 15 13 13.6569 13 12C13 10.3431 14.3431 9 16 9C17.6569 9 19 10.3431 19 12Z" />
                                        <path
                                            d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
                                    </svg>

                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M3 13.3333C3 13.3333 4.5 14 6.5 17C6.5 17 6.78485 16.5192 7.32133 15.7526M17 6C14.7085 7.14577 12.3119 9.55181 10.3879 11.8223" />
                                        <path d="M8 13.3333C8 13.3333 9.5 14 11.5 17C11.5 17 17 8.5 22 6" />
                                    </svg> --}}
                                </span>
                            @else
                                <span class="inline-block text-center text-neutral-200">
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M5 5L19 19" />
                                        <path
                                            d="M22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12Z" />
                                    </svg> --}}
                                    <svg class="w-4 h-4 scale-125" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path fill="currentColor"
                                            d="M11 12C11 13.6569 9.65685 15 8 15C6.34315 15 5 13.6569 5 12C5 10.3431 6.34315 9 8 9C9.65685 9 11 10.3431 11 12Z" />
                                        <path
                                            d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
                                    </svg>
                                </span>
                            @endif
                        </td>

                        <td class="p-2 align-middle text-center">
                            <x-button-delete wire:click="$emit('sucursales.confirmDelete', {{ $item }})"
                                wire:loading.attr="disabled" wire:target="delete" />
                            {{-- @if ($item->status == 0)
                                    <x-mini-button wire:click="$emit('sucursales.confirmDown', {{ $item }})"
                                        wire:loading.attr="disabled" wire:target="delete">
                                        <svg class="w-4 h-4 scale-125" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path fill="currentColor"
                                                d="M19 12C19 13.6569 17.6569 15 16 15C14.3431 15 13 13.6569 13 12C13 10.3431 14.3431 9 16 9C17.6569 9 19 10.3431 19 12Z" />
                                            <path
                                                d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
                                        </svg>
                                    </x-mini-button>
                                @else
                                    <x-mini-button
                                        class="hover:bg-neutral-500 hover:ring-neutral-300 focus:bg-neutral-500 focus:ring-neutral-300"
                                        wire:click="$emit('sucursales.confirmActive', {{ $item }})"
                                        wire:loading.attr="disabled">
                                        <svg class="w-4 h-4 scale-125 text-neutral-200" viewBox="0 0 24 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path fill="currentColor"
                                                d="M11 12C11 13.6569 9.65685 15 8 15C6.34315 15 5 13.6569 5 12C5 10.3431 6.34315 9 8 9C9.65685 9 11 10.3431 11 12Z" />
                                            <path
                                                d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
                                        </svg>
                                    </x-mini-button>
                                @endif --}}
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>

    <script>
        document.addEventListener('livewire:load', function() {

            Livewire.on('sucursales.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar sucursal con nombre : ' + data.name,
                    text: "Se eliminará un registro en la base de datos,",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.sucursales.show-sucursales', 'delete', data
                            .id);
                    }
                })
            })

            Livewire.on('sucursales.confirmSetDefault', data => {
                swal.fire({
                    title: 'Definir ' + data.name +
                        ' como sucursal principal ?',
                    text: "Se actualizarán los registro de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.sucursales.show-sucursales', 'setsucursaldefault',
                            data.id);
                    }
                })
            })

        })
    </script>

</div>

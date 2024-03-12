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
                    ESTADO</th>

                @canany(['admin.administracion.sucursales.edit', 'admin.administracion.sucursales.delete',
                    'admin.administracion.sucursales.restore'])
                    <th scope="col" class="p-2 font-medium text-center">
                        OPCIONES
                    </th>
                @endcanany
            </tr>
        </x-slot>
        @if (count($sucursales))
            <x-slot name="body">
                @foreach ($sucursales as $item)
                    <tr>
                        <td class="p-2 align-middle ">
                            <div class="flex items-center justify-start gap-1">
                                @if ($item->default)
                                    <x-icon-default />
                                @endif

                                @if ($item->trashed())
                                    <p class="inline-block text-linktable">
                                        {{ $item->name }}</p>
                                @else
                                    <a href="{{ route('admin.administracion.sucursales.edit', $item) }}"
                                        class="inline-block text-linktable hover:text-hoverlinktable transition-all ease-in-out duration-150">{{ $item->name }}</a>
                                @endif
                            </div>
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

                        <td class="p-2 text-center align-middle">
                            @if ($item->trashed())
                                <x-span-text text="INACTIVO" class="leading-3 !tracking-normal" type="red" />
                            @else
                                <x-span-text text="ACTIVO" class="leading-3 !tracking-normal" type="green" />
                            @endif
                        </td>

                        @canany(['admin.administracion.sucursales.edit', 'admin.administracion.sucursales.delete',
                            'admin.administracion.sucursales.restore'])
                            <td class="p-2 align-middle text-center space-x-3">
                                @can('admin.administracion.sucursales.edit')
                                    @if ($item->default == 0 && $item->deleted_at == null)
                                        <x-icon-default onclick="confirmSetDefault({{ $item }})"
                                            wire:loading.attr="disabled"
                                            class="!text-gray-400 inline-block cursor-pointer hover:!text-next-500" />
                                    @endif
                                @endcan

                                @if ($item->deleted_at == null)
                                    @can('admin.administracion.sucursales.delete')
                                        <x-button-delete onclick="confirmDelete({{ $item }})"
                                            wire:loading.attr="disabled" />
                                    @endcan
                                @else
                                    @can('admin.administracion.sucursales.restore')
                                        <button onclick="restoreSucursal({{ $item }})" wire:loading.attr="disabled">
                                            <svg class="w-4 h-4 scale-125 inline-block" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path fill="currentColor"
                                                    d="M11 12C11 13.6569 9.65685 15 8 15C6.34315 15 5 13.6569 5 12C5 10.3431 6.34315 9 8 9C9.65685 9 11 10.3431 11 12Z" />
                                                <path
                                                    d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
                                            </svg>
                                        </button>
                                    @endcan
                                @endif
                            </td>
                        @endcanany
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>

    <script>
        function confirmDelete(sucursal) {
            swal.fire({
                title: 'Deshabilitar sucursal, ' + sucursal.name,
                text: "El registro seleccionado dejará de estar disponible en la base de datos,",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(sucursal.id);
                }
            })
        }

        function confirmSetDefault(sucursal) {
            swal.fire({
                title: 'Seleccionar ' + sucursal.name + ' como principal ?',
                text: "Se actualizará un registro en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.setsucursaldefault(sucursal.id);
                }
            })
        }

        function restoreSucursal(sucursal) {
            swal.fire({
                title: 'Habilitar sucursal, ' + sucursal.name + ' ?',
                text: "Se actualizará un registro en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.restoreSucursal(sucursal.id);
                }
            })
        }

        document.addEventListener('livewire:load', function() {
            Livewire.on('sucursales.existUserSucursals', data => {
                swal.fire({
                    title: 'Sucursal, ' + data.name + ' se encuentra vinculado a usuarios  ?',
                    text: "Se deshabilitará la sucursal seleccionada de todos los usuarios vinculados.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(data.id, true);
                    }
                })
            })

        })
    </script>

</div>

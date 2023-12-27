<div>
    @if (count($users))
        <div class="pb-2">
            {{ $users->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <x-table>
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>NOMBRES</span>

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
                    CORREO</th>

                <th scope="col" class="p-2 font-medium">
                    VERIFICACIÓN</th>

                <th scope="col" class="p-2 font-medium">
                    STATUS</th>

                <th scope="col" class="p-2 font-medium">
                    ROL</th>

                <th scope="col" class="p-2 font-medium">
                    SUCURSALES</th>

                <th scope="col" class="p-2 font-medium text-left">
                    TEMA</th>

                <th scope="col" class="p-2 relative">
                    <span class="sr-only">OPCIONES</span>
                </th>
            </tr>
        </x-slot>
        @if (count($users))
            <x-slot name="body">
                @foreach ($users as $item)
                    <tr>
                        <td class="p-2 text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 flex-shrink-0 rounded-full overflow-hidden">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <img class="h-full w-full object-cover block"
                                            src="{{ $item->profile_photo_url }}" alt="{{ $item->name }}" />
                                    @endif
                                </div>
                                <a class="w-full text-linktable hover:underline hover:text-hoverlinktable"
                                    href="{{ route('admin.users.edit', $item) }}">
                                    {{ $item->name }}</a>
                            </div>
                        </td>

                        <td class="p-2 text-xs">
                            {{ $item->email }}
                        </td>

                        <td class="p-2 text-xs text-center">
                            @if ($item->email_verified_at)
                                <small
                                    class="p-1 text-xs inline-block leading-3 rounded bg-green-100 text-green-600">Verificado</small>
                            @else
                                <small class="p-1 text-xs inline-block leading-3 rounded bg-red-100 text-red-600">No
                                    verificado</small>
                            @endif
                        </td>

                        <td class="p-2 text-xs text-center">
                            @if ($item->deleted_at)
                                <small
                                    class="p-1 text-xs inline-block leading-3 rounded bg-red-100 text-red-600">Verificado</small>
                            @else
                                <small
                                    class="p-1 text-xs inline-block leading-3 rounded bg-green-100 text-green-600">Activo</small>
                            @endif
                        </td>

                        <td class="p-2 text-xs">
                            @if (count($item->roles))
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($item->roles as $rol)
                                        <div
                                            class="inline-flex items-center justify-center gap-1 bg-fondospancardproduct text-textspancardproduct p-1 rounded">
                                            <span class="w-3 h-3 block">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                                </svg>
                                            </span>
                                            <span class="text-[10px]">{{ $rol->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td class="p-2 text-xs">
                            @if (count($item->sucursals))
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($item->sucursals as $suc)
                                        <div
                                            class="inline-flex items-center justify-center gap-1 bg-fondospancardproduct text-textspancardproduct p-1 rounded">
                                            <span class="w-3 h-3 block">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                                </svg>
                                            </span>
                                            <span class="text-[10px]">{{ $suc->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="p-2 text-xs">
                            {{ $item->theme_id }}
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="flex gap-1 justify-center items-center">
                                <x-button-delete />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('users.confirmDelete', data => {
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
                        Livewire.emitTo('admin.users.show-users', 'delete', data
                            .id);
                    }
                })
            })
        })
    </script>
</div>

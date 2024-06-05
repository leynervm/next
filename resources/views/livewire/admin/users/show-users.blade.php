<div>
    <div wire:loading.flex class="loading-overlay rounded hidden overflow-hidden fixed">
        <x-loading-next />
    </div>

    @if ($users->hasPages())
        <div class="pb-2">
            {{ $users->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex items-center gap-2 mt-4">
        <div class="w-full max-w-sm">
            <x-label value="Buscar usuario :" />
            <div class="relative flex items-center">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mx-3 text-next-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <x-input placeholder="Buscar documento, nombres del usuario..." class="block w-full pl-9"
                    wire:model.lazy="search">
                </x-input>
            </div>
        </div>

        @if (auth()->user()->isAdmin())
            @if (count($sucursals) > 1)
                <div class="w-full xs:max-w-sm">
                    <x-label value="Filtrar Sucursal :" />
                    <div class="relative" id="parentsearchsucursal" x-data="{ searchsucursal: @entangle('searchsucursal') }" x-init="selectSearchsucursal">
                        <x-select class="block w-full" x-ref="searchsuc" id="searchsucursal"
                            data-minimum-results-for-search="3" data-placeholder="null">
                            <x-slot name="options">
                                @foreach ($sucursals as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="searchsucursal" />
                </div>
            @endif
        @endif
    </div>

    <x-table class="mt-1">
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
                    ACCESO</th>
                <th scope="col" class="p-2 font-medium">ROLES</th>
                <th scope="col" class="p-2 font-medium">
                    SUCURSAL</th>
                <th scope="col" class="p-2 font-medium text-left">
                    TEMA</th>
                <th scope="col" class="p-2 font-medium">
                    STATUS</th>
                @can('admin.users.delete')
                    <th scope="col" class="p-2 relative">
                        <span class="sr-only">OPCIONES</span>
                    </th>
                @endcan
            </tr>
        </x-slot>
        @if (count($users) > 0)
            <x-slot name="body">
                @foreach ($users as $item)
                    <tr>
                        <td class="p-2">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 flex-shrink-0 rounded-full overflow-hidden">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <img class="h-full w-full object-cover block"
                                            src="{{ $item->profile_photo_url }}" alt="{{ $item->name }}" />
                                    @endif
                                </div>

                                @can('admin.users.edit')
                                    @if ($item->trashed())
                                        <div>
                                            <p class="w-full block text-linktable">{{ $item->document }}</p>
                                            <p class="w-full block text-linktable">{{ $item->name }}</p>
                                        </div>
                                    @else
                                        <a class="w-full inline-block text-linktable hover:text-hoverlinktable"
                                            href="{{ route('admin.users.edit', $item) }}">
                                            {{ $item->document }}
                                            <p>{{ $item->name }}</p>
                                            @if (Module::isEnabled('Employer'))
                                                @if ($item->employer)
                                                    @if ($item->employer->areawork)
                                                        <p>AREA : {{ $item->employer->areawork->name }}</p>
                                                    @endif
                                                @endif
                                            @endif
                                        </a>
                                    @endif
                                @endcan

                                @cannot('admin.users.edit')
                                    <h1 class="w-full inline-block text-linktable">
                                        {{ $item->document }}
                                        <p>{{ $item->name }}</p>
                                    </h1>
                                @endcannot
                            </div>
                        </td>

                        <td class="p-2">
                            {{ $item->email }}
                        </td>

                        <td class="p-2 text-center">
                            @if ($item->email_verified_at)
                                <x-icon-default class="inline-block" />
                            @else
                                <x-span-text text="NO VERIFICADO" type="red" class="leading-3 !tracking-normal" />
                            @endif
                        </td>

                        <td class="p-2 text-center">
                            @if ($item->isAdmin())
                                <x-span-text text="SUPER ADMIN" type="blue"
                                    class="leading-3 !tracking-normal inline-block" />
                            @elseif ($item->employer)
                                <x-span-text text="DASHBOARD" type="next"
                                    class="leading-3 !tracking-normal inline-block" />
                            @else
                                <x-span-text text="WEB" class="leading-3 !tracking-normal inline-block" />
                            @endif
                        </td>

                        <td class="p-2">
                            @if (count($item->roles))
                                <div class="flex flex-wrap justify-center items-center gap-1">
                                    @foreach ($item->roles as $rol)
                                        <div
                                            class="inline-flex items-center justify-center gap-1 bg-fondospancardproduct text-textspancardproduct p-1 rounded-md">
                                            <span class="w-3 h-3 block">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-full h-full scale-125" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M12.5 22H6.59087C5.04549 22 3.81631 21.248 2.71266 20.1966C0.453365 18.0441 4.1628 16.324 5.57757 15.4816C7.97679 14.053 10.8425 13.6575 13.5 14.2952">
                                                    </path>
                                                    <path
                                                        d="M16.5 6.5C16.5 8.98528 14.4853 11 12 11C9.51472 11 7.5 8.98528 7.5 6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5Z">
                                                    </path>
                                                    <path stroke-width=".3" fill="currentColor"
                                                        d="M16.0803 21.8573L15.7761 22.5428L15.7761 22.5428L16.0803 21.8573ZM15.1332 20.8425L14.4337 21.113H14.4337L15.1332 20.8425ZM21.8668 20.8425L22.5663 21.113L22.5663 21.113L21.8668 20.8425ZM20.9197 21.8573L21.2239 22.5428L21.2239 22.5428L20.9197 21.8573ZM20.9197 16.5177L21.2239 15.8322L20.9197 16.5177ZM21.8668 17.5325L22.5663 17.262L22.5663 17.262L21.8668 17.5325ZM16.0803 16.5177L15.7761 15.8322L16.0803 16.5177ZM15.1332 17.5325L14.4337 17.262L15.1332 17.5325ZM16 16.375C16 16.7892 16.3358 17.125 16.75 17.125C17.1642 17.125 17.5 16.7892 17.5 16.375H16ZM19.5 16.375C19.5 16.7892 19.8358 17.125 20.25 17.125C20.6642 17.125 21 16.7892 21 16.375H19.5ZM17.625 17.125H19.375V15.625H17.625V17.125ZM19.375 21.25H17.625V22.75H19.375V21.25ZM17.625 21.25C17.2063 21.25 16.9325 21.2495 16.7222 21.2342C16.5196 21.2193 16.4338 21.1936 16.3845 21.1718L15.7761 22.5428C16.0484 22.6637 16.3272 22.7093 16.6128 22.7302C16.8905 22.7505 17.2283 22.75 17.625 22.75V21.25ZM14.25 19.1875C14.25 19.6147 14.2496 19.9702 14.2682 20.2611C14.2871 20.5577 14.3278 20.839 14.4337 21.113L15.8328 20.5721C15.8054 20.5014 15.7795 20.3921 15.7651 20.1658C15.7504 19.9336 15.75 19.6339 15.75 19.1875H14.25ZM16.3845 21.1718C16.1471 21.0664 15.9427 20.8566 15.8328 20.5721L14.4337 21.113C14.6789 21.7474 15.1559 22.2676 15.7761 22.5428L16.3845 21.1718ZM21.25 19.1875C21.25 19.6339 21.2496 19.9336 21.2349 20.1658C21.2205 20.3921 21.1946 20.5014 21.1672 20.5721L22.5663 21.113C22.6722 20.839 22.7129 20.5577 22.7318 20.2611C22.7504 19.9702 22.75 19.6147 22.75 19.1875H21.25ZM19.375 22.75C19.7717 22.75 20.1095 22.7505 20.3872 22.7302C20.6728 22.7093 20.9516 22.6637 21.2239 22.5428L20.6155 21.1718C20.5662 21.1936 20.4804 21.2193 20.2778 21.2342C20.0675 21.2495 19.7937 21.25 19.375 21.25V22.75ZM21.1672 20.5721C21.0573 20.8566 20.8529 21.0664 20.6155 21.1718L21.2239 22.5428C21.8441 22.2676 22.3211 21.7474 22.5663 21.113L21.1672 20.5721ZM19.375 17.125C19.7937 17.125 20.0675 17.1255 20.2778 17.1408C20.4804 17.1557 20.5662 17.1814 20.6155 17.2032L21.2239 15.8322C20.9516 15.7113 20.6728 15.6657 20.3872 15.6448C20.1095 15.6245 19.7717 15.625 19.375 15.625V17.125ZM22.75 19.1875C22.75 18.7603 22.7504 18.4048 22.7318 18.1139C22.7129 17.8173 22.6722 17.536 22.5663 17.262L21.1672 17.8029C21.1946 17.8736 21.2205 17.9829 21.2349 18.2092C21.2496 18.4414 21.25 18.7411 21.25 19.1875H22.75ZM20.6155 17.2032C20.8529 17.3086 21.0573 17.5184 21.1672 17.8029L22.5663 17.262C22.3211 16.6277 21.8441 16.1074 21.2239 15.8322L20.6155 17.2032ZM17.625 15.625C17.2283 15.625 16.8905 15.6245 16.6128 15.6448C16.3272 15.6657 16.0484 15.7113 15.7761 15.8322L16.3845 17.2032C16.4338 17.1814 16.5196 17.1557 16.7222 17.1408C16.9325 17.1255 17.2063 17.125 17.625 17.125V15.625ZM15.75 19.1875C15.75 18.7411 15.7504 18.4414 15.7651 18.2092C15.7795 17.9829 15.8054 17.8736 15.8328 17.8029L14.4337 17.262C14.3278 17.536 14.2871 17.8173 14.2682 18.1139C14.2496 18.4048 14.25 18.7603 14.25 19.1875H15.75ZM15.7761 15.8322C15.1559 16.1074 14.6789 16.6277 14.4337 17.262L15.8328 17.8029C15.9427 17.5184 16.1471 17.3086 16.3845 17.2032L15.7761 15.8322ZM17.5 16.375V14.6875H16V16.375H17.5ZM19.5 14.6875V16.375H21V14.6875H19.5ZM18.5 13.75C19.0782 13.75 19.5 14.1952 19.5 14.6875H21C21 13.3158 19.8548 12.25 18.5 12.25V13.75ZM17.5 14.6875C17.5 14.1952 17.9218 13.75 18.5 13.75V12.25C17.1452 12.25 16 13.3158 16 14.6875H17.5Z">
                                                    </path>
                                                </svg>
                                            </span>
                                            <span class="text-[10px]">{{ $rol->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="p-2 text-center">
                            @if ($item->sucursal)
                                {{ $item->sucursal->name }}
                                @if ($item->sucursal->trashed())
                                    <p><x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal" /></p>
                                @endif
                            @endif
                        </td>
                        <td class="p-2">
                            {{ $item->theme_id }}
                        </td>
                        <td class="p-2 text-center">
                            @if ($item->trashed())
                                <x-span-text text="BAJA" type="red" class="leading-3 !tracking-normal" />
                            @else
                                <x-span-text text="ACTIVO" type="green" class="leading-3 !tracking-normal" />
                            @endif
                        </td>
                        @canany(['admin.users.delete', 'admin.users.restore'])
                            <td class="p-2 text-center">
                                @if (!$item->isAdmin())
                                    @if ($item->trashed())
                                        <p class="text-center">
                                            <x-span-text text="ELIMINADO" class="leading-3 !tracking-normal" />
                                        </p>
                                        @can('admin.users.restore')
                                            <x-button-toggle class="text-gray-400 hover:text-gray-200 focus:text-gray-200"
                                                onclick="restoreUser({{ $item }})" wire:loading.attr="disabled"
                                                wire:key="restoreuser{{ $item->id }}">DESACTIVAR</x-button-toggle>
                                        @endcan
                                    @else
                                        <x-button-delete wire:loading.attr="disabled"
                                            onclick="confirmDelete({{ $item }})"
                                            wire:key="deleteuser_{{ $item->id }}" />
                                    @endif
                                @endif
                            </td>
                        @endcanany
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>

    <script>
        function confirmDelete(user) {
            swal.fire({
                title: 'Realizar baja de usuario ' + user.name,
                text: "Se dará de baja un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(user.id);
                }
            })
        }

        function restoreUser(user) {
            swal.fire({
                title: 'Habilitar usuario ' + user.name + ' ?',
                text: "Se actualizará un registro en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.restoreuser(user.id);
                }
            })
        }

        function selectSearchsucursal() {
            this.selectSS = $(this.$refs.searchsuc).select2();
            this.selectSS.val(this.searchsucursal).trigger("change");
            this.selectSS.on("select2:select", (event) => {
                this.searchsucursal = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchsucursal", (value) => {
                this.selectSS.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectSS.select2().val(this.searchsucursal).trigger('change');
            });
        }
    </script>
</div>

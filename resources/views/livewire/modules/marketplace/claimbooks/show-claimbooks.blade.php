<div x-data="showreclamos">
    <div wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    @if ($claimbooks->hasPages())
        <div class="pb-2">
            {{ $claimbooks->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex flex-col xs:flex-row xs:flex-wrap gap-2 pb-2">
        <div class="w-full sm:max-w-sm">
            <x-label value="Buscar :" />
            <x-input wire:model.lazy="search" class="w-full" placeholder="Buscar ..." />
        </div>

        <div class="w-full xs:w-auto">
            <x-label value="Fecha :" />
            <x-input type="date" wire:model.lazy="searchdate" class="w-full block " />
        </div>

        @if (count($typereclamos) > 1)
            <div class="w-full xs:w-52">
                <x-label value="Tipo :" />
                <div id="parentsearchtypepayment" class="relative" x-init="type">
                    <x-select id="searchtypepayment" x-ref="selecttype" class="w-full" data-placeholder="null">
                        <x-slot name="options">
                            @foreach ($typereclamos as $item)
                                <option value="{{ $item->tipo_reclamo }}">{{ $item->tipo_reclamo }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
            </div>
        @endif

        {{-- @if (count($sucursals) > 1) --}}
        <div class="w-full xs:w-60">
            <x-label value="Punto venta :" />
            <div id="parentsearchuser" class="relative" x-init="sucursal">
                <x-select id="searchuser" class="w-full" x-ref="selectsucursal" data-placeholder="null">
                    <x-slot name="options">
                        @foreach ($sucursals as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
        </div>
        {{-- @endif --}}
    </div>


    <x-table>
        <x-slot name="header">
            <th class="p-2">N° SOLICITUD</th>
            <th class="p-2">DATOS DE LA PERSONA</th>
            <th class="p-2">TIPO DEL RECLAMO</th>
            <th class="p-2">BIEN CONTRATADO</th>
            <th class="p-2">CANAL DE VENTA</th>
            <th class="p-2">PUNTO VENTA</th>
            <th class="p-2">PEDIDO</th>
            <th class="p-2">OPCIONES</th>
        </x-slot>
        @if (count($claimbooks) > 0)
            <x-slot name="body">
                @foreach ($claimbooks as $item)
                    <tr>
                        <td class="p-2">
                            {{ $item->serie }}-{{ $item->correlativo }}
                            <p class="text-[10px]">{{ formatDate($item->date, "DD MMMM Y") }}</p>
                        </td>
                        <td class="p-2">
                            <p>
                                {{ $item->document }} \ {{ $item->name }}
                            </p>
                            <p class="text-[10px] text-colorsubtitleform">{{ $item->direccion }}</p>
                            <p class="text-colorsubtitleform">
                                {{ $item->email }} \ {{ formatTelefono($item->telefono) }}
                            </p>

                            @if ($item->isMenorEdad())
                                <h1 class="font-semibold text-[10px] mt-2">DATOS DEL APODERADO</h1>
                                <p>
                                    {{ $item->document_apoderado }} \ {{ $item->name_apoderado }}
                                </p>
                                <p class="text-[10px] text-colorsubtitleform">{{ $item->direccion_apoderado }}</p>
                                <p class="text-colorsubtitleform">
                                    {{ formatTelefono($item->telefono_apoderado) }}</p>
                            @endif
                        </td>
                        <td class="p-2">
                            <p class="font-semibold text-colorsubtitleform">
                                {{ $item->tipo_reclamo }}</p>
                            <p class="text-[10px] leading-3">
                                {{ $item->detalle_reclamo }}
                            </p>
                        </td>
                        <td class="p-2">
                            <p class="font-semibold text-colorsubtitleform">
                                {{ $item->biencontratado }}</p>
                            <p class="text-[10px] leading-3">
                                {{ $item->descripcion_producto_servicio }}
                            </p>
                        </td>
                        <td class="p-2">{{ $item->channelsale }}</td>
                        <td class="p-2">
                            @if ($item->sucursal)
                                {{ $item->sucursal->name }}
                                <p class="text-[10px] text-colorsubtitleform">
                                    {{ $item->sucursal->direccion }} -
                                    {{ $item->sucursal->ubigeo->distrito }} /
                                    {{ $item->sucursal->ubigeo->provincia }} /
                                    {{ $item->sucursal->ubigeo->region }}
                                </p>
                            @endif
                        </td>
                        <td class="p-2">{{ $item->pedido }}</td>
                        <td class="p-2 text-center">
                            <a href="{{ route('claimbook.print.pdf', $item) }}" target="_blank"
                                class="p-1.5 bg-red-800 text-white inline-block rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 block scale-110 " xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path
                                        d="M7 18V15.5M7 15.5V14C7 13.5286 7 13.2929 7.15377 13.1464C7.30754 13 7.55503 13 8.05 13H8.75C9.47487 13 10.0625 13.5596 10.0625 14.25C10.0625 14.9404 9.47487 15.5 8.75 15.5H7ZM21 13H19.6875C18.8625 13 18.4501 13 18.1938 13.2441C17.9375 13.4882 17.9375 13.881 17.9375 14.6667V15.5M17.9375 18V15.5M17.9375 15.5H20.125M15.75 15.5C15.75 16.8807 14.5747 18 13.125 18C12.7979 18 12.6343 18 12.5125 17.933C12.2208 17.7726 12.25 17.448 12.25 17.1667V13.8333C12.25 13.552 12.2208 13.2274 12.5125 13.067C12.6343 13 12.7979 13 13.125 13C14.5747 13 15.75 14.1193 15.75 15.5Z" />
                                    <path
                                        d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10" />
                                    <path
                                        d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('showreclamos', () => ({
                searchtype: @entangle('searchtype'),
                searchsucursal: @entangle('searchsucursal')

            }))
        })

        function type() {
            this.selectTP = $(this.$refs.selecttype).select2();
            this.selectTP.val(this.searchtype).trigger("change");
            this.selectTP.on("select2:select", (event) => {
                this.searchtype = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchtype", (value) => {
                this.selectTP.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTP.select2().val(this.searchtype).trigger('change');
            });
        }

        function sucursal() {
            this.selectSU = $(this.$refs.selectsucursal).select2();
            this.selectSU.val(this.searchsucursal).trigger("change");
            this.selectSU.on("select2:select", (event) => {
                this.searchsucursal = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchsucursal", (value) => {
                this.selectSU.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectSU.select2().val(this.searchsucursal).trigger('change');
            });
        }
    </script>
</div>

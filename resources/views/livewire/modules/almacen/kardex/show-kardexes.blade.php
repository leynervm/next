<div class="relative" x-data="{ loading: false }">
    <div class="flex flex-col xs:flex-row xs:flex-wrap gap-2">
        <div class="w-full xs:max-w-md">
            <x-label value="Buscar producto :" />
            <x-input type="text" wire:model.lazy="searchproducto" class="w-full block xs:max-w-md"
                placeholder="Buscar producto..." />
        </div>

        <div class="w-full xs:w-auto">
            <x-label value="Fecha :" />
            <x-input type="date" wire:model.lazy="date" class="w-full block" />
        </div>

        <div class="w-full xs:w-auto">
            <x-label value="Hasta :" />
            <x-input type="date" wire:model.lazy="dateto" class="w-full block xs:w-auto" />
        </div>

        <div class="w-full xs:w-52">
            <x-label value="Almacén :" />
            <div x-data="{ searchalmacen: @entangle('searchalmacen') }" x-init="select2Almacen" id="parentsearchalmacen" class="relative" wire:ignore>
                <x-select id="searchalmacen" x-ref="selectalmacen" data-placeholder="null">
                    <x-slot name="options">
                        @if (count($almacens))
                            @foreach ($almacens as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
        </div>

        @if (count($users) > 1)
            <div class="w-full xs:w-60">
                <x-label value="Usuario :" />
                <div x-data="{ searchuser: @entangle('searchuser') }" x-init="select2UserAlpine" class="relative" id="parentsearchuser" wire:ignore>
                    <x-select id="searchuser" x-ref="select" data-placeholder="null">
                        <x-slot name="options">
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
            </div>
        @endif

        @if (count($sucursals) > 1)
            <div class="w-full xs:w-full xs:max-w-xs">
                <x-label value="Sucursal :" />
                <div x-data="{ searchsucursal: @entangle('searchsucursal') }" x-init="select2SucursalAlpine" id="parentsearchsucursal" class="relative"
                    wire:ignore>
                    <x-select id="searchsucursal" x-ref="selectsucursal" data-placeholder="null">
                        <x-slot name="options">
                            @if (count($sucursals))
                                @foreach ($sucursals as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
            </div>
        @endif
    </div>

    @if ($kardexes->hasPages())
        <div class="pt-3 pb-1">
            {{ $kardexes->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <x-table class="mt-1">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>FECHA</span>
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
                <th scope="col" class="p-2 font-medium">
                    PRODUCTO</th>
                <th scope="col" class="p-2 font-medium">
                    STOCK ANTERIOR</th>
                <th scope="col" class="p-2 font-medium text-left">
                    CANTIDAD</th>
                <th scope="col" class="p-2 font-medium text-center">
                    NUEVO STOCK</th>
                <th scope="col" class="p-2 font-medium">
                    TIPO MOVIMIENTO</th>
                <th scope="col" class="p-2 font-medium">
                    REFERENCIA</th>
                <th scope="col" class="p-2 font-medium">
                    SUCURSAL</th>
            </tr>
        </x-slot>
        @if (count($kardexes))
            <x-slot name="body">
                @foreach ($kardexes as $item)
                    <tr>
                        <td class="p-2 text-xs uppercase">
                            {{ formatDate($item->date) }}
                        </td>
                        <td class="p-2 text-xs text-justify">
                            <p>{{ $item->producto->name }}</p>
                            <x-span-text :text="$item->almacen->name" class="leading-3 !tracking-normal" />
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ formatDecimalOrInteger($item->oldstock) }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            @if ($item->simbolo == '+')
                                <span
                                    class="p-0.5 px-1 leading-3 rounded bg-green-500 inline-flex items-center gap-1 text-white">
                                    <p class="font-medium text-xs">{{ $item->simbolo }}
                                        {{ formatDecimalOrInteger($item->cantidad) }}</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M15 16.3265L16.409 17.8131C17.159 18.6044 17.534 19 18 19C18.466 19 18.841 18.6044 19.591 17.8131L21 16.3265M18 18.9128L18 14.5377C18 12.3042 18 11.1875 17.5532 10.2028C17.1063 9.21804 16.2659 8.48266 14.585 7.01192L14 6.5" />
                                        <path
                                            d="M3 6.5C3 5.27489 3 4.66233 3.23842 4.1944C3.44815 3.78279 3.78279 3.44815 4.1944 3.23842C4.66233 3 5.27489 3 6.5 3C7.72511 3 8.33767 3 8.8056 3.23842C9.21721 3.44815 9.55185 3.78279 9.76158 4.1944C10 4.66233 10 5.27489 10 6.5C10 7.72511 10 8.33767 9.76158 8.8056C9.55185 9.21721 9.21721 9.55185 8.8056 9.76158C8.33767 10 7.72511 10 6.5 10C5.27489 10 4.66233 10 4.1944 9.76158C3.78279 9.55185 3.44815 9.21721 3.23842 8.8056C3 8.33767 3 7.72511 3 6.5Z" />
                                        <path
                                            d="M3 17.5C3 16.2749 3 15.6623 3.23842 15.1944C3.44815 14.7828 3.78279 14.4481 4.1944 14.2384C4.66233 14 5.27489 14 6.5 14C7.72511 14 8.33767 14 8.8056 14.2384C9.21721 14.4481 9.55185 14.7828 9.76158 15.1944C10 15.6623 10 16.2749 10 17.5C10 18.7251 10 19.3377 9.76158 19.8056C9.55185 20.2172 9.21721 20.5519 8.8056 20.7616C8.33767 21 7.72511 21 6.5 21C5.27489 21 4.66233 21 4.1944 20.7616C3.78279 20.5519 3.44815 20.2172 3.23842 19.8056C3 19.3377 3 18.7251 3 17.5Z" />
                                    </svg>
                                </span>
                            @else
                                <span
                                    class="p-0.5 px-1 leading-3 rounded bg-red-500 inline-flex items-center gap-1 text-white">
                                    <p class="font-medium text-xs">{{ $item->simbolo }}
                                        {{ formatDecimalOrInteger($item->cantidad) }}</p>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M15 7.67359L16.409 6.18704C17.159 5.39576 17.534 5.00012 18 5.00012C18.466 5.00012 18.841 5.39576 19.591 6.18704L21 7.67359M18 5.08736L18 9.46244C18 11.6959 18 12.8126 17.5532 13.7974C17.1063 14.7821 16.2659 15.5175 14.585 16.9882L14 17.5001" />
                                        <path
                                            d="M3 6.5C3 5.27489 3 4.66233 3.23842 4.1944C3.44815 3.78279 3.78279 3.44815 4.1944 3.23842C4.66233 3 5.27489 3 6.5 3C7.72511 3 8.33767 3 8.8056 3.23842C9.21721 3.44815 9.55185 3.78279 9.76158 4.1944C10 4.66233 10 5.27489 10 6.5C10 7.72511 10 8.33767 9.76158 8.8056C9.55185 9.21721 9.21721 9.55185 8.8056 9.76158C8.33767 10 7.72511 10 6.5 10C5.27489 10 4.66233 10 4.1944 9.76158C3.78279 9.55185 3.44815 9.21721 3.23842 8.8056C3 8.33767 3 7.72511 3 6.5Z" />
                                        <path
                                            d="M3 17.5C3 16.2749 3 15.6623 3.23842 15.1944C3.44815 14.7828 3.78279 14.4481 4.1944 14.2384C4.66233 14 5.27489 14 6.5 14C7.72511 14 8.33767 14 8.8056 14.2384C9.21721 14.4481 9.55185 14.7828 9.76158 15.1944C10 15.6623 10 16.2749 10 17.5C10 18.7251 10 19.3377 9.76158 19.8056C9.55185 20.2172 9.21721 20.5519 8.8056 20.7616C8.33767 21 7.72511 21 6.5 21C5.27489 21 4.66233 21 4.1944 20.7616C3.78279 20.5519 3.44815 20.2172 3.23842 19.8056C3 19.3377 3 18.7251 3 17.5Z" />
                                    </svg>
                                </span>
                            @endif
                        </td>
                        <td class="p-2 text-center">
                            {{ formatDecimalOrInteger($item->newstock) }}
                        </td>
                        <td class="p-2 text-center align-middle">
                            {{ $item->detalle }}
                            {{-- <x-span-text :text="$item->detalle" :type="$item->simbolo == '+' ? 'green' : 'orange'" class="leading-3 !tracking-normal" /> --}}
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->reference }}
                        </td>
                        <td class="p-2 text-center">
                            <p>{{ $item->sucursal->name }}</p>
                            <p class="text-colorsubtitleform text-[10px] leading-3">
                                USUARIO : {{ $item->user->name }}</p>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
        <x-slot name="loading">
            <div x-show="loading" wire:loading wire:loading.flex class="loading-overlay rounded">
                <x-loading-next />
            </div>
        </x-slot>
    </x-table>

    <script>
        function select2Almacen() {
            this.select2 = $(this.$refs.selectalmacen).select2();
            this.select2.val(this.searchalmacen).trigger("change");
            this.select2.on("select2:select", (event) => {
                this.searchalmacen = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchalmacen", (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        function select2SucursalAlpine() {
            this.select2S = $(this.$refs.selectsucursal).select2();
            this.select2S.val(this.searchsucursal).trigger("change");
            this.select2S.on("select2:select", (event) => {
                this.searchsucursal = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchsucursal", (value) => {
                this.select2S.val(value).trigger("change");
            });
        }

        function select2UserAlpine() {
            this.select2U = $(this.$refs.select).select2();
            this.select2U.val(this.searchuser).trigger("change");
            this.select2U.on("select2:select", (event) => {
                this.searchuser = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchuser", (value) => {
                this.select2U.val(value).trigger("change");
            });
        }
    </script>
</div>

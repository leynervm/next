<div>
    <div wire:loading.flex class="loading-overlay hidden fixed">
        <x-loading-next />
    </div>

    @if ($compras->hasPages())
        <div class="pb-2">
            {{ $compras->links() }}
        </div>
    @endif

    <div class="flex flex-wrap items-center gap-1 mt-4">
        <div class="w-full xs:max-w-sm">
            <x-label value="Buscar proveedor compra :" />
            <div class="w-full relative flex items-center">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mx-3 text-next-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <x-input placeholder="Buscar proveedor, referencia compra..." class="block w-full pl-9"
                    wire:model.lazy="search">
                </x-input>
            </div>
        </div>

        <div class="w-full xs:max-w-xs">
            <x-label value="Fecha compra :" />
            <x-input type="date" name="date" wire:model.lazy="date" id="search" class="w-full block" />
        </div>

        @if (count($typepayments) > 1)
            <div class="w-full xs:max-w-xs">
                <x-label value="Tipo pago :" />
                <div class="relative" x-data="{ typepayment: @entangle('typepayment') }" id="parentsearchpy" x-init="select2Payment">
                    <x-select class="w-full block" id="searchpy" x-ref="selectpt" data-placeholder="null">
                        <x-slot name="options">
                            @foreach ($typepayments as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
            </div>
        @endif
    </div>

    @can('admin.almacen.compras.deletes')
        <div class="w-full mt-1">
            <x-label-check for="eliminados">
                <x-input wire:model.lazy="deletes" name="deletes" value="true" type="checkbox" id="eliminados" />
                MOSTRAR COMPRAS ELIMINADAS
            </x-label-check>
        </div>
    @endcan

    <x-table class="mt-1 relative">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>ID</span>
                        <svg class="h-3 w-3 text-white" viewBox="0 0 10 11" fill="none"
                            xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="0.1">
                            <path fill="currentColor"
                                d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" />
                            <path fill="currentColor"
                                d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" />
                            <path fill="currentColor"
                                d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                stroke-width="0.3" />
                        </svg>
                    </button>
                </th>
                <th scope="col" class="p-2 font-medium">
                    PROVEEDOR
                </th>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>FECHA COMPRA</span>
                        <svg class="h-3 w-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg"
                            stroke="currentColor" stroke-width="0.1" fill="currentColor">
                            <path fill="currentColor"
                                d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" />
                            <path fill="currentColor"
                                d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" />
                            <path fill="currentColor"
                                d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                stroke-width="0.3" />
                        </svg>
                    </button>
                </th>
                <th scope="col" class="p-2 font-medium">
                    DOC. REFERENCIA</th>
                <th scope="col" class="p-2 font-medium">
                    TOTAL COMPRA</th>
                <th scope="col" class="p-2 font-medium">
                    TIPO MONEDA</th>
                {{-- <th scope="col" class="p-2 font-medium">
                    TIPO CAMBIO</th> --}}
                <th scope="col" class="p-2 font-medium">
                    TIPO PAGO</th>
                <th scope="col" class="p-2 font-medium">
                    ESTADO PAGO</th>
                <th scope="col" class="p-2 font-medium">
                    SUCURSAL</th>


                {{-- <th scope="col" class="p-2 relative">
                    <span class="sr-only">OPCIONES</span>
                </th> --}}
            </tr>
        </x-slot>
        @if (count($compras) > 0)
            <x-slot name="body">
                @foreach ($compras as $item)
                    <tr>
                        <td class="p-2">
                            @if ($item->trashed())
                                <p class="block w-full leading-3 text-linktable mb-1">
                                    COMPRA-{{ $item->id }}</p>
                                <x-span-text :text="'ELIMINADO ' . formatDate($item->deleted_at, 'DD MMMM YYYY')" type="red" class="leading-3 !tracking-normal" />
                            @else
                                <a href="{{ route('admin.almacen.compras.edit', $item) }}"
                                    class="font-medium break-words text-linktable cursor-pointer hover:text-hoverlinktable transition-all ease-in-out duration-150">
                                    COMPRA-{{ $item->id }}</a>
                            @endif
                        </td>
                        <td class="p-2">
                            <h4 class="">{{ $item->proveedor->document }}</h4>
                            <p class=" text-[10px]">{{ $item->proveedor->name }}</p>
                        </td>
                        <td class="p-2">
                            {{ formatDate($item->date, 'DD MMMM Y') }}
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->referencia }}
                        </td>
                        <td class="p-2 align-middle text-center">
                            {{ $item->moneda->simbolo }}
                            {{ number_format($item->total, 3, '.', ', ') }}
                        </td>
                        <td class="p-2 align-middle text-center">
                            {{ $item->moneda->currency }}
                            @if ($item->moneda->code == 'USD')
                                <p><x-span-text :text="formatDecimalOrInteger($item->tipocambio)" class="leading-3 !tracking-normal" /></p>
                            @endif
                        </td>
                        <td class="p-2 align-middle text-center">
                            {{ $item->typepayment->name }}
                        </td>
                        <td class="p-2 text-center align-middle">
                            @if ($item->typepayment->paycuotas)
                                @if (count($item->cuotas))
                                    @if (count($item->cuotaspendientes))
                                        <x-span-text text="PENDIENTE PAGO" class="leading-3 !tracking-normal"
                                            type="red" />
                                    @else
                                        <x-span-text text="PAGADO" class="leading-3 !tracking-normal"
                                            type="green" />
                                    @endif
                                @else
                                    <x-span-text text="CONFIGURAR CUOTAS" class="leading-3 !tracking-normal" />
                                @endif
                            @else
                                @if ($item->cajamovimientos()->sum('amount') == $item->total)
                                    <x-span-text text="PAGADO" class="leading-3 !tracking-normal" type="green" />
                                @else
                                    <x-span-text text="PENDIENTE PAGO" class="leading-3 !tracking-normal"
                                        type="red" />
                                @endif
                            @endif
                        </td>
                        <td class="p-2 align-middle text-center">
                            {{ $item->sucursal->name }}
                            @if ($item->sucursal->trashed())
                                <p><x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal" /></p>
                            @endif
                            <p class="text-[10px] text-colorsubtitleform">USUARIO: {{ $item->user->name }}</p>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>


    <script>
        function select2Sucursal() {
            this.selectS = $(this.$refs.selectsucursal).select2();
            this.selectS.val(this.searchsucursal).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.searchsucursal = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchsucursal", (value) => {
                this.selectS.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectS.select2().val(this.searchsucursal).trigger('change');
            });
        }

        function select2Payment() {
            this.selectPT = $(this.$refs.selectpt).select2();
            this.selectPT.val(this.typepayment).trigger("change");
            this.selectPT.on("select2:select", (event) => {
                this.typepayment = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typepayment", (value) => {
                this.selectPT.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectPT.select2().val(this.typepayment).trigger('change');
            });
        }
    </script>

</div>

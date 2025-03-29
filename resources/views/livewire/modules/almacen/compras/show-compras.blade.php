<div>
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

        <div class="w-full xs:max-w-36">
            <x-label value="Fecha compra :" />
            <x-input type="date" name="date" wire:model.lazy="date" id="search" class="w-full block" />
        </div>

        @if (count($typepayments) > 1)
            <div class="w-full xs:max-w-36">
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
                <th scope="col" class="p-2 font-medium">ID</th>
                <th scope="col" class="p-2 font-medium">PROVEEDOR</th>
                <th scope="col" class="p-2 font-medium">FECHA COMPRA</th>
                <th scope="col" class="p-2 font-medium">DOC. REFERENCIA</th>
                <th scope="col" class="p-2 font-medium">TOTAL COMPRA</th>
                <th scope="col" class="p-2 font-medium">TIPO MONEDA</th>
                {{-- <th scope="col" class="p-2 font-medium">
                    TIPO CAMBIO</th> --}}
                <th scope="col" class="p-2 font-medium">TIPO PAGO</th>
                <th scope="col" class="p-2 font-medium">ESTADO PAGO</th>
                <th scope="col" class="p-2 font-medium">SUCURSAL</th>


                {{-- <th scope="col" class="p-2 relative">
                    <span class="sr-only">OPCIONES</span>
                </th> --}}
            </tr>
        </x-slot>
        @if (count($compras) > 0)
            <x-slot name="body">
                @foreach ($compras as $item)
                    <tr>
                        <td class="p-2" style="min-width: 80px;">
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
                        <td class="p-2" style="min-width: 120px;">
                            <h4>{{ $item->proveedor->document }}</h4>
                            <p class="text-[10px] !leading-none">{{ $item->proveedor->name }}</p>
                        </td>
                        <td class="p-2 !leading-none"  style="min-width: 120px;">
                            {{ formatDate($item->date, 'DD MMMM Y') }}
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->referencia }}
                        </td>
                        <td class="p-2 align-middle text-center"  style="min-width: 100px;">
                            {{ $item->moneda->simbolo }}
                            {{ number_format($item->total, 3, '.', ', ') }}
                        </td>
                        <td class="p-2 align-middle text-center">
                            {{ $item->moneda->currency }}
                            @if ($item->moneda->isDolar())
                                <p><x-span-text :text="decimalOrInteger($item->tipocambio)" class="leading-3 !tracking-normal" /></p>
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
                                        <x-span-text text="PAGADO" class="leading-3 !tracking-normal" type="green" />
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
                        <td class="p-2 align-middle text-center !leading-none" style="min-width: 150px;">
                            {{ $item->sucursal->name }}
                            @if ($item->sucursal->trashed())
                                <p><x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal" /></p>
                            @endif
                            <p class="text-[10px] text-colorsubtitleform !leading-none">{{ $item->user->name }}</p>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>

    <div wire:loading.flex class="loading-overlay hidden fixed">
        <x-loading-next />
    </div>

    @if ($compras->hasPages())
        <div class="w-full flex justify-center items-center sm:justify-end p-1 sticky -bottom-1 right-0 bg-body">
            {{ $compras->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

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

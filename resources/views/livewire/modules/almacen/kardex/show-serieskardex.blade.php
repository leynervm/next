<div class="relative" x-data="{ loading: false }">
    <div class="flex flex-col xs:flex-row xs:flex-wrap gap-2">
        <div class="w-full xs:max-w-sm">
            <x-label value="Buscar serie :" />
            <x-input type="text" wire:model.lazy="searchserie" class="w-full block" placeholder="Buscar serie..." />
        </div>

        <div class="w-full xs:w-auto">
            <x-label value="Fecha salida:" />
            <x-input type="date" wire:model.lazy="date" class="w-full block" />
        </div>

        <div class="w-full xs:w-auto">
            <x-label value="Hasta (Fecha salida):" />
            <x-input type="date" wire:model.lazy="dateto" class="w-full block" />
        </div>

        @if (count($sucursals) > 1)
            <div class="w-full xs:w-52">
                <x-label value="Almacén :" />
                <div x-data="{ searchalmacen: @entangle('searchalmacen') }" x-init="select2Almacen" id="parentsearchalmacen" class="relative"
                    wire:ignore>
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

    @if ($serieskardex->hasPages())
        <div class="pt-3 pb-1">
            {{ $serieskardex->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <x-table class="mt-1">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium">
                    SERIE</th>

                <th scope="col" class="p-2 font-medium text-left">
                    PRODUCTO</th>

                <th scope="col" class="p-2 font-medium">
                    ENTRADA</th>

                <th scope="col" class="p-2 font-medium">
                    SALIDA</th>

                <th scope="col" class="p-2 font-medium">
                    ESTADO</th>
            </tr>
        </x-slot>

        @if (count($serieskardex) > 0)
            <x-slot name="body">
                @foreach ($serieskardex as $item)
                    <tr>
                        <td class="p-2 text-center">
                            @can('admin.almacen.kardex.series.show')
                                <a class="text-linktable hover:text-hoverlinktable cursor-pointer transition-all ease-in-out duration-150"
                                    href="{{ route('admin.almacen.kardex.series.show', $item->serie) }}">
                                    {{ $item->serie }} </a>
                            @endcan

                            @cannot('admin.almacen.kardex.series.show')
                                <h1 class="text-linktable">{{ $item->serie }} </h1>
                            @endcannot
                        </td>

                        <td class="p-2 text-justify">
                            <p class="w-full">{{ $item->producto->name }}</p>
                            <x-span-text :text="$item->almacen->name" class="leading-3 !tracking-normal" />
                        </td>

                        <td class="p-2 text-center">
                            @if ($item->compraitem)
                                <x-span-text :text="formatDate($item->compraitem->created_at, 'DD MMMM YYYY')" class="leading-3 !tracking-normal" />
                                <p class="text-colorlabel leading-3 text-[10px]">
                                    REF. COMPRA: {{ $item->compraitem->compra->referencia }}</p>
                            @endif
                        </td>

                        <td class="p-2 text-center">
                            @if ($item->itemserie)
                                <x-span-text :text="formatDate($item->itemserie->date, 'DD MMMM YYYY')" class="leading-3 !tracking-normal" />
                                <p class="text-colorlabel leading-3 text-[10px]">
                                    {{ $item->itemserie->tvitem->tvitemable->seriecompleta }}</p>
                            @endif
                        </td>

                        <td class="p-2 text-xs text-center">
                            @if ($item->status == 0)
                                <x-span-text text="DISPONIBLE" class="leading-3 !tracking-normal" type="green" />
                            @elseif ($item->status == 1)
                                <x-span-text text="RESERVADO" class="leading-3 !tracking-normal" />
                            @else
                                <x-span-text text="SALIDA" class="leading-3 !tracking-normal" type="red" />
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif

        <x-slot name="loading">
            <div x-show="loading" wire:loading.flex class="loading-overlay rounded">
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
    </script>
</div>

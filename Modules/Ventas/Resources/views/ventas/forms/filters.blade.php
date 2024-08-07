<div class="w-full">
    <div class="w-full flex flex-col gap-2 md:flex-row">
        <div class="w-full md:flex-1">
            <x-label value="Descripcion producto :" />
            <x-input class="block w-full disabled:bg-gray-200" wire:model.lazy="search" placeholder="Buscar producto..." />
            <x-jet-input-error for="search" />
        </div>

        @if ($empresa->usarLista())
            @if (count($pricetypes) > 1)
                <div class="w-full md:w-64 lg:w-80 md:flex-shrink-0">
                    <x-label value="Lista precios :" />
                    <div id="parentventapricetype_id" class="relative" x-data="{ pricetype_id: @entangle('pricetype_id') }" x-init="select2Pricetype">
                        <x-select class="block w-full" id="ventapricetype_id" x-ref="selectp">
                            <x-slot name="options">
                                @foreach ($pricetypes as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="pricetype_id" />
                </div>
            @endif
        @endif
    </div>

    <div class="w-full flex flex-wrap gap-2 mt-2">
        <div class="w-full md:max-w-xs">
            <x-label value="Buscar serie :" />
            <x-input class="block w-full" wire:keydown.enter="getProductoBySerie" wire:model.defer="searchserie"
                placeholder="Buscar serie..." />
            <x-jet-input-error for="searchserie" />
        </div>

        @if (count($sucursal->almacens) > 1)
            <div class="w-full md:max-w-xs">
                <x-label value="Almacén :" />
                <div id="parentalmacen_id" class="relative" x-data="{ almacen_id: @entangle('almacen_id') }" x-init="select2Almacen">
                    <x-select class="block w-full" id="almacen_id" x-ref="selecta">
                        <x-slot name="options">
                            @foreach ($sucursal->almacens as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="almacen_id" />
            </div>
        @endif

        <div class=" w-full md:max-w-xs">
            <x-label value="Marca :" />
            <div id="parentsearchmarca" class="relative" x-data="{ searchmarca: @entangle('searchmarca') }" x-init="select2Marca">
                <x-select class="block w-full" id="searchmarca" x-ref="selectmarca" data-placeholder="null"
                    data-minimum-results-for-search="3">
                    <x-slot name="options">
                        @if (count($marcas))
                            @foreach ($marcas as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="searchmarca" />
        </div>

        <div class=" w-full md:max-w-xs">
            <x-label value="Categoría :" />
            <div id="parentsearchcategory" class="relative" x-data="{ searchcategory: @entangle('searchcategory') }" x-init="select2Category">
                <x-select class="block w-full" id="searchcategory" x-ref="selectcat" data-placeholder="null"
                    data-minimum-results-for-search="3">
                    <x-slot name="options">
                        @if (count($categories))
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="searchcategory" />
        </div>

        @if (count($subcategories) > 1)
            <div class=" w-full md:max-w-xs">
                <x-label value="Subcategoría :" />
                <div id="parentsearchsubcategory" class="relative" x-data="{ searchsubcategory: @entangle('searchsubcategory') }" x-init="SelectSubcategory">
                    <x-select class="block w-full" id="searchsubcategory" x-ref="selectsubcat" data-placeholder="null"
                        data-minimum-results-for-search="3">
                        <x-slot name="options">
                            @foreach ($subcategories as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="searchsubcategory" />
            </div>
        @endif
    </div>

    <div class="w-full mt-1">
        <x-label-check for="disponibles">
            <x-input wire:model="disponibles" name="disponibles" value="1" type="checkbox" id="disponibles" />
            MOSTRAR SOLO DISPONIBLES
        </x-label-check>
    </div>

    <script>
        function select2Almacen() {
            this.selectA = $(this.$refs.selecta).select2();
            this.selectA.val(this.almacen_id).trigger("change");
            this.selectA.on("select2:select", (event) => {
                this.almacen_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("almacen_id", (value) => {
                this.selectA.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectA.select2().val(this.almacen_id).trigger('change');
            });
        }

        function select2Marca() {
            this.selectM = $(this.$refs.selectmarca).select2();
            this.selectM.val(this.searchmarca).trigger("change");
            this.selectM.on("select2:select", (event) => {
                this.searchmarca = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchmarca", (value) => {
                this.selectM.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectM.select2().val(this.searchmarca).trigger('change');
            });
        }

        function select2Category() {
            this.selectC = $(this.$refs.selectcat).select2();
            this.selectC.val(this.searchcategory).trigger("change");
            this.selectC.on("select2:select", (event) => {
                this.searchcategory = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchcategory", (value) => {
                this.selectC.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectC.select2().val(this.searchcategory).trigger('change');
            });
        }

        function select2Pricetype() {
            this.selectP = $(this.$refs.selectp).select2();
            this.selectP.val(this.pricetype_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                @this.setPricetypeId(event.target.value);
                // this.pricetype_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("pricetype_id", (value) => {
                this.selectP.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectP.select2().val(this.pricetype_id).trigger('change');
            });
        }

        function SelectSubcategory() {
            this.selectSC = $(this.$refs.selectsubcat).select2();
            this.selectSC.val(this.searchsubcategory).trigger("change");
            this.selectSC.on("select2:select", (event) => {
                this.searchsubcategory = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchsubcategory", (value) => {
                this.selectSC.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectSC.select2('destroy');
                this.selectSC.select2().val(this.searchsubcategory).trigger('change');
            });
        }
    </script>
</div>

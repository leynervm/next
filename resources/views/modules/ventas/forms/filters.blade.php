<div class="w-full relative" x-data="loadselect2">
    <div x-bind:style="{ opacity: loadingfilters ? '0' : '1' }" style="opacity: 0"
        class="w-full grid grid-cols-1 sm:grid-cols-3 xl:grid-cols-4 gap-2">
        <div class="w-full col-span-2 xl:col-span-3">
            <x-label value="Buscar producto :" />
            <x-input class="block w-full disabled:bg-gray-200" wire:model.debounce.700ms="search"
                placeholder="Buscar producto..." />
            <x-jet-input-error for="search" />
        </div>

        <div class="w-full">
            <x-label value="Buscar serie :" />
            <x-input class="block w-full" wire:keydown.enter="getProductoBySerie" wire:model.defer="searchserie"
                placeholder="Buscar serie..." />
            <x-jet-input-error for="searchserie" />
        </div>
    </div>

    <div x-bind:style="{ opacity: loadingfilters ? '0' : '1' }" style="opacity: 0"
        class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-2 mt-2">

        @if ($empresa->usarLista())
            {{-- @if (count($pricetypes) > 1) --}}
            <div class="w-full">
                <x-label value="Lista precios :" />
                <div id="parentventapricetype_id" class="relative">
                    <x-select class="block w-full" id="ventapricetype_id" x-ref="selectpricelist">
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="pricetype_id" />
            </div>
            {{-- @endif --}}
        @endif

        <div class="w-full">
            <x-label value="Marca :" />
            <div id="parentsearchmarca" class="relative">
                <x-select class="block w-full" id="searchmarca" x-ref="selectmarca" data-placeholder="null"
                    data-minimum-results-for-search="2">
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="searchmarca" />
        </div>

        <div class="w-full">
            <x-label value="Categoría :" />
            <div id="parentsearchcategory" class="relative">
                <x-select class="block w-full" id="searchcategory" x-ref="selectcat" data-placeholder="null"
                    data-minimum-results-for-search="2">
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="searchcategory" />
        </div>

        {{-- @if (count($subcategories) > 1) --}}
        <div class="w-full">
            <x-label value="Subcategoría :" />
            <div id="parentsearchsubcategory" class="relative">
                <x-select class="block w-full" id="searchsubcategory" x-ref="selectsubcat" data-placeholder="null"
                    data-minimum-results-for-search="2">
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="searchsubcategory" />
        </div>
        {{-- @endif --}}
    </div>

    <div class="w-full mt-1">
        <x-label-check for="disponibles">
            <x-input wire:model="disponibles" name="disponibles" value="1" type="checkbox" id="disponibles" />
            MOSTRAR SOLO DISPONIBLES
        </x-label-check>
    </div>

    <div class="w-full h-full bg-body flex flex-col gap-2 absolute top-0 rounded-lg" x-show="loadingfilters" x-cloak
        style="display: none">
        <div class="w-full flex gap-2">
            <div class="w-full flex-1 skeleton-box">
                <div class="p-1.5 rounded-sm bg-neutral-400 max-w-24"></div>
                <div class="w-full p-4 bg-neutral-200 rounded-lg mt-1"></div>
            </div>
            <div class="w-full max-w-64 skeleton-box">
                <div class="p-1.5 rounded-sm bg-neutral-400 max-w-24"></div>
                <div class="w-full p-4 bg-neutral-200 rounded-lg mt-1"></div>
            </div>
        </div>
        <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-2">
            <div class="w-full skeleton-box">
                <div class="p-1.5 rounded-sm bg-neutral-400 max-w-24"></div>
                <div class="w-full p-4 bg-neutral-200 rounded-lg mt-1"></div>
            </div>
            <div class="w-full skeleton-box">
                <div class="p-1.5 rounded-sm bg-neutral-400 max-w-24"></div>
                <div class="w-full p-4 bg-neutral-200 rounded-lg mt-1"></div>
            </div>
            <div class="w-full skeleton-box">
                <div class="p-1.5 rounded-sm bg-neutral-400 max-w-24"></div>
                <div class="w-full p-4 bg-neutral-200 rounded-lg mt-1"></div>
            </div>
            <div class="w-full skeleton-box">
                <div class="p-1.5 rounded-sm bg-neutral-400 max-w-24"></div>
                <div class="w-full p-4 bg-neutral-200 rounded-lg mt-1"></div>
            </div>
            <div class="w-full skeleton-box">
                <div class="p-1.5 rounded-sm bg-neutral-400 max-w-24"></div>
                <div class="w-full p-4 bg-neutral-200 rounded-lg mt-1"></div>
            </div>
        </div>
    </div>

    <script>
        function loadselect2() {
            return {
                marcas: [],
                categories: [],
                subcategories: [],
                subcategories: [],
                pricetypes: [],
                loadingfilters: true,

                init() {
                    this.fetchMultipleDatos();
                    this.obtenerSubcategorias(this.searchcategory)
                },
                async fetchMultipleDatos() {
                    try {
                        let urlmarcas = "{{ route('admin.ventas.marcas.list') }}";
                        let urlcategorias = "{{ route('admin.ventas.categories.list') }}";
                        let urlpricetypes = "{{ route('admin.ventas.pricetypes.list') }}";

                        const [marcas, categories, pricetypes] = await Promise.all([
                            fetchAsyncDatos(urlmarcas),
                            fetchAsyncDatos(urlcategorias),
                            fetchAsyncDatos(urlpricetypes),
                        ]);

                        this.marcas = marcas;
                        this.categories = categories;
                        this.pricetypes = pricetypes;

                        this.select2Pricetype()
                        this.select2Marca()
                        this.select2Category()
                        this.loadingfilters = false;
                    } catch (error) {
                        console.error('Error en una de las peticiones:', error);
                        return null;
                    }
                },
                async obtenerDatos() {
                    // const subcategories = await fetchAsyncDatos("{{ route('admin.ventas.subcategories.list') }}", {
                    //     category_id: this.searchcategory
                    // });
                    // if (subcategories) {
                    //     this.subcategories = subcategories;
                    // }
                    // this.selectSubcategory()
                },
                async obtenerSubcategorias(category_id = null) {
                    const subcategories = await fetchAsyncDatos("{{ route('admin.ventas.subcategories.list') }}", {
                        category_id: category_id
                    });
                    if (subcategories) {
                        this.subcategories = subcategories;
                    }
                    this.selectSubcategory()
                },
                select2Marca() {
                    this.selectM = $(this.$refs.selectmarca).select2({
                        data: this.marcas,
                        cache: true
                    });
                    this.selectM.val(this.searchmarca).trigger("change");
                    this.selectM.on("select2:select", (event) => {
                        // this.searchmarca = event.target.value;
                        this.$wire.set('searchmarca', event.target.value, true)
                        this.$wire.$refresh()
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                    this.$watch("searchmarca", (value) => {
                        this.selectM.val(value).trigger("change");
                    });

                    Livewire.hook('message.processed', () => {
                        this.selectM.select2({
                            data: this.marcas,
                            cache: true
                        }).val(this.searchmarca).trigger('change');
                    });
                },
                select2Category() {
                    this.selectC = $(this.$refs.selectcat).select2({
                        data: this.categories,
                        cache: true
                    });
                    this.selectC.val(this.searchcategory).trigger("change");
                    this.selectC.on("select2:select", (event) => {
                        this.$wire.set('searchsubcategory', '', true)
                        this.$wire.set('searchcategory', event.target.value, true)
                        this.obtenerSubcategorias(event.target.value)
                        this.$wire.$refresh()
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                    this.$watch("searchcategory", (value) => {
                        this.selectC.val(value).trigger("change");
                    });

                    Livewire.hook('message.processed', () => {
                        this.selectC.select2({
                            data: this.categories,
                            cache: true
                        }).val(this.searchcategory).trigger('change');
                    });
                },
                selectSubcategory() {
                    this.selectSC = $(this.$refs.selectsubcat).select2({
                        data: this.subcategories,
                        cache: true
                    });
                    this.selectSC.val(this.searchsubcategory).trigger("change");
                    this.selectSC.on("select2:select", (event) => {
                        this.$wire.set('searchsubcategory', event.target.value, true)
                        this.$wire.$refresh()
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
                        this.selectSC.select2({
                            data: this.subcategories,
                            cache: true
                        }).val(this.searchsubcategory).trigger('change');
                    });
                },
                select2Pricetype() {
                    this.selectP = $(this.$refs.selectpricelist).select2({
                        data: this.pricetypes
                    });
                    this.selectP.val(this.pricetype_id).trigger("change");
                    this.selectP.on("select2:select", (event) => {
                        this.pricetype_id = event.target.value;
                        // this.$wire.set('pricetype_id', event.target.value, true)
                        // this.$wire.$refresh()
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                    this.$watch("pricetype_id", (value) => {
                        this.selectP.val(value).trigger("change");
                    });

                    Livewire.hook('message.processed', () => {
                        this.selectP.select2({
                            data: this.pricetypes
                        }).val(this.pricetype_id).trigger('change');
                    });
                }
            }
        }
    </script>
</div>

<div class="w-full flex flex-col gap-8" x-data="datagarantia">
    @can('admin.almacen.productos.garantias')
        <x-form-card titulo="GARANTÍAS" subtitulo="Agregar garantías de proteccion del producto." class="relative">
            <div class="w-full flex flex-wrap lg:flex-nowrap gap-3" x-data="{ loading: false }">
                <div x-show="loading" wire:loading.flex wire:target="save, delete, render, " class="loading-overlay rounded">
                    <x-loading-next />
                </div>

                @can('admin.almacen.productos.garantias.edit')
                    <div class="w-full lg:w-80 xl:w-96 lg:flex-shrink-0 bg-body p-3 rounded">
                        <form wire:submit.prevent="save" class="w-full">
                            <div class="w-full">
                                <x-label value="Garantías disponibles :" />
                                <div class="relative" id="parenttypegarantia_id" x-init="select2Garantia">
                                    <x-select class="block w-full select2" x-ref="select" id="typegarantia_id"
                                        data-placeholder="null">
                                        <x-slot name="options">
                                            @if (count($typegarantias))
                                                @foreach ($typegarantias as $item)
                                                    <option value="{{ $item->id }}" data-time="{{ $item->time }}">
                                                        {{ $item->name }} -
                                                        @if ($item->datecode == 'MM')
                                                            MESES
                                                        @else
                                                            AÑOS
                                                        @endif
                                                    </option>
                                                @endforeach
                                            @endif
                                        </x-slot>
                                    </x-select>
                                    <x-icon-select />
                                </div>
                                <x-jet-input-error for="typegarantia_id" />
                            </div>

                            <div class="w-full mt-2">
                                <x-label value="Tiempo garantía :" />
                                <x-input class="block w-full" x-model="time" type="number" placeholder="Meses garantía..." />
                                <x-jet-input-error for="time" />
                            </div>

                            <div class="w-full pt-4 flex justify-end">
                                <x-button type="submit" wire:loading.atrr="disabled">
                                    REGISTRAR</x-button>
                            </div>
                        </form>
                    </div>
                @endcan
                @if (count($producto->garantiaproductos))
                    <div class="w-full flex flex-wrap gap-2">
                        @foreach ($producto->garantiaproductos as $item)
                            @php
                                if ($item->typegarantia->datecode == 'MM') {
                                    $timestring = $item->time > 1 ? ' MESES' : ' MES';
                                } else {
                                    $timestring = $item->time > 1 ? ' AÑOS' : ' AÑO';
                                }
                            @endphp

                            <x-minicard size="lg" :title="$item->typegarantia->name" :content="$item->time . $timestring" class="!bg-body">
                                {{-- <span class="absolute right-2 -top-3 w-4 h-4 block text-next-500 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 320 512"
                                    fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M310.6 246.6l-127.1 128C176.4 380.9 168.2 384 160 384s-16.38-3.125-22.63-9.375l-127.1-128C.2244 237.5-2.516 223.7 2.438 211.8S19.07 192 32 192h255.1c12.94 0 24.62 7.781 29.58 19.75S319.8 237.5 310.6 246.6z" />
                                </svg>
                            </span>

                            <span
                                class="absolute right-0 -top-8 bg-next-500 text-white text-[10px] font-semibold tracking-widest p-1.5 px-2 rounded-sm">
                                {{ $item->time . $timestring }}</span> --}}

                                <span class="block mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-colorlinknav"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M10.014 2C6.23617 2 4.34725 2 3.17362 3.17157C2 4.34315 2 6.22876 2 10C2 13.7712 2 15.6569 3.17362 16.8284C4.34725 18 6.23617 18 10.014 18H14.021C17.7989 18 19.6878 18 20.8614 16.8284C21.671 16.0203 21.9221 14.8723 22 13" />
                                        <path d="M12 18V22" />
                                        <path d="M8 22H16" />
                                        <path d="M11 15H13" />
                                        <path
                                            d="M17.4991 2C16.0744 2 15.1506 2.90855 14.0581 3.23971C13.6138 3.37436 13.3917 3.44168 13.3018 3.53659C13.2119 3.6315 13.1856 3.77019 13.133 4.04756C12.5696 7.0157 13.801 9.75979 16.7375 10.8279C17.053 10.9426 17.2108 11 17.5007 11C17.7906 11 17.9484 10.9426 18.2639 10.8279C21.2002 9.75978 22.4304 7.01569 21.8669 4.04756C21.8142 3.77014 21.7879 3.63143 21.698 3.53652C21.6081 3.44161 21.386 3.37432 20.9418 3.23974C19.8488 2.90862 18.9239 2 17.4991 2Z" />
                                    </svg>
                                </span>

                                @can('admin.almacen.productos.garantias.edit')
                                    <x-slot name="buttons">
                                        <x-button-delete onclick="confirmDeleteGarantia({{ $item }})"
                                            wire:loading.attr="disabled" />
                                    </x-slot>
                                @endcan
                            </x-minicard>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-form-card>
    @endcan

    @can('admin.administracion.pricetypes.productos')
        @if (mi_empresa()->usarlista())
            <x-form-card titulo="PRECIOS VENTA" subtitulo="Personalizar precios de venta según su preferencia.">
                <form wire:submit.prevent="updatelistaprecios" class="w-full flex flex-col gap-2">
                    @if (count($pricetypes) > 0)
                        <div class="w-full flex flex-wrap gap-2">
                            @foreach ($pricetypes as $item)
                                @if (in_array($item->campo_table, lista_precios()))
                                    <x-simple-card
                                        class="w-full xs:w-48 flex flex-col items-center justify-between rounded-xl p-2">
                                        <x-span-text :text="$item->name" class="leading-3 !tracking-normal" />

                                        <div>
                                            <x-label value="S/." class="w-full text-left" />
                                            <x-input class="block w-full" wire:model.defer="{{ $item->campo_table }}"
                                                type="number" step="0.001" />
                                            <x-jet-input-error for="{{ $item->campo_table }}" />
                                        </div>

                                        @if (mi_empresa()->verDolar())
                                            @if (mi_empresa()->tipocambio > 0)
                                                {{-- <h1 class="text-center relative pt-1 text-colorlabel text-xs">
                                        S/. {{ formatDecimalOrInteger($producto[$item->campo_table], 2, ', ') }}</h1> --}}
                                            @else
                                                <p class="text-center tracking-widest text-colorerror">
                                                    TIPO CAMBIO NO CONFIGURADO</p>
                                            @endif
                                        @endif

                                        {{-- <div class="w-full flex justify-end">
                                        <x-button-edit wire:click="cambiarprecioventa({{ $lista->id }})"
                                            wire:loading.attr="disabled" />
                                    </div> --}}
                                    </x-simple-card>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <div class="w-full">
                        <x-button type="submit" wire:loading.attr="disabled">ACTUALIZAR</x-button>
                    </div>
                </form>
            </x-form-card>
        @endif
    @endcan
    {{-- {{ $producto->detalleproducto->descripcion }} --}}

    <x-form-card titulo="DETALLE PRODUCTO">
        <div class="w-full overflow-hidden">
            <form wire:submit.prevent="savedetalle" class="w-full">

                <div wire:ignore>
                    <x-ckeditor-5 id="myckeditor3" wire:model.defer="descripcion" />
                </div>

                <x-jet-input-error for="descripcion" />
                <x-jet-input-error for="producto.id" />

                @can('admin.almacen.productos.especificaciones')
                    <div class="mt-3 flex justify-end">
                        <x-button type="submit" wire:loading.atrr="disabled">
                            @if ($producto->detalleproducto)
                                ACTUALIZAR
                            @else
                                REGISTRAR
                            @endif
                        </x-button>
                    </div>
                @endcan
            </form>
        </div>
    </x-form-card>


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Cambiar precio venta') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="saveprecioventa" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Lista precio :" />
                    <x-disabled-text :text="$pricetype->name" />
                    <x-jet-input-error for="pricetype.id" />
                </div>

                <div>
                    <x-label value="Precio venta sugerido :" />
                    <x-disabled-text :text="$priceold ?? '0.00'" />
                </div>

                <div>
                    <x-label value="Precio venta manual :" />
                    <x-input class="block w-full" wire:model.defer="newprice" type="number" min="0"
                        step="0.01" />
                    <x-jet-input-error for="newprice" />
                    <x-jet-input-error for="producto.id" />
                </div>

                <div class="mt-3 flex gap-2 justify-end">
                    @if ($pricemanual)
                        <x-button wire:click="deletepricemanual" wire:key="deletepricemanual{{ $producto->id }}"
                            wire:loading.attr="disabled">
                            ELIMINAR PRECIO MANUAL</x-button>
                    @endif
                    <x-button type="submit" wire:loading.atrr="disabled">
                        ACTUALIZAR
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script src="{{ asset('assets/ckeditor5/ckeditor5_38.1.1_super-build_ckeditor.js') }}"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('datagarantia', () => ({
                typegarantia_id: @entangle('typegarantia_id').defer,
                time: @entangle('time').defer,

                init() {},
                getTimegarantia(target) {
                    let time = target.options[target.selectedIndex].getAttribute('data-time');
                    this.time = time;
                },
            }));
        })

        function select2Garantia() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.typegarantia_id).trigger("change");
            this.select2.on("select2:select", (event) => {
                this.typegarantia_id = event.target.value;
                this.getTimegarantia(event.target);
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typegarantia_id", (value) => {
                this.select2.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.select2.select2().val(this.typegarantia_id).trigger('change');
            });
        }

        function confirmDeleteGarantia(garantia) {
            swal.fire({
                title: 'Eliminar garantía del producto !',
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(garantia);
                    // Livewire.emitTo('almacen.productos.show-garantias', 'delete', data);
                }
            })
        }
    </script>
</div>

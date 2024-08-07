<div>
    <div wire:loading.flex class="fixed loading-overlay rounded hidden">
        <x-loading-next />
    </div>

    <div class="w-full flex flex-col gap-5" x-data="loader">
        <x-form-card titulo="GENERAR NUEVA VENTA" subtitulo="Complete todos los campos para registrar una nueva venta.">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full flex flex-col gap-1">

                    @include('ventas::ventas.forms.comprobante')

                    @if (Module::isEnabled('Facturacion'))
                        @can('admin.ventas.create.guias')
                            @include('ventas::ventas.forms.guia-remision')
                        @endcan
                    @endif

                    <div class="w-full flex flex-col gap-1">
                        <x-jet-input-error for="typepayment.id" />
                        <x-jet-input-error for="items" />
                        <x-jet-input-error for="typepay" />
                        <x-jet-input-error for="concept.id" />
                        <x-jet-input-error for="parcialpayments" />
                        {{-- <x-jet-input-error for="client_id" /> --}}
                    </div>

                    <div
                        class="w-full flex flex-col sm:flex-row xl:flex-col gap-1 pt-4 justify-between items-start sm:items-end xl:items-start">
                        @can('admin.ventas.create.guias')
                            @if (count($comprobantesguia) > 0)
                                <div class="inline-block" x-show="!sincronizegre">
                                    <x-label-check for="incluyeguia" x-show="openguia">
                                        <x-input x-model="incluyeguia" name="incluyeguia" type="checkbox"
                                            id="incluyeguia" />GENERAR GUÍA REMISIÓN
                                    </x-label-check>
                                </div>
                            @endif
                        @endcan

                        <x-button class="block w-full sm:inline-block sm:w-auto xl:w-full" type="submit"
                            wire:loading.attr="disabled">
                            {{ __('REGISTRAR VENTA') }}</x-button>
                    </div>

                    @if ($errors->any())
                        <div class="w-full flex flex-col gap-1">
                            @foreach ($errors->keys() as $key)
                                <x-jet-input-error :for="$key" />
                            @endforeach
                        </div>
                    @endif
                </div>
            </form>
        </x-form-card>

        <x-form-card titulo="RESUMEN DE VENTA" class="text-colorlabel">
            <div class="w-full">
                <p class="text-[10px]">EXONERADO : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($exonerado, 2, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">GRAVADO : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($gravado, 2, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">IGV : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($igv, 2, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">GRATUITO : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($gratuito, 2, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">DESCUENTOS : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($descuentos, 2, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">SUBTOTAL : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($total, 2, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">TOTAL PAGAR : {{ $moneda->simbolo }}
                    <span
                        class="font-bold text-xl">{{ number_format($total - ($gratuito + $igvgratuito), 2, '.', ', ') }}</span>
                    @if ($increment > 0)
                        INC. + {{ formatDecimalOrInteger($increment) }}%
                        ({{ number_format($total - ($gratuito + $paymentactual) - $amountincrement, 2, '.', ', ') }})
                    @endif
                </p>

                <p class="text-[10px]">PENDIENTE : {{ $moneda->simbolo }}
                    <span
                        class="font-bold text-xl text-red-600">{{ number_format($total - ($gratuito + $igvgratuito + $paymentactual), 2, '.', ', ') }}</span>
                </p>
            </div>
        </x-form-card>

        <x-form-card titulo="PAGO PARCIAL" class="text-colorlabel" style="display: none" x-show="typepay > 0">
            <div class="w-full flex flex-wrap gap-2">
                @foreach ($parcialpayments as $index => $item)
                    <x-minicard size="md" alignFooter="justify-end">
                        <h1 class="text-lg text-center leading-5 font-semibold text-colorlabel">
                            {{ number_format($item['amount'], 2, '.', ', ') }}</h1>
                        <span class="text-[10px] text-center text-colorsubtitleform mt-2">{{ $item['method'] }}</span>
                        <slot name="buttons">
                            <x-button-delete wire:click="removepay({{ $index }})" />
                        </slot>
                    </x-minicard>
                @endforeach
            </div>
        </x-form-card>

        <div class="w-full" x-data="{ showcart: true }">
            <div class="text-end px-3">
                <button class="text-amber-500 relative inline-block w-6 h-6 cursor-pointer" @click="showcart=!showcart">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" class="w-full h-full block">
                        <path d="M8 16L16.7201 15.2733C19.4486 15.046 20.0611 14.45 20.3635 11.7289L21 6" />
                        <path d="M6 6H22" />
                        <circle cx="6" cy="20" r="2" />
                        <circle cx="17" cy="20" r="2" />
                        <path d="M8 20L15 20" />
                        <path
                            d="M2 2H2.966C3.91068 2 4.73414 2.62459 4.96326 3.51493L7.93852 15.0765C8.08887 15.6608 7.9602 16.2797 7.58824 16.7616L6.63213 18" />
                    </svg>
                    <small
                        class="bg-amber-500 text-white animate-bounce font-semibold absolute -top-3 -right-1 flex items-center justify-center w-4 h-4 p-0.5 leading-3 rounded-full text-[8px]">
                        {{ count($carshoops) }}</small>
                </button>
            </div>
            @if (count($carshoops) > 0)
                <div class="w-full" x-show="showcart" x-transition>
                    <div class="flex gap-2 flex-wrap justify-start">
                        @foreach ($carshoops as $item)
                            <x-simple-card
                                class="w-full flex flex-col border border-borderminicard justify-between lg:max-w-sm xl:w-full group p-1 text-xs relative overflow-hidden">

                                <h1 class="text-colorlabel w-full text-[10px] leading-3 text-left z-[1]">
                                    <span class="font-semibold text-sm">
                                        {{ formatDecimalOrInteger($item->cantidad) }}
                                        {{ $item->producto->unit->name }}</span>
                                    {{ $item->producto->name }}

                                    @if (count($item->carshoopseries) == 1)
                                        - SN: {{ $item->carshoopseries()->first()->serie->serie }}
                                    @endif
                                </h1>

                                @if ($item->promocion)
                                    <div class="w-auto h-auto bg-red-600 absolute left-1 top-1  rounded-sm">
                                        <p class=" text-white text-[9px] inline-block font-medium p-1 leading-3">
                                            PROMOCIÓN</p>
                                    </div>
                                @endif

                                @if (count($item->carshoopitems) > 0)
                                    @if (count($item->carshoopitems) > 0)
                                        <div class="w-full mb-2 mt-1">
                                            @foreach ($item->carshoopitems as $itemcarshop)
                                                <h1 class="text-next-500 text-[10px] leading-3 text-left">
                                                    <span
                                                        class="w-1.5 h-1.5 bg-next-500 inline-block rounded-full"></span>
                                                    {{ $itemcarshop->producto->name }}
                                                </h1>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif


                                <div class="w-full flex gap-1 items-end">
                                    <h1 class="text-colorlabel whitespace-nowrap text-xs text-right">
                                        <small class="text-[10px] font-medium">{{ $item->moneda->simbolo }}</small>
                                        {{ number_format($item->price + $item->igv, 2) }}
                                    </h1>

                                    <h1
                                        class="flex-1 w-full text-colorlabel whitespace-nowrap leading-3 text-lg font-semibold text-right">
                                        <small class="text-[9px] font-medium">IMPORTE <br>
                                            {{ $item->moneda->simbolo }}</small>
                                        {{ number_format($item->total, 2) }}
                                    </h1>
                                </div>

                                <div class="w-full flex flex-wrap gap-1 mt-2">
                                    <x-span-text :text="$item->almacen->name" class="leading-3 !tracking-normal" />

                                    @if ($item->isNoAlterStock())
                                        <x-span-text text="NO ALTERA STOCK" class="leading-3 !tracking-normal" />
                                    @elseif ($item->isReservedStock())
                                        <x-span-text text="STOCK RESERVADO" class="leading-3 !tracking-normal"
                                            type="orange" />
                                    @elseif ($item->isIncrementStock())
                                        <x-span-text text="INCREMENTA STOCK" class="leading-3 !tracking-normal"
                                            type="green" />
                                    @elseif($item->isDiscountStock())
                                        <x-span-text text="DISMINUYE STOCK" class="leading-3 !tracking-normal"
                                            type="red" />
                                    @endif
                                </div>

                                {{-- <h1 class="text-colorlabel whitespace-nowrap text-xs font-semibold">
                                    <small class="text-[10px] font-medium">P.U : </small>
                                    {{ number_format($item->price, 2) }}</h1>
                                <h1 class="text-colorlabel whitespace-nowrap text-xs font-semibold">
                                    <small class="text-[10px] font-medium">IGV : </small>
                                    {{ number_format($item->igv, 2) }}</h1> --}}

                                @if (count($item->carshoopseries) > 1)
                                    <div x-data="{ showForm: false }" class="mt-1">
                                        <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                            {{ __('VER SERIES') }}
                                        </x-button>
                                        <div x-show="showForm" x-transition class="block w-full rounded mt-1">
                                            <div class="w-full flex flex-wrap gap-1">
                                                @foreach ($item->carshoopseries as $itemserie)
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 rounded-lg">
                                                        {{ $itemserie->serie->serie }}
                                                        <x-button-delete
                                                            onclick="confirmDeleteSerie({{ $itemserie }})"
                                                            wire:loading.attr="disabled" />
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                <div class="w-full flex items-end gap-2 justify-between mt-2">
                                    @can('admin.ventas.create.gratuito')
                                        <div>
                                            <x-label-check textSize="[9px]" for="gratuito_{{ $item->id }}">
                                                <x-input wire:change="updategratis({{ $item->id }})" value="1"
                                                    type="checkbox" id="gratuito_{{ $item->id }}"
                                                    :checked="$item->isGratuito()" />
                                                GRATUITO</x-label-check>
                                        </div>
                                    @endcan
                                    <x-button-delete onclick="confirmDeleteCarshoop({{ $item->id }})"
                                        wire:loading.attr="disabled" />
                                </div>
                            </x-simple-card>
                        @endforeach
                    </div>

                    <div class="w-full flex justify-end mt-2">
                        <x-button-secondary onclick="confirmDeleteAllCarshoop()" wire:loading.attr="disabled"
                            class="inline-block">ELIMINAR TODO</x-button-secondary>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function confirmDeleteSerie(itemserie) {
            swal.fire({
                title: 'Eliminar serie ' + itemserie.serie.serie + ' del carrito de ventas ?',
                text: "Se eliminará un registro del carrito de ventas y se actualizará el stock del producto.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteserie(itemserie.id);
                }
            })
        }

        function confirmDeleteCarshoop(carshoop_id) {
            swal.fire({
                title: 'ELIMINAR ITEM DEL CARRITO ?',
                text: "Se eliminará un registro del carrito de ventas y se actualizará el stock del producto.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // @this.delete(carshoop_id);
                    const message = deleteitem(carshoop_id);
                }
            })
        }

        async function deleteitem(carshoop_id) {
            try {
                const route = "{{ route('admin.carshoop.delete', ['carshoop' => ':carshoop_id']) }}"
                    .replace(':carshoop_id', carshoop_id);
                const response = await axios.post(route, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    }
                })

                if (response.status === 200) {
                    console.log(response.data);
                    @this.setTotal();
                } else {
                    throw new Error('Error al vaciar el carrito');
                }
            } catch (error) {
                console.error('Error al vaciar el carrito:', error);
                throw error;
            }
        }

        function confirmDeleteAllCarshoop() {
            swal.fire({
                title: 'Eliminar carrito de ventas ?',
                text: "Se eliminarán todos los productos del carrito de ventas y se actualizará su stock correspondientes.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const message = deleteAll();
                    // @this.deleteallcarshoop();
                }
            })
        }

        async function deleteAll() {
            try {
                const response = await axios.post("{{ route('admin.carshoop.delete.all') }}", {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    }
                })

                if (response.status === 200) {
                    console.log(response.data);
                    @this.setTotal();
                } else {
                    throw new Error('Error al vaciar el carrito');
                }
            } catch (error) {
                console.error('Error al vaciar el carrito:', error);
                throw error;
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('loader', () => ({
                incluyeguia: @entangle('incluyeguia').defer,
                vehiculosml: false,
                loadingprivate: false,
                loadingpublic: false,
                loadingdestinatario: false,
                codemotivotraslado: '',
                codemodalidad: '',
                paymentcuotas: false,
                formapago: '',
                code: '',
                sendsunat: '',
                openguia: true,
                sincronizegre: @entangle('sincronizegre').defer,

                cotizacion_id: @entangle('cotizacion_id').defer,
                moneda_id: @entangle('moneda_id'),
                seriecomprobante_id: @entangle('seriecomprobante_id').defer,
                typepayment_id: @entangle('typepayment_id'),
                methodpayment_id: @entangle('methodpayment_id').defer,
                serieguia_id: @entangle('serieguia_id').defer,
                motivotraslado_id: @entangle('motivotraslado_id').defer,
                modalidadtransporte_id: @entangle('modalidadtransporte_id').defer,
                ubigeoorigen_id: @entangle('ubigeoorigen_id').defer,
                ubigeodestino_id: @entangle('ubigeodestino_id').defer,
                typepay: @entangle('typepay').defer,

                init() {
                    // const selectpayment = this.$refs.selectpayment;
                    // const selectcomprobante = this.$refs.selectcomprobante;
                    // if (selectpayment) {
                    //     this.getTipopago(selectpayment);
                    // }
                    // if (selectcomprobante) {
                    //     this.getCodeSend(selectcomprobante);
                    // }

                    this.$watch("typepay", (value) => {
                        console.log('Typepay : ' + value);
                    });
                },
                toggle() {
                    this.vehiculosml = !this.vehiculosml;
                    if (this.vehiculosml) {
                        this.loadingpublic = false;
                        this.loadingprivate = false;
                    } else {
                        this.selectedModalidadtransporte(this.codemodalidad);
                    }
                },
                toggleguia() {
                    this.incluyeguia = !this.incluyeguia;
                },
                getCodeMotivo(target) {
                    this.codemotivotraslado = target.options[target.selectedIndex].getAttribute(
                        'data-code');
                    this.selectedMotivotraslado(this.codemotivotraslado);
                },
                getCodeModalidad(target) {
                    this.codemodalidad = target.options[target.selectedIndex].getAttribute(
                        'data-code');
                    if (!this.vehiculosml) {
                        this.selectedModalidadtransporte(this.codemodalidad);
                    }
                },
                selectedModalidadtransporte(value) {
                    // console.log(value);
                    switch (value) {
                        case '01':
                            this.loadingpublic = true;
                            this.loadingprivate = false;
                            break;
                        case '02':
                            this.loadingprivate = true;
                            this.loadingpublic = false;
                            break;
                        default:
                            this.loadingprivate = false;
                            this.loadingpublic = false;
                    }
                },
                selectedMotivotraslado(value) {
                    switch (value) {
                        case '01':
                            this.loadingdestinatario = false;
                            break;
                        case '03':
                            this.loadingdestinatario = true;
                            break;
                        default:
                            this.loadingdestinatario = false;
                            this.loadingprivate = false;
                            this.loadingpublic = false;
                    }
                },
                getCodeSend(target) {
                    this.sendsunat = target.options[target.selectedIndex].getAttribute(
                        'data-sunat');
                    // console.log(this.sendsunat);

                    switch (this.sendsunat) {
                        case '0':
                            this.incluyeguia = false;
                            this.openguia = false;
                            break;
                        case '1':
                            this.openguia = true;
                            break;
                        default:
                            this.openguia = false;
                            this.incluyeguia = false;
                            this.sendsunat = '';
                    }

                    // console.log('sendsunat ' + this.sendsunat);
                    // console.log('incluyeguia ' + this.incluyeguia);
                    // console.log('openguia ' + this.openguia);
                },
                savepay(event) {
                    this.$wire.call('savepay')
                        .then(() => {
                            // console.log('function ejecutado correctamente');
                        });
                    event.preventDefault();
                }
            }));
        })

        window.addEventListener('show-resumen-venta', (event) => {
            @this.setTotal();
        });
    </script>
</div>

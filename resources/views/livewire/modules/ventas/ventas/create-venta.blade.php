<div class="w-full flex flex-col lg:flex-row gap-2 lg:h-[calc(100vh_-_4rem)]" x-data="loader">
    {{-- <div wire:loading.flex
        wire:target="page,typepayment_id,pricetype_id,almacen_id,save,addtocar,disponibles,gratuito,searchcategory,searchsubcategory,searchmarca,setTotal"
        class="fixed loading-overlay hidden">
        <x-loading-next />
    </div> --}}

    <x-loading-web-next class="!hidden" wire:loading.class.remove="!hidden" />

    <div class="w-full flex flex-col gap-5 lg:flex-shrink-0 lg:w-80 lg:overflow-y-auto soft-scrollbar h-full">
        <x-form-card titulo="GENERAR NUEVA VENTA" subtitulo="Complete todos los campos para registrar una nueva venta.">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full flex flex-col gap-1">

                    @include('modules.ventas.forms.comprobante')

                    @if (Module::isEnabled('Facturacion'))
                        @can('admin.ventas.create.guias')
                            @include('modules.ventas.forms.guia-remision')
                        @endcan
                    @endif

                    @can('admin.ventas.create.guias')
                        @if (count($comprobantesguia) > 0)
                            <div class="block w-full" x-show="!sincronizegre">
                                <x-label-check for="incluyeguia" x-show="openguia">
                                    <x-input x-model="incluyeguia" name="incluyeguia" type="checkbox"
                                        id="incluyeguia" />GENERAR GUÍA REMISIÓN
                                </x-label-check>
                            </div>
                        @endif
                    @endcan

                    <div class="w-full flex flex-col gap-1">
                        <x-jet-input-error for="typepayment.id" />
                        <x-jet-input-error for="items" />
                        <x-jet-input-error for="typepay" />
                        <x-jet-input-error for="concept.id" />
                        <x-jet-input-error for="parcialpayments" />
                        {{-- <x-jet-input-error for="client_id" /> --}}
                    </div>

                    <div class="w-full">
                        <x-button class="block w-full" type="submit" wire:loading.attr="disabled">
                            {{ __('Save sale') }}</x-button>
                    </div>

                    {{-- @if ($errors->any())
                        <div class="w-full flex flex-col gap-1">
                            @foreach ($errors->keys() as $key)
                                <x-jet-input-error :for="$key" />
                            @endforeach
                        </div>
                    @endif --}}
                </div>
            </form>
        </x-form-card>

        <x-form-card titulo="RESUMEN DE VENTA" class="text-colorlabel">
            <div class="w-full">
                <table class="w-full table text-[10px]">
                    <tr>
                        <td>EXONERADO :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm">{{ number_format($exonerado, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td>GRAVADO :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm"> {{ number_format($gravado, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td>IGV :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm"> {{ number_format($igv, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td>GRATUITO :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm">
                                {{ number_format($gratuito + $igvgratuito, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td>DESCUENTOS :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm">
                                {{ number_format($descuentos, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr> --}}
                    <tr>
                        <td>SUBTOTAL :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm">
                                {{ number_format($subtotal, 2, '.', ', ') }}</span>
                            {{-- <span class="font-semibold text-sm">
                                {{ number_format($total, 2, '.', ', ') }}</span> --}}
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>

                    <tr>
                        <td>TOTAL PAGAR :</td>
                        <td class="text-end">
                            <span class="font-semibold text-xl">
                                {{ number_format($total, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>

                            @if ($increment > 0)
                                <br>
                                INC. {{ decimalOrInteger($increment) }}%
                                ({{ number_format($amountincrement, 2, '.', ', ') }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>PENDIENTE :</td>
                        <td class="text-end">
                            <span class="font-semibold text-xl text-red-600">
                                {{ number_format($total - $paymentactual, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>
                </table>
            </div>
        </x-form-card>

        <x-form-card titulo="PAGO PARCIAL" class="text-colorlabel" style="display: none" x-show="typepay > 0">
            <div class="w-full flex flex-col">
                <div class="w-full flex flex-wrap gap-2">
                    @foreach ($parcialpayments as $index => $item)
                        <div class="block text-center size-28 rounded-xl border p-2.5 border-borderminicard">
                            <h1 class="text-lg text-center leading-5 font-semibold text-colorlabel">
                                {{ number_format($item['amount'], 2, '.', ', ') }}</h1>
                            <span class="text-[9px] leading-none text-center text-colorsubtitleform mt-2">
                                {{ $item['method'] }}</span>

                            @if (!empty($item['detalle']))
                                <p
                                    class="w-full block uppercase text-[10px] leading-none text-center text-colorsubtitleform mt-0.5">
                                    <small class="font-semibold">DETALLE</small>
                                    {{ $item['detalle'] }}
                                </p>
                            @endif
                            <div class="w-full text-center">
                                <x-button-delete wire:click="removepay({{ $index }})" />
                            </div>
                        </div>
                    @endforeach
                </div>
                <x-jet-input-error for="parcialpayments" />
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
                        class="bg-amber-500 text-white font-semibold absolute -top-3 -right-1 flex items-center justify-center w-4 h-4 p-0.5 leading-3 rounded-full text-[8px]">
                        {{ count($carshoops) }}</small>
                </button>
            </div>
            @if (count($carshoops) > 0)
                <div class="w-full" x-show="showcart" x-transition>
                    <div class="flex gap-2 flex-wrap justify-start">
                        @foreach ($carshoops as $item)
                            <x-simple-card
                                class="w-full flex flex-col border border-borderminicard justify-between lg:max-w-sm xl:w-full group p-1 text-xs relative overflow-hidden">

                                @if ($item->promocion)
                                    <span
                                        class="bg-red-600 mr-auto inline-block rounded-md text-white text-[9px] font-medium p-1 leading-3">
                                        PROMOCIÓN</span>
                                @endif

                                <h1 class="text-colorlabel w-full text-[10px] leading-3 text-left z-[1]">
                                    <span class="font-semibold text-sm">
                                        {{ decimalOrInteger($item->cantidad) }}
                                        {{ $item->producto->unit->name }}</span>
                                    {{ $item->producto->name }}

                                    @if (count($item->carshoopseries) == 1)
                                        <br>SN: {{ $item->carshoopseries()->first()->serie->serie }}
                                    @endif
                                </h1>

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


                                <div
                                    class="w-full flex items-end gap-2 {{ is_null($item->promocion_id) ? 'justify-between' : 'justify-end' }} mt-2">
                                    @if (is_null($item->promocion_id))
                                        @can('admin.ventas.create.gratuito')
                                            <div>
                                                <x-label-check textSize="[9px]" for="gratuito_{{ $item->id }}">
                                                    <x-input wire:change="updategratis({{ $item->id }})"
                                                        value="1" type="checkbox" id="gratuito_{{ $item->id }}"
                                                        :checked="$item->isGratuito()" />
                                                    GRATUITO</x-label-check>
                                            </div>
                                        @endcan
                                    @endif
                                    <x-button-delete wire:loading.attr="disabled"
                                        @click="confirmDeleteCarshoop('{{ $item->id }}')" />
                                </div>
                            </x-simple-card>
                        @endforeach
                    </div>

                    <div class="w-full flex mt-2">
                        <x-button-secondary wire:loading.attr="disabled" class="inline-block"
                            @click="confirmDeleteAllCarshoop">ELIMINAR TODO</x-button-secondary>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="w-full flex-1 lg:overflow-y-auto soft-scrollbar h-full">
        <x-form-card titulo="PRODUCTOS">
            <div class="w-full">
                @include('modules.ventas.forms.filters')

                @if (count($productos) > 0)
                    @if ($productos->hasPages())
                        <div class="w-full flex justify-end py-2">
                            {{ $productos->onEachSide(0)->links('livewire::pagination-default') }}
                        </div>
                    @endif

                    @include('modules.ventas.forms.productos')
                @else
                    <p class="text-xs text-colorsubtitleform">NO SE ENCONTRARON REGISTROS DE PRODUCTOS</p>
                @endif
            </div>
        </x-form-card>
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

        async function fetchAsyncDatos(ruta, data = {}) {
            try {
                const response = await fetch(ruta, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    console.log('Error en la respuesta del servidor');
                    throw new Error('Error en la respuesta del servidor');
                }

                const datos = await response.json();
                return datos;
            } catch (error) {
                console.error('Error al realizar la petición:', error);
                return null;
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
                codecomprobante: '',
                sendsunat: '',
                openguia: true,
                sincronizegre: @entangle('sincronizegre').defer,

                cotizacion_id: @entangle('cotizacion_id').defer,
                document: @entangle('document').defer,
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
                parcialpayments: @entangle('parcialpayments').defer,
                istransferencia: @entangle('istransferencia').defer,

                ubigeos: [],
                typepayments: [],
                methodpayments: [],
                seriecomprobantes: [],
                searchmarca: @entangle('searchmarca'),
                searchcategory: @entangle('searchcategory'),
                searchsubcategory: @entangle('searchsubcategory'),
                pricetype_id: @entangle('pricetype_id'),

                init() {

                    $(this.$refs.selectcomprobante).select2();
                    $(this.$refs.selectmethodpayment).select2();
                    $(this.$refs.selectpayment).select2();
                    this.obtenerDatos();

                    this.$watch("moneda_id", (value) => {
                        const message = this.updatemonedacart(value);
                    });
                    this.$watch("methodpayment_id", (value) => {
                        this.selectMP.val(value).trigger("change");
                    });
                    this.$watch("seriecomprobante_id", (value, oldvalue) => {
                        let digits = this.document.length ?? 0;
                        if (this.codecomprobante == '01' && digits !== 11) {
                            this.seriecomprobante_id = '';
                            this.selectTC.val(this.seriecomprobante_id).trigger("change");
                            this.$dispatch('validation', {
                                title: 'INGRESE N° RUC VÁLIDO PARA EL COMPROBANTE SELECCIONADO !',
                                text: null
                            });
                            return false;
                        }
                    });

                    Livewire.hook('message.processed', () => {
                        this.selectTC.select2({
                            data: this.seriecomprobantes
                        }).val(this.seriecomprobante_id).trigger('change');

                        this.selectMP.select2({
                            data: this.methodpayments
                        }).val(this.methodpayment_id).trigger('change');
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
                savepay(event) {
                    this.$wire.call('savepay').then(() => {
                        // console.log('function ejecutado correctamente');
                    });
                    event.preventDefault();
                },
                confirmDeleteCarshoop(carshoop_id) {
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
                            const message = this.deleteitem(carshoop_id);
                        }
                    })
                },
                confirmDeleteAllCarshoop() {
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
                            const message = this.deleteAll();
                            // @this.deleteallcarshoop();
                        }
                    })
                },
                addtocarrito(event, producto_id, serie_id, seriealmacen_id) {
                    let form = event.target;
                    let propertyalmacen = 'selectedalmacen_' + producto_id;
                    const cart = {
                        price: form.price.value,
                        cantidad: form.cantidad == undefined ? 1 : form.cantidad.value,
                        serie: form.serie == undefined ? null : form.serie.value,
                        serie_id: form.serie_id == undefined ? null : form.serie_id.value,
                        almacen_id: form[propertyalmacen] == undefined ? null : form[
                            propertyalmacen].value
                    };
                    // console.log(cart, serie_id, seriealmacen_id);
                    this.$wire.addtocar(producto_id, cart, serie_id, seriealmacen_id).then(result => {})
                        .catch(error => {
                            console.error(error);
                        });
                },
                async updatemonedacart(moneda_id) {
                    const route =
                        "{{ route('admin.carshoop.updatemoneda', ['moneda_id' => ':moneda_id']) }}"
                        .replace(':moneda_id', moneda_id);
                    const response = await axios.post(route, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        moneda_id: moneda_id
                    }).catch(function(error) {
                        console.log(error);
                    });

                    if (response.status === 200) {
                        this.$wire.call('setTotal').then(() => {
                            console.log('setTotal ejecutado correctamente');
                        });
                    } else {
                        throw new Error('Error al actualizar moneda del carrito');
                    }
                },
                async deleteitem(carshoop_id) {
                    try {
                        const route =
                            "{{ route('admin.carshoop.delete', ['carshoop' => ':carshoop_id']) }}"
                            .replace(':carshoop_id', carshoop_id);
                        const response = await axios.post(route, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })

                        if (response.status === 200) {
                            if (response.data.success) {
                                this.$wire.call('setTotal').then(() => {
                                    console.log('setTotal ejecutado correctamente');
                                });
                            } else {
                                console.log('Error al eliminar item del carrito de ventas.');
                                // window.dispatchEvent(new CustomEvent('validation', {
                                //     detail: {
                                //         title: response.data.mensaje,
                                //         text: 

                                //     }
                                // }));
                            }
                        } else {
                            throw new Error('Error al vaciar el carrito');
                        }
                    } catch (error) {
                        console.error('Error al vaciar el carrito:', error);
                        throw error;
                    }
                },
                async deleteAll() {
                    try {
                        const response = await axios.post(
                            "{{ route('admin.carshoop.delete.all') }}", {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            })

                        if (response.status === 200) {
                            this.$wire.call('setTotal').then(() => {
                                // console.log('setTotal ejecutado correctamente');
                            });
                        } else {
                            throw new Error('Error al eliminar el carrito');
                        }
                    } catch (error) {
                        console.error('Error al eliminar el carrito:', error);
                        throw error;
                    }
                },
                async obtenerDatos() {
                    const ROUTE = {
                        typepayments: "{{ route('admin.ventas.typepayments.list') }}",
                        methodpayments: "{{ route('admin.ventas.methodpayments.list') }}",
                        seriecomprobantes: "{{ route('admin.ventas.seriecomprobantes.list') }}",
                        ubigeos: "{{ route('admin.ventas.ubigeos.list') }}"
                    }

                    // const [typepayments, methodpayments] = await Promise.all([
                    //     fetchAsyncDatos(ROUTE.typepayments),
                    //     fetchAsyncDatos(ROUTE.methodpayments)
                    // ]);

                    this.typepayments = await fetchAsyncDatos(ROUTE.typepayments);
                    this.selectPayment()
                    this.methodpayments = await fetchAsyncDatos(ROUTE.methodpayments);
                    this.selectMethodpayment()
                    this.seriecomprobantes = await fetchAsyncDatos(ROUTE.seriecomprobantes);
                    this.selectComprobante()
                    this.ubigeos = await fetchAsyncDatos(ROUTE.ubigeos);
                    this.selectUbigeoEmision()
                    this.selectUbigeoDestino()
                },
                selectUbigeoEmision() {
                    this.selectUE = $(this.$refs.ubigeoemision).select2({
                        data: this.ubigeos
                    });
                    this.selectUE.val(this.ubigeoorigen_id).trigger("change");
                    this.selectUE.on("select2:select", (event) => {
                        this.ubigeoorigen_id = event.target.value;
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                    this.$watch("ubigeoorigen_id", (value) => {
                        this.selectUE.val(value).trigger("change");
                    });
                    Livewire.hook('message.processed', () => {
                        this.selectUE.select2('destroy');
                        this.selectUE.select2({
                            data: this.ubigeos
                        }).val(this.ubigeoorigen_id).trigger('change');
                    });
                },
                selectUbigeoDestino() {
                    this.selectUD = $(this.$refs.ubigeodestino).select2({
                        data: this.ubigeos
                    });
                    this.selectUD.val(this.ubigeodestino_id).trigger("change");
                    this.selectUD.on("select2:select", (event) => {
                        this.ubigeodestino_id = event.target.value;
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                    this.$watch("ubigeodestino_id", (value) => {
                        this.selectUD.val(value).trigger("change");
                    });
                    Livewire.hook('message.processed', () => {
                        this.selectUD.select2('destroy');
                        this.selectUD.select2({
                            data: this.ubigeos
                        }).val(this.ubigeodestino_id).trigger('change');
                    });
                },
                selectMethodpayment() {
                    this.selectMP = $(this.$refs.selectmethodpayment).select2({
                        data: this.methodpayments,
                    }).on('select2:open', (e) => {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    }).on('change', (e) => {
                        this.methodpayment_id = e.target.value
                        // console.log($(e.target).select2('data')[0]);
                        const paramsData = $(e.target).select2('data')[0];
                        if (paramsData) {
                            this.istransferencia = paramsData.transferencia;
                        } else {
                            this.istransferencia = false;
                        }
                    }).val(this.methodpayment_id).trigger("change");
                },
                selectPayment() {
                    this.selectTP = $(this.$refs.selectpayment).select2({
                        data: this.typepayments
                    }).val(this.typepayment_id).trigger("change");
                    this.selectTP.on("select2:select", (event) => {
                        this.$wire.set('typepayment_id', event.target.value, true)
                        this.$wire.$refresh()
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                    this.$watch("typepayment_id", (value) => {
                        this.selectTP.val(value).trigger("change");
                    });

                    Livewire.hook('message.processed', () => {
                        let typepayment = this.typepayments.find(item => item.id == this
                            .typepayment_id);
                        if (typepayment) {
                            switch (typepayment.paycuotas) {
                                case true:
                                    this.paymentcuotas = true;
                                    this.typepay = '1';
                                    break;
                                case false:
                                    this.paymentcuotas = false;
                                    break;
                                default:
                                    this.paymentcuotas = false;
                                    this.formapago = '';
                            }
                        } else {
                            this.paymentcuotas = false;
                        }
                        this.selectTP.select2({
                            data: this.typepayments,
                            templateResult: function(item) {
                                var $option = $('<span data-paycuotas="' + item
                                    .paycuotas +
                                    '">' + item.text + '</span>'
                                );
                                return $option;
                            },
                        }).val(this.typepayment_id).trigger('change');
                    });
                },
                selectComprobante() {
                    this.selectTC = $(this.$refs.selectcomprobante).select2({
                        data: this.seriecomprobantes,
                    }).on('change', (e) => {
                        this.seriecomprobante_id = e.target.value
                        const paramsData = $(e.target).select2('data')[0];
                        if (paramsData) {
                            this.sendsunat = paramsData.sunat;
                            this.codecomprobante = paramsData.code;
                            switch (this.sendsunat) {
                                case false:
                                    this.incluyeguia = false;
                                    this.openguia = false;
                                    break;
                                case true:
                                    this.openguia = true;
                                    break;
                                default:
                                    this.openguia = false;
                                    this.incluyeguia = false;
                                    this.sendsunat = '';
                            }
                        } else {
                            this.openguia = false;
                            this.incluyeguia = false;
                            this.sendsunat = '';
                        }
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    }).val(this.seriecomprobante_id).trigger("change");

                }
                // getSelectedData(ref, propiedad, nameData) {
                //     const selectData = $(this.$refs[ref]).select2('data')[0];
                //     this[propiedad] = selectData.transferencia;
                // }
            }));
        })

        // function formatOption(option) {
        //     var option = $('<span data-prueba="' + option.transferencia + '">' + option.text + '</span>');
        //     return option;
        // }
    </script>
</div>

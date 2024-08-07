<div>
    <x-button-next titulo="Nuevo movimiento" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo movimiento caja') }}
        </x-slot>

        <x-slot name="content">
            @if (count($diferencias) > 0)
                <div class="w-full flex flex-wrap gap-2 justify-end">
                    @foreach ($diferencias as $item)
                        <x-minicard :title="null" size="lg" class="cursor-pointer text-colorlabel">
                            <div class="text-xs font-medium text-center">
                                <small>SALDO CAJA</small>
                                <h3 class="font-semibold text-xl">
                                    {{ number_format($item->diferencia, 2, '.', ', ') }}
                                </h3>
                                <small>{{ $item->moneda->currency }}</small>
                            </div>
                        </x-minicard>
                    @endforeach
                </div>
            @endif

            @if ($monthbox)
                <p class="text-colorlabel text-md md:text-3xl font-semibold text-end mt-2 mb-5">
                    <small class="text-[10px] font-medium w-full block leading-3">CAJA MENSUAL</small>
                    {{ formatDate($monthbox->month, 'MMMM Y') }}
                    <small class="w-full block font-medium text-xs">{{ $openbox->box->name }}</small>
                </p>
            @else
                <p class="text-colorerror text-[10px] text-end">APERTURA DE CAJA MENSUAL NO DISPONIBLE...</p>
            @endif

            <div class="w-full">
                <p class="text-colorlabel text-xl font-semibold text-end">
                    <small class="text-[10px] font-medium">APERTURA S/. </small>
                    {{ number_format($openbox->apertura, 2, '.', ', ') }}
                    <small class="text-[10px] font-medium">SOLES</small>
                </p>

                @if (count($sumatorias) > 0)
                    @foreach ($sumatorias as $item)
                        @php
                            $color = $item->typemovement->value == 'INGRESO' ? 'text-green-500' : 'text-red-500';
                        @endphp
                        <p class="text-colorminicard text-xl font-semibold text-end {{ $color }}">
                            <small class="text-[10px] font-medium">
                                {{ $item->typemovement->value }} {{ $item->moneda->simbolo }}</small>
                            {{ number_format($item->total, 2, '.', ', ') }}
                            <small class="text-[10px] font-medium">{{ $item->moneda->currency }}</small>
                        </p>
                    @endforeach
                @endif
            </div>


            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2" x-data="cajamovimiento">
                <div class="w-full">
                    <x-label value="Monto :" />
                    <x-input class="block w-full" x-model="amount" @change="calcular" type="number" placeholder="0.00"
                        onkeypress="return validarDecimal(event, 9)" step="0.001" />
                    <x-jet-input-error for="amount" />
                </div>

                <div class="w-full">
                    <x-label value="Moneda :" />
                    <div class="relative" x-data="{ moneda_id: @entangle('moneda_id').defer }" x-init="MonedaPagoManual" wire:ignore>
                        <x-select class="block w-full" id="monedampman_id" wire:model.defer="moneda_id"
                            data-dropdown-parent="null" x-ref="selectmpman">
                            <x-slot name="options">
                                @if (count($monedas) > 0)
                                    @foreach ($monedas as $item)
                                        <option value="{{ $item->id }}" data-code="{{ $item->code }}"
                                            data-simbolo="{{ $item->simbolo }}" data-currency="{{ $item->currency }}">
                                            {{ $item->currency }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="moneda_id" />
                </div>

                {{-- <div class="w-full">
                    <x-label textSize="[10px]"
                        class="inline-flex items-end tracking-normal font-semibold gap-1 cursor-pointer text-textspancardproduct">
                        <x-input x-model="showtipocambio" name="tipocambio" type="checkbox" id="tipocambio" />
                        USAR TIPO CAMBIO
                    </x-label>

                    <div class="w-full" x-show="showtipocambio">
                        <x-label value="Tipo cambio :" />
                        <x-input class="block w-full" x-model="tipocambio" @change="calcular" type="number"
                            placeholder="0.00" onkeypress="return validarDecimal(event, 7)" step="0.001"
                            min="0.001" />
                        <x-jet-input-error for="tipocambio" />

                        <div class="w-full text-xs text-end text-neutral-500 font-semibold" x-show="totalamount > 0">
                            <small class="inline-block" x-text="simbolo"></small>
                            <template x-if="totalamount > 0">
                                <h1 x-text="totalamount" class="text-2xl inline-block"></h1>
                            </template>
                            <template x-if="totalamount == null">
                                <small class="inline-block text-colorerror">SELECCIONAR TIPO DE MONEDA...</small>
                            </template>
                            <small class="inline-block" x-text="currency"></small>
                        </div>
                    </div>
                </div> --}}

                <div class="w-full">
                    <x-label value="Forma pago :" />
                    <div class="relative" x-data="{ methodpayment_id: @entangle('methodpayment_id').defer }" x-init="FormaPagoManual">
                        <x-select class="block w-full" id="methodpaymentman_id" wire:model.defer="methodpayment_id"
                            data-dropdown-parent="null" x-ref="selectfpman">
                            <x-slot name="options">
                                @if (count($methodpayments) > 0)
                                    @foreach ($methodpayments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="methodpayment_id" />
                </div>

                <div class="w-full">
                    <x-label value="Concepto pago :" />
                    <div class="relative" x-data="{ concept_id: @entangle('concept_id').defer }" x-init="ConceptPagoManual">
                        <x-select class="block w-full" id="conceptmp_id" wire:model.defer="concept_id"
                            data-dropdown-parent="null" x-ref="selectcpm">
                            <x-slot name="options">
                                @if (count($concepts) > 0)
                                    @foreach ($concepts as $item)
                                        <option value="{{ $item->id }}" title="{{ $item->typemovement->value }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="concept_id" />
                </div>

                <div>
                    <x-label value="Detalle :" />
                    <x-input class="block w-full" wire:model.defer="detalle"
                        placeholder="Ingrese detalle del movimiento..." />
                    <x-jet-input-error for="detalle" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        // function totalamount() {
        //     return {
        //         showtipocambio: true,
        //         amount: @entangle('amunt').defer,
        //         totalamount: '0.00',
        //         calcular(value) {
        //             this.modelOpen = true;
        //             document.body.style.overflow = 'hidden';
        //         },
        //     }
        // }

        document.addEventListener('alpine:init', () => {
            Alpine.data('cajamovimiento', () => ({
                showtipocambio: @entangle('showtipocambio').defer,
                amount: @entangle('amount').defer,
                tipocambio: @entangle('tipocambio').defer,
                totalamount: @entangle('totalamount').defer,
                simbolo: null,
                code: null,
                currency: null,

                init() {
                    this.$watch("showtipocambio", (value) => {
                        this.tipocambio = null;
                        this.totalamount = '0.000';
                    });
                },
                calcular() {
                    if (this.code == 'PEN') {
                        if (toDecimal(this.amount) > 0 && toDecimal(this.tipocambio) > 0) {
                            this.totalamount = toDecimal(this.amount / this.tipocambio, 3);
                        }
                    } else if (this.code == 'USD') {
                        if (toDecimal(this.amount) > 0 && toDecimal(this.tipocambio) > 0) {
                            this.totalamount = toDecimal(this.amount * this.tipocambio, 3);
                        }
                    } else {
                        this.totalamount = null
                    }
                }
            }))
        })



        function FormaPagoManual() {
            this.selectFPMA = $(this.$refs.selectfpman).select2();
            this.selectFPMA.val(this.methodpayment_id).trigger("change");
            this.selectFPMA.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('methodpayment_id', (value) => {
                this.selectFPMA.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectFPMA.select2().val(this.methodpayment_id).trigger('change');
            });
        }

        function MonedaPagoManual() {
            this.selectMPMA = $(this.$refs.selectmpman).select2();
            this.selectMPMA.val(this.moneda_id).trigger("change");
            this.selectMPMA.on("select2:select", (event) => {
                this.moneda_id = event.target.value;
                const data = event.params.data.element.dataset;
                this.code = event.params.data.element.dataset.code;
                console.log(this.code);

                if (this.code == 'PEN') {
                    this.simbolo = '$.';
                    this.currency = 'DÓLARES';
                } else if (this.code == 'USD') {
                    this.simbolo = 'S/.';
                    this.currency = 'SOLES';
                }
                this.calcular();
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('moneda_id', (value) => {
                this.selectMPMA.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectMPMA.select2().val(this.moneda_id).trigger('change');
            });
        }

        function ConceptPagoManual() {
            this.selectCPM = $(this.$refs.selectcpm).select2({
                templateResult: formatOption
            });
            this.selectCPM.val(this.concept_id).trigger("change");
            this.selectCPM.on("select2:select", (event) => {
                this.concept_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('concept_id', (value) => {
                this.selectCPM.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectCPM.select2({
                    templateResult: formatOption
                }).val(this.concept_id).trigger('change');
            });
        }

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text + '</strong><p class="select2-subtitle-option">' + option.title +
                '</p>'
            );
            return $option;
        };
    </script>
</div>

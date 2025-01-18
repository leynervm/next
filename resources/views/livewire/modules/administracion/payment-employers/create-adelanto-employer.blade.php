<div>
    <x-button-next titulo="Adelantos personal" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 18.5C14 18.5 15 18.5 16 20.5C16 20.5 19.1765 15.5 22 14.5" />
            <path d="M5.5 11.5H5.49102" />
            <path
                d="M11 19.5H10.5C6.74142 19.5 4.86213 19.5 3.60746 18.5091C3.40678 18.3506 3.22119 18.176 3.0528 17.9871C2 16.8062 2 15.0375 2 11.5C2 7.96252 2 6.19377 3.0528 5.0129C3.22119 4.82403 3.40678 4.64935 3.60746 4.49087C4.86213 3.5 6.74142 3.5 10.5 3.5H13.5C17.2586 3.5 19.1379 3.5 20.3925 4.49087C20.5932 4.64935 20.7788 4.82403 20.9472 5.0129C21.8957 6.07684 21.9897 7.61799 21.999 10.5V11" />
            <path
                d="M14.5 11.5C14.5 12.8807 13.3807 14 12 14C10.6193 14 9.5 12.8807 9.5 11.5C9.5 10.1193 10.6193 9 12 9C13.3807 9 14.5 10.1193 14.5 11.5Z" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo adelanto personal') }}
        </x-slot>

        <x-slot name="content">
            @if ($monthbox)
                <p class="text-colorlabel text-md md:text-3xl font-semibold text-end mt-2 mb-5">
                    <small class="text-[10px] font-medium w-full block leading-3">CAJA MENSUAL</small>
                    {{ formatDate($monthbox->month, 'MMMM Y') }}
                    <small class="w-full block font-medium text-xs">{{ $openbox->box->name }}</small>
                </p>
            @else
                <p class="text-colorerror text-[10px] text-end">APERTURA DE CAJA MENSUAL NO DISPONIBLE...</p>
            @endif

            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2" x-data="adelantoemployer">
                <div>
                    <x-label value="Seleccionar moneda pago :" />
                    @if (count($diferencias) > 0)
                        <div class="w-full flex flex-wrap gap-2 justify-start items-center">
                            @foreach ($diferencias as $item)
                                <div class="inline-flex">
                                    <input class="sr-only peer" x-model="moneda_id" type="radio" name="monedas"
                                        id="monedapagoempl_{{ $item->moneda_id }}" value="{{ $item->moneda_id }}"
                                        @change="getCodeMoneda('{{ $item->moneda }}')" />
                                    <x-label-check-moneda for="monedapagoempl_{{ $item->moneda_id }}" :simbolo="$item->moneda->simbolo"
                                        :saldo="$item->diferencia" :diferenciasbytype="$diferenciasbytype->where('moneda_id', $item->moneda_id)" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <x-jet-input-error for="moneda_id" />
                </div>

                <div class="w-full">
                    <x-label value="Personal :" />
                    <div class="relative" x-init="SelectEmployer">
                        <x-select class="block w-full" id="employ_id" data-dropdown-parent="null" x-ref="selectemployer"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($employers) > 0)
                                    @foreach ($employers as $item)
                                        <option value="{{ $item->id }}" title="{{ $item->areawork->name ?? '' }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="employer_id" />
                </div>

                <div class="w-full">
                    <x-label value="Monto :" />
                    <x-input class="block w-full input-number-none" x-model="amount" type="number" placeholder="0.00"
                        onkeypress="return validarDecimal(event, 9)" step="0.001" @input="calcular" />
                    <x-jet-input-error for="amount" />
                </div>

                <div class="w-full" x-cloak style="display: none;" x-show="showtipocambio">
                    <div class="w-full">
                        <x-label value="Tipo cambio :" />
                        <x-input class="block w-full" x-model="tipocambio" @input="calcular" type="number"
                            placeholder="0.00" onkeypress="return validarDecimal(event, 7)" step="0.001"
                            min="0.001" />
                        <x-jet-input-error for="tipocambio" />
                    </div>

                    <div class="w-full text-xs text-end text-colorsubtitleform font-semibold" x-show="totalamount > 0"
                        x-cloak style="display: none;">
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

                <div class="w-full">
                    <x-label value="Forma pago :" />
                    <div class="relative" x-data="{ methodpayment_id: @entangle('methodpayment_id').defer }" x-init="FormaPago">
                        <x-select class="block w-full" id="methodpaymentae_id" wire:model.defer="methodpayment_id"
                            data-dropdown-parent="null" x-ref="selectmep">
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

                <div>
                    <x-label value="Detalle :" />
                    <x-text-area class="block w-full" wire:model.defer="detalle"></x-text-area>
                    <x-jet-input-error for="detalle" />
                    <x-jet-input-error for="concept_id" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>

            @if ($adelantos)
                @if (count($adelantos) > 0)
                    <h1 class="text-colorlabel text-xs my-3 font-semibold">HISTORIAL ADELANTOS</h1>
                    <div class="w-full flex flex-wrap gap-2">
                        @foreach ($adelantos as $item)
                            <x-minicard :title="null" size="lg" class="cursor-pointer text-colortitleform">
                                <div class="text-xs font-medium text-center">
                                    <small>{{ formatDate($item->date, 'DD MMMM Y') }}</small>
                                    <h3 class="font-semibold text-xl">
                                        {{ number_format($item->amount, 2, '.', ', ') }}
                                        <small class="text-[10px] font-medium">{{ $item->moneda->currency }}</small>
                                    </h3>

                                </div>
                            </x-minicard>
                        @endforeach
                    </div>
                @endif
            @endif
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adelantoemployer', () => ({
                employer_id: @entangle('employer_id'),
                showtipocambio: @entangle('showtipocambio').defer,
                amount: @entangle('amount').defer,
                tipocambio: @entangle('tipocambio').defer,
                totalamount: @entangle('totalamount').defer,
                moneda_id: @entangle('moneda_id').defer,
                simbolo: null,
                code: null,
                currency: null,

                init() {
                    this.$watch("showtipocambio", (value) => {
                        this.tipocambio = null;
                        this.totalamount = '0.00';
                    });
                },
                getCodeMoneda(moneda) {
                    const monedaJSON = JSON.parse(moneda);
                    this.code = monedaJSON.code;
                    if (this.code == 'PEN') {
                        this.simbolo = '$.';
                        this.currency = 'DÓLARES';
                        this.showtipocambio = false;
                    } else if (this.code == 'USD') {
                        this.simbolo = 'S/.';
                        this.currency = 'SOLES';
                        this.showtipocambio = true;
                    }
                    this.calcular();
                    this.currency = monedaJSON.currency;
                    this.simbolo = monedaJSON.simbolo;
                },
                calcular() {
                    if (this.code == 'PEN') {
                        if (toDecimal(this.amount) > 0 && toDecimal(this.tipocambio) > 0) {
                            this.totalamount = toDecimal(this.amount * this.tipocambio, 2);
                        } else {
                            this.totalamount = '0.00'
                        }
                    } else if (this.code == 'USD') {
                        if (toDecimal(this.amount) > 0 && toDecimal(this.tipocambio) > 0) {
                            this.totalamount = toDecimal(this.amount / this.tipocambio, 2);
                        } else {
                            this.totalamount = '0.00'
                        }
                    } else {
                        this.totalamount = null
                    }
                }
            }))
        })

        function FormaPago() {
            this.selectFPM = $(this.$refs.selectmep).select2();
            this.selectFPM.val(this.methodpayment_id).trigger("change");
            this.selectFPM.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('methodpayment_id', (value) => {
                this.selectFPM.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectFPM.select2().val(this.methodpayment_id).trigger('change');
            });
        }

        function SelectEmployer() {
            this.selectEMP = $(this.$refs.selectemployer).select2({
                templateResult: formatOption
            });
            this.selectEMP.val(this.employer_id).trigger("change");
            this.selectEMP.on("select2:select", (event) => {
                this.employer_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('employer_id', (value) => {
                this.selectEMP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectEMP.select2({
                    templateResult: formatOption
                }).val(this.employer_id).trigger('change');
            });
        }

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text + '</strong><p class="select2-subtitle-option">' + option.title + '</p>'
            );
            return $option;
        };
    </script>
</div>

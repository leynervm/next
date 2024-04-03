<div>
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo pago personal') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="relative w-full flex flex-col gap-2">
                @if ($monthbox)
                    <p class="text-colorlabel text-md md:text-3xl font-semibold text-end mt-2 mb-5">
                        <small class="text-[10px] font-medium w-full block leading-3">CAJA MENSUAL</small>
                        {{ formatDate($monthbox->month, 'MMMM Y') }}
                        @if ($openbox)
                            <small class="w-full block font-medium text-xs">{{ $openbox->box->name }}</small>
                        @else
                            <small class="text-colorerror w-full block font-medium text-[10px] leading-3">
                                APERTURA DE CAJA DIARIA NO DISPONIBLE...
                            </small>
                        @endif
                    </p>
                @else
                    <p class="text-colorerror text-[10px] text-end">APERTURA DE CAJA MENSUAL NO DISPONIBLE...</p>
                @endif

                <p class="text-colorlabel text-md font-semibold text-end">
                    <small class="text-[10px] font-medium w-full block leading-3">SUELDO</small>
                    {{ number_format($employer->sueldo, 2, '.', ', ') }}
                </p>

                <p class="text-colorlabel text-md font-semibold text-end">
                    <small class="text-[10px] font-medium w-full block leading-3">TOTAL ADELANTOS</small>
                    {{ number_format($adelantos, 2, '.', ', ') }}
                </p>

                @if (count($employer->cajamovimientos))
                    <div class="w-full flex flex-wrap justify-end gap-2">
                        @foreach ($employer->cajamovimientos as $item)
                            <x-simple-card
                                class="!bg-transparent border border-borderminicard text-colorlabel rounded-md p-3">
                                <p class="text-[10px]">{{ formatDate($item->date, 'DD MMMM Y') }}</p>
                                <p class="text-right font-semibold text-xl">
                                    {{ number_format($item->amount, 2, '.', ', ') }}</p>
                            </x-simple-card>
                        @endforeach
                    </div>
                @endif

                <p class="text-colorlabel text-md md:text-3xl font-semibold">
                    <small class="text-[10px] font-medium w-full block leading-3">TOTAL PAGAR</small>
                    {{ number_format($employer->sueldo + $bonus - ($descuentos + $adelantos), 2, '.', ', ') }}
                </p>

                <div class="w-full grid gap-2">
                    <div class="w-full">
                        <x-label value="Monto pagar :" />
                        <x-input class="block w-full" wire:model.lazy="amount" type="number" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="amount" />
                    </div>

                    <div class="w-full">
                        <x-label value="Descuentos :" />
                        <x-input class="block w-full" wire:model.lazy="descuentos" type="number" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="descuentos" />
                    </div>

                    <div class="w-full">
                        <x-label value="Bonus :" />
                        <x-input class="block w-full" wire:model.lazy="bonus" type="number" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="bonus" />
                    </div>

                    <div class="w-full">
                        <x-label value="Forma pago :" />
                        <div class="relative" id="methodpayment_id" x-data="{ methodpayment_id: @entangle('methodpayment_id').defer }" x-init="select2Methodpayment"
                            wire:ignore>
                            <x-select class="block w-full" wire:model.defer="methodpayment_id" x-ref="selectmp"
                                id="methodpayment_id" data-dropdown-parent="null">
                                <x-slot name="options">
                                    @if (count($methodpayments))
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
                        <x-label value="Detalle :" />
                        <x-input class="block w-full" wire:model.defer="detalle" />
                        <x-jet-input-error for="detalle" />
                        <x-jet-input-error for="concept_id" />
                        <x-jet-input-error for="openbox.id" />
                        <x-jet-input-error for="monthbox.id" />
                        <x-jet-input-error for="moneda_id" />
                        <x-jet-input-error for="month" />
                    </div>
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>

                <div wire:loading.flex class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        function select2Methodpayment() {
            this.selectMP = $(this.$refs.selectmp).select2();
            this.selectMP.val(this.methodpayment_id).trigger("change");
            this.selectMP.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectMP.val(value).trigger("change");
            });
        }
    </script>
</div>

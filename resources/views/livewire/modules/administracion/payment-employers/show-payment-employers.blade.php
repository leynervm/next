<div>
    @if ($payments->hasPages())
        <div class="pb-2">
            {{ $payments->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex items-center gap-2 mt-4 mb-1">
        {{-- @if (count($sucursalusers) > 1) --}}
        <div class="w-full xs:max-w-xs">
            <x-label value="Filtrar mes :" />
            <div class="relative" id="parentsearchmonth" x-data="{ searchmonth: @entangle('searchmonth') }" x-init="selectSearchmonth">
                <x-select class="block w-full" x-ref="selectmonth" wire:model.defer="searchmonth" id="searchmonth"
                    data-minimum-results-for-search="3" data-placeholder="null">
                    <x-slot name="options">
                        @foreach ($months as $item)
                            <option value="{{ $item->month }}">
                                {{ formatDate($item->month, 'MMMM Y') }}
                            </option>
                        @endforeach
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="searchmonth" />
        </div>
        {{-- @endif --}}
    </div>

    @if (count($payments))
        <div class="w-full flex flex-col gap-5 mt-5">
            @foreach ($payments as $item)
                @php
                    $paymentactual = $item->amount + $item->adelantos;
                    $paymenttotal = $employer->sueldo + $item->bonus - $item->descuentos;
                @endphp

                <x-simple-card class="flex flex-col gap-1 rounded-md cursor-default p-3">
                    <div class="w-full sm:flex sm:gap-3">
                        <div class="w-full text-colortitleform">
                            <h1 class="font-semibold text-sm leading-4">
                                {{ formatDate($item->month, 'MMMM Y') }}
                                - {{ $employer->name }}
                            </h1>

                            <h1 class="text-colorsubtitleform font-medium text-xs">
                                {{ $employer->sucursal->name }}
                                @if ($employer->sucursal->trashed())
                                    <x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal inline-block" />
                                @endif
                            </h1>

                            <h1 class="text-xs leading-4">
                                {{ formatDate($item->cajamovimientos->latest()->first()->date) }}
                            </h1>

                            <h3 class="font-semibold text-3xl leading-normal">
                                {{ number_format($item->amount + $item->adelantos, 2, '.', ', ') }}
                            </h3>
                        </div>

                        <div class="w-full text-colortitleform">
                            <h3 class="font-semibold text-xs text-end leading-3">
                                <small class="font-medium">SUELDO </small>
                                {{ number_format($employer->sueldo, 2, '.', ', ') }}
                            </h3>

                            @if ($item->bonus > 0)
                                <h3 class="font-semibold text-xs text-end leading-3 text-green-500">
                                    <small class="font-medium">BONUS</small>
                                    + {{ number_format($item->bonus, 2, '.', ', ') }}
                                </h3>
                            @endif

                            <h3 class="font-semibold text-xs text-end leading-3">
                                <small class="font-medium">ADELANTOS</small>
                                {{ number_format($item->adelantos, 2, '.', ', ') }}
                            </h3>

                            @if ($item->descuentos > 0)
                                <h3
                                    class="font-semibold text-xs text-end leading-3 @if ($item->descuentos > 0) text-red-500 @endif">
                                    <small class="font-medium">DESCUENTOS</small>
                                    - {{ number_format($item->descuentos, 2, '.', ', ') }}
                                </h3>
                            @endif

                            @if ($paymentactual < $paymenttotal)
                                <h3 class="font-semibold text-xs text-end leading-3">
                                    <small class="font-medium">PENDIENTE</small>
                                    {{ number_format($employer->sueldo + $item->bonus - ($item->adelantos + $item->descuentos + $item->amount), 2, '.', ', ') }}
                                </h3>
                            @endif
                        </div>
                    </div>

                    <div
                        class="w-full flex items-end @if ($paymentactual < $paymenttotal) justify-between @else justify-end @endif">
                        @can('admin.administracion.employers.payments.create')
                            @if ($paymentactual < $paymenttotal)
                                <x-button wire:click="pay({{ $item->id }})" wire:key="pay_({{ $item->id }}"
                                    wire:loading.attr="disabled">PAGAR</x-button>
                            @endif
                        @endcan

                        @can('admin.administracion.employers.payments.delete')
                            <x-button-delete onclick="confirmDelete( {{ $item }})" wire:loading.attr="disabled" />
                        @endcan
                    </div>
                </x-simple-card>
            @endforeach
        </div>
    @endif

    <x-jet-dialog-modal wire:model="open" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo pago personal') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="relative w-full flex flex-col gap-2">
                <p class="text-colorminicard text-md font-semibold">
                    <small class="text-[10px] font-medium w-full block leading-3">SUELDO</small>
                    {{ number_format($employer->sueldo, 2, '.', ', ') }}
                </p>

                <p class="text-colorminicard text-md font-semibold">
                    <small class="text-[10px] font-medium w-full block leading-3">TOTAL ADELANTOS</small>
                    {{ number_format($employerpayment->adelantos, 2, '.', ', ') }}
                </p>

                <p class="text-colorminicard text-md md:text-3xl font-semibold">
                    <small class="text-[10px] font-medium w-full block leading-3">MES PAGO</small>
                    {{ formatDate($employerpayment->month, 'MMMM Y') }}
                    <small class="w-full block font-medium text-xs">{{ $openbox->box->name }}</small>
                </p>

                <p class="text-colorminicard text-md md:text-3xl font-semibold">
                    <small class="text-[10px] font-medium w-full block leading-3">TOTAL PAGAR</small>
                    {{ number_format($employer->sueldo + $employerpayment->bonus - ($employerpayment->amount + $employerpayment->descuentos + $employerpayment->adelantos), 2, '.', ', ') }}
                </p>

                <div class="w-full grid gap-2">
                    <div class="w-full">
                        <x-label value="Monto pagar :" />
                        <x-input class="block w-full" wire:model.defer="amount" type="number" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="amount" />
                    </div>

                    <div class="w-full">
                        <x-label value="Forma pago :" />
                        <div class="relative" id="parentmethodpaymente_id" x-data="{ methodpayment_id: @entangle('methodpayment_id').defer }"
                            x-init="select2MethodpaymentE" wire:ignore>
                            <x-select class="block w-full" wire:model.defer="methodpayment_id" x-ref="selectmpe"
                                id="methodpaymente_id" data-dropdown-parent="null">
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
        function select2MethodpaymentE() {
            this.selectMPE = $(this.$refs.selectmpe).select2();
            this.selectMPE.val(this.methodpayment_id).trigger("change");
            this.selectMPE.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectMPE.val(value).trigger("change");
            });
        }

        function selectSearchmonth() {
            this.selectSM = $(this.$refs.selectmonth).select2();
            this.selectSM.val(this.searchmonth).trigger("change");
            this.selectSM.on("select2:select", (event) => {
                this.searchmonth = event.target.value;
                console.log(this.searchmonth);
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchmonth", (value) => {
                this.selectSM.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectSM.select2().val(this.searchmonth).trigger('change');
            });
        }

        function confirmDelete(payment) {
            swal.fire({
                title: 'Eliminar pago del personal ?',
                text: "Se eliminará un registro de pagos de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(payment.id);
                }
            })
        }
    </script>
</div>

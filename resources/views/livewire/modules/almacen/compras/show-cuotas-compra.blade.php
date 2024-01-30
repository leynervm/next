<div>
    <x-form-card titulo="CUOTAS PAGO" subtitulo="Información de cuotas de pago de la compra.">
        @if (count($compra->cuotas))
            <div class="w-full flex flex-col gap-2">
                <div class="w-full flex gap-2 flex-wrap justify-start">
                    @foreach ($compra->cuotas as $item)
                        <x-card-cuota class="w-full xs:w-60" :titulo="null" :detallepago="$item->cajamovimiento"
                            :wire:key="'cardcuota-'.$item->id">
                            <p class="text-colorminicard text-xl font-semibold text-center">
                                <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                                {{ number_format($item->amount, 2, '.', ', ') }}
                                <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                            </p>

                            <div class="w-full flex flex-wrap gap-2 justify-center">
                                <x-span-text :text="'Cuota' . substr('000' . $item->cuota, -3)" class="leading-3 !tracking-normal" />
                                <x-span-text :text="formatDate($item->expiredate, 'DD MMMM Y')" class="leading-3 !tracking-normal" />
                            </div>

                            <x-slot name="footer">
                                @if ($item->cajamovimiento)
                                    <div class="w-full flex gap-2 flex-wrap items-end justify-between">
                                        <x-mini-button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M17.571 18H20.4a.6.6 0 00.6-.6V11a4 4 0 00-4-4H7a4 4 0 00-4 4v6.4a.6.6 0 00.6.6h2.829M8 7V3.6a.6.6 0 01.6-.6h6.8a.6.6 0 01.6.6V7" />
                                                <path
                                                    d="M6.098 20.315L6.428 18l.498-3.485A.6.6 0 017.52 14h8.96a.6.6 0 01.594.515L17.57 18l.331 2.315a.6.6 0 01-.594.685H6.692a.6.6 0 01-.594-.685z" />
                                                <path d="M17 10.01l.01-.011" />
                                            </svg>
                                        </x-mini-button>
                                        <x-button-delete
                                            wire:click="$emit('compra.confirmDeletePay', {{ $item }})"
                                            wire:loading.attr="disabled" />
                                    </div>
                                @else
                                    <div class="w-full flex gap-2 flex-wrap items-end justify-between">
                                        <x-button wire:key="paycuota_{{ $item->id }}"
                                            wire:click="paycuota({{ $item->id }})"
                                            wire:loading.attr="disabled">PAGAR</x-button>

                                        <x-button-delete
                                            wire:click="$emit('compra.confirmDeleteCuota', {{ $item }})"
                                            wire:loading.attr="disabled" />
                                    </div>
                                @endif
                            </x-slot>

                        </x-card-cuota>
                    @endforeach
                </div>

                @if ($compra->cuotas()->whereHas('cajamovimiento')->count() < $compra->cuotas->count())
                    <div class="w-full flex justify-end">
                        <x-button wire:click="editcuotas" wire:loading.attr="disabled" wire:key="editcuotas">
                            EDITAR CUOTAS</x-button>
                    </div>
                @endif
            </div>
        @else
            <div class="w-full flex flex-wrap xl:flex-nowrap gap-2">
                <form wire:submit.prevent="calcularcuotas"
                    class="w-full xl:w-1/3 relative flex flex-col gap-2 bg-body p-3 rounded">
                    <div class="w-full">
                        <x-label value="Cuotas :" />
                        <x-input class="block w-full" type="number" min="1" step="1" max="10"
                            wire:model.defer="countcuotas" />
                    </div>
                    <x-jet-input-error for="countcuotas" />

                    <div class="w-full flex justify-end mt-3">
                        <x-button type="submit" wire:loading.attr="disabled">
                            CALCULAR
                        </x-button>
                    </div>
                </form>

                <div class="w-full xl:w-2/3">
                    @if (count($cuotas))
                        <div class="w-full flex flex-wrap gap-1">
                            @foreach ($cuotas as $item)
                                <x-card-cuota :titulo="substr('000' . $item['cuota'], -3)" class="w-full sm:w-48">

                                    <x-label value="Fecha pago :" textSize="[10px]" />
                                    <x-input class="block w-full" type="date"
                                        wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.date" />

                                    <x-label value="Monto Cuota :" textSize="[10px]" />
                                    <x-input class="block w-full numeric" type="number" min="1" step="0.001"
                                        wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.amount" />

                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.cuota" />
                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.date" />
                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.amount" />
                                </x-card-cuota>
                            @endforeach
                        </div>
                        <x-jet-input-error for="cuotas" />
                        <x-jet-input-error for="amountcuotas" />

                        <div class="w-full flex pt-4 justify-end">
                            <x-button wire:click="savecuotas" wire:loading.attr="disabled">
                                {{ __('REGISTRAR') }}
                            </x-button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div wire:loading.flex class="loading-overlay rounded hidden">
            <x-loading-next />
        </div>
    </x-form-card>

    <x-jet-dialog-modal wire:model="openpaycuota" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago cuota compra') }}
            <x-button-close-modal wire:click="$toggle('openpaycuota')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="w-full flex flex-col gap-1">
                <div class="w-full">
                    <p class="text-colorminicard text-xl font-semibold">
                        <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                        {{ number_format($cuota->amount, 2, '.', ', ') }}
                        <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                    </p>
                </div>

                <div class="w-full">
                    <x-label value="Método pago :" />
                    <div class="relative" x-data="{ methodpayment_id: @entangle('methodpayment_id').defer }" x-init="select2Methodpayment" id="parentqwerty" wire:ignore>
                        <x-select class="block w-full" x-ref="select" id="qwerty" data-dropdown-parent="null">
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
                    <x-label value="Otros (N° operación , Banco, etc) :" />
                    <x-input class="block w-full" wire:model.defer="detalle" />
                    <x-jet-input-error for="detalle" />
                    <x-jet-input-error for="opencaja.id" />
                </div>

                @if ($errors->any())
                    <div class="mt-2">
                        @foreach ($errors->keys() as $key)
                            <x-jet-input-error :for="$key" />
                        @endforeach
                    </div>
                @endif

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="savepayment">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="opencuotas" maxWidth="2xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Editar cuotas compra') }}
            <x-button-close-modal wire:click="$toggle('opencuotas')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <div class="w-full flex flex-col gap-3 relative">
                <form wire:submit.prevent="updatecuotas" class="w-full flex flex-wrap justify-around gap-2">
                    @if (count($cuotas))
                        <div class="w-full flex flex-wrap gap-1">
                            @foreach ($cuotas as $item)
                                <x-card-cuota :titulo="substr('000' . $item['cuota'], -3)" class="w-full sm:w-60">
                                    @if (!is_null($item['cajamovimiento_id']))
                                        <x-icon-default class="absolute top-2 right-2" />
                                        <p class="text-colorminicard text-xl font-semibold text-center">
                                            <small
                                                class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                                            {{ number_format($item['amount'], 2, '.', ', ') }}
                                            <small
                                                class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                                        </p>
                                    @endif

                                    <x-label value="Fecha pago :" class="mt-5" />
                                    @if (is_null($item['cajamovimiento_id']))
                                        <x-input class="block w-full" type="date"
                                            wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.date" />
                                    @else
                                        <x-disabled-text :text="\Carbon\Carbon::parse($item['date'])->format('d/m/Y')" />
                                    @endif


                                    @if (is_null($item['cajamovimiento_id']))
                                        <x-label value="Monto Cuota :" />
                                        <x-input class="block w-full numeric" type="number" min="1"
                                            step="0.0001"
                                            wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.amount"
                                            onkeydown="return numeric(event)" oninput="return notsimbols(event)" />
                                    @endif

                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.cuota" />
                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.date" />
                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.amount" />

                                </x-card-cuota>
                            @endforeach
                        </div>
                        <x-jet-input-error for="cuotas" />
                        <x-jet-input-error for="amountcuotas" />

                        <div class="w-full mt-3 gap-2 flex items-center justify-center">
                            <x-button wire:click="addnewcuota" wire:loading.attr="disabled"
                                wire:target="addnewcuota">
                                AGREGAR NUEVA CUOTA
                            </x-button>
                            <x-button type="submit" wire:loading.attr="disable" wire:target="updatecuotas">
                                CONFIRMAR CUOTAS</x-button>
                        </div>
                    @endif
                </form>

                <div wire:loading.flex class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>
            </div>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        // function deletecuota(data) {
        //     console.log(data);
        //     // const cuotastr = '000' + data.cuota;
        //     swal.fire({
        //         title: 'Desea eliminar cuota ?',
        //         text: "Se eliminará un registro de pago de la base de datos.",
        //         icon: 'question',
        //         showCancelButton: true,
        //         confirmButtonColor: '#0FB9B9',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Confirmar',
        //         cancelButtonText: 'Cancelar'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // console.log('Delete');
        //             @this.deletecuota(data);
        //         }
        //     })
        // }

        function select2Methodpayment() {
            this.selectS = $(this.$refs.select).select2();
            this.selectS.val(this.methodpayment_id).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectS.val(value).trigger("change");
            });
        }

        document.addEventListener("livewire:load", () => {

            // renderSelect2();

            // $('#editproveedorcompra_id').on("change", function(e) {
            //     disabledSelect();
            //     @this.set('compra.proveedor_id', e.target.value);
            // });

            // $('#editcompramethodpayment_id').on("change", function(e) {
            //     disabledSelect();
            //     @this.set('methodpayment_id', e.target.value);
            // });

            // $('#editcompracuenta_id').on("change", function(e) {
            //     disabledSelect();
            //     @this.set('cuenta_id', e.target.value);
            // });

            // document.addEventListener('render-select2-editcompra', () => {
            //     renderSelect2();
            // });

            Livewire.on('compra.confirmDeletePay', data => {
                const cuotastr = '000' + data.cuota;
                swal.fire({
                    title: 'Desea anular el pago de la Cuota' + cuotastr.substr(-3) + '?',
                    text: "Se eliminará un registro de pago de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deletepaycuota(data.id);
                    }
                })
            });


            Livewire.on('compra.confirmDeleteCuota', data => {
                const cuotastr = '000' + data.cuota;
                swal.fire({
                    title: 'Desea eliminar la Cuota' + cuotastr.substr(-3) + '?',
                    text: "Se eliminará un registro de pago de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log('Delete');
                        @this.deletecuota(data.id);
                    }
                })
            });

            // function renderSelect2() {
            //     $('#editproveedorcompra_id,#editmonedacompra_id,#editcompramethodpayment_id, #editcompracuenta_id')
            //         .select2()
            //         .on('select2:open', function(e) {
            //             const evt = "scroll.select2";
            //             $(e.target).parents().off(evt);
            //             $(window).off(evt);
            //         });

            //     $('#editcompracuenta_id').on("change", function(e) {
            //         disabledSelect();
            //         @this.set('cuenta_id', e.target.value);
            //     });
            // }

            // function disabledSelect() {
            //     $('#editproveedorcompra_id,#editmonedacompra_id,#editcompramethodpayment_id, #editcompracuenta_id')
            //         .attr("disabled", true);
            // }

        })
    </script>
</div>

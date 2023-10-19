<div class="w-full flex flex-col gap-8" wire:key="show-compra{{ $compra->id }}">

    <x-form-card titulo="DATOS COMPRA" widthBefore="before:w-24" subtitulo="Resumen de los datos princiales de la compra."
        x-data="{ updatingcompra: false }">

        <form wire:submit.prevent="update" class="w-full flex flex-col gap-2 bg-body p-3 rounded">
            <div class="w-full flex flex-col xs:grid xs:grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full xs:col-span-2">
                    <x-label value="Proveedor :" />
                    <div id="parenteditproveedorcompra_id">
                        <x-select class="block w-full" id="editproveedorcompra_id"
                            wire:model.defer="compra.proveedor_id" data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($proveedores))
                                    @foreach ($proveedores as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->document }} - {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                    </div>
                    <x-jet-input-error for="compra.proveedor_id" />
                </div>

                <div class="w-full">
                    <x-label value="Moneda :" />
                    <x-disabled-text :text="$compra->moneda->currency" />
                </div>

                <div class="w-full">
                    <x-label value="Fecha compra :" />
                    <x-input type="date" class="block w-full" wire:model.defer="compra.date" />
                    <x-jet-input-error for="compra.date" />
                </div>

                <div class="w-full">
                    <x-label value="Boleta/ Factura compra :" />
                    <x-input class="block w-full" wire:model.defer="compra.referencia"
                        placeholder="Boleta o factura de compra..." />
                    <x-jet-input-error for="compra.referencia" />
                </div>

                <div class="w-full">
                    <x-label value="Guía de compra :" />
                    <x-input class="block w-full" wire:model.defer="compra.guia" placeholder="Guia de compra..." />
                    <x-jet-input-error for="compra.guia" />
                </div>

                @if ($moneda->code == 'USD')
                    <div class="w-full">
                        <x-label value="Tipo Cambio :" />
                        <x-input class="block w-full numeric" wire:model.defer="compra.tipocambio" placeholder="0.00"
                            type="number" min="0" step="0.01" />
                        <x-jet-input-error for="compra.tipocambio" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Total exonerado :" />
                    <x-input class="block w-full numeric" wire:model.lazy="compra.exonerado" placeholder="0.00"
                        type="number" min="0" step="0.0001" />
                    <x-jet-input-error for="compra.exonerado" />
                </div>

                <div class="w-full">
                    <x-label value="Total gravado :" />
                    <x-input class="block w-full numeric" wire:model.lazy="compra.gravado" placeholder="0.00"
                        type="number" min="0" step="0.0001" />
                    <x-jet-input-error for="compra.gravado" />
                </div>

                <div class="w-full">
                    <x-label value="Total IGV :" />
                    <x-input class="block w-full numeric" wire:model.lazy="compra.igv" placeholder="0.00" type="number"
                        min="0" step="0.01" />
                    <x-jet-input-error for="compra.igv" />
                </div>

                <div class="w-full">
                    <x-label value="Total otros :" />
                    <x-input class="block w-full numeric" wire:model.lazy="compra.otros" placeholder="0.00"
                        type="number" min="0" step="0.01" />
                    <x-jet-input-error for="compra.otros" />
                </div>

                <div class="w-full">
                    <x-label value="Total compra :" />
                    <x-disabled-text :text="number_format($compra->total, 4, '.', ', ')" />
                    <x-jet-input-error for="compra.total" />
                </div>

                <div class="w-full">
                    <x-label value="Tipo pago :" />
                    <x-disabled-text :text="$compra->typepayment->name" />
                </div>

                @if (!$typepayment->paycuotas)
                    <div class="w-full">
                        <x-label value="Forma pago :" />
                        <x-disabled-text :text="$compra->cajamovimiento->methodpayment->name" />
                    </div>
                    @if (count($cuentas) > 1)
                        <div class="w-full">
                            <x-label value="Cuenta pago :" />
                            <div id="parentcuentacompra_id">
                                <x-select class="block w-full" wire:model.defer="cuenta_id" id="cuentacompra_id">
                                    <x-slot name="options">
                                        @foreach ($cuentas as $item)
                                            <option value="{{ $item->id }}">{{ $item->account }} -
                                                {{ $item->descripcion }}</option>
                                        @endforeach
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="cuenta_id" />
                        </div>
                    @endif
                @endif

                <div class="w-full xs:col-span-2">
                    <x-label value="Descripción compra, detalle :" />
                    <x-input class="block w-full" wire:model.defer="compra.detalle"
                        placeholder="Descripción de compra..." />
                    <x-jet-input-error for="compra.detalle" />
                </div>

                <div class="w-full">
                    <x-label value="Conteo productos :" />
                    <x-disabled-text :text="number_format($compra->counter, 2, '.', '')" />
                    <x-jet-input-error for="compra.counter" />
                </div>
            </div>

            <div class="w-full flex gap-2 pt-4 justify-end">
                <x-button-secondary wire:click="$emit('compra.comfirmDelete')" wire:loading.attr="disabled"
                    wire:target="delete">
                    {{ __('ELIMINAR') }}
                </x-button-secondary>

                <x-button type="submit" wire:loading.attr="disabled" wire:target="update">
                    {{ __('ACTUALIZAR') }}
                </x-button>
            </div>
        </form>

        <div x-show="updatingcompra" wire:loading wire:loading.flex wire:target="update, refresh, delete"
            class="loading-overlay rounded">
            <x-loading-next />
        </div>
    </x-form-card>

    @if ($compra->typepayment)
        @if ($compra->typepayment->paycuotas)
            <x-form-card titulo="CUOTAS PAGO" widthBefore="before:w-20"
                subtitulo="Información de cuotas de pago de la compra." x-data="{ loadingcuotas: false }">

                @if (count($compra->cuotas))
                    <div class="w-full flex flex-col gap-2">
                        <div class="w-full flex gap-2 flex-wrap justify-start">
                            @foreach ($compra->cuotas as $item)
                                <x-card-cuota class="w-full xs:w-48" :titulo="substr('000' . $item->cuota, -3)" :detallepago="$item->cajamovimiento">
                                    <p class="text-colorminicard text-[10px] mt-2">
                                        MONTO :
                                        {{ $compra->moneda->simbolo }}
                                        {{ number_format($item->amount, 4, '.', ', ') }}
                                        {{ $compra->moneda->currency }}
                                    </p>

                                    {{-- @if ($compra->moneda->code == 'USD')
                                        <p class="text-colorminicard text-[10px]">
                                            MONTO SOLES : S/.
                                            {{ number_format($item->amount * $compra->tipocambio, 4, '.', ', ') }}
                                        </p>
                                    @endif --}}

                                    <p class="text-colorminicard text-[10px] uppercase">
                                        Fecha pago :
                                        {{ \Carbon\Carbon::parse($item->expiredate)->locale('es')->isoformat('DD MMMM Y') }}
                                    </p>

                                    <x-slot name="footer">
                                        @if ($item->cajamovimiento)
                                            <div class="w-full flex gap-2 flex-wrap items-end justify-between">
                                                <x-mini-button>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path
                                                            d="M17.571 18H20.4a.6.6 0 00.6-.6V11a4 4 0 00-4-4H7a4 4 0 00-4 4v6.4a.6.6 0 00.6.6h2.829M8 7V3.6a.6.6 0 01.6-.6h6.8a.6.6 0 01.6.6V7" />
                                                        <path
                                                            d="M6.098 20.315L6.428 18l.498-3.485A.6.6 0 017.52 14h8.96a.6.6 0 01.594.515L17.57 18l.331 2.315a.6.6 0 01-.594.685H6.692a.6.6 0 01-.594-.685z" />
                                                        <path d="M17 10.01l.01-.011" />
                                                    </svg>
                                                </x-mini-button>
                                                <x-button-delete
                                                    wire:click="$emit('compra.confirmDeletePay', {{ $item }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="compra.confirmDeletePay"></x-button-delete>
                                            </div>
                                        @else
                                            <div class="w-full flex gap-2 flex-wrap items-end justify-between">
                                                <x-button wire:click="pay({{ $item->id }})"
                                                    wire:key="pay{{ $item->id }}" wire:loading.attr="disabled"
                                                    wire:target="pay">PAGAR</x-button>
                                                <x-button-delete
                                                    wire:click="$emit('compra.confirmDeleteCuota', {{ $item }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="compra.deletecuota"></x-button-delete>
                                            </div>
                                        @endif
                                    </x-slot>

                                </x-card-cuota>
                            @endforeach
                        </div>
                        <div class="w-full">
                            <x-button wire:click="editcuotas" wire:loading.attr="disabled"
                                wire:target="editcuotas">EDITAR CUOTAS</x-button>
                        </div>
                    </div>
                @else
                    <div class="w-full flex flex-wrap xl:flex-nowrap gap-2">
                        <form wire:submit.prevent="calcularcuotas"
                            class="w-full xl:w-1/3 relative flex flex-col gap-2 bg-body p-3 rounded">
                            <div class="w-full">
                                <x-label value="Cuotas :" />
                                <x-input class="block w-full" type="number" min="1" step="1"
                                    max="10" wire:model.defer="countcuotas" />
                            </div>
                            <x-jet-input-error for="countcuotas" />

                            <div class="w-full flex justify-end mt-3">
                                <x-button type="submit" wire:loading.attr="disabled" wire:target="calcularcuotas">
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
                                                wire:model="cuotas.{{ $loop->iteration - 1 }}.date" />

                                            <x-label value="Monto Cuota :" textSize="[10px]" />
                                            <x-input class="block w-full numeric" type="number" min="1"
                                                step="0.0001"
                                                wire:model="cuotas.{{ $loop->iteration - 1 }}.amount" />

                                            <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.cuota" />
                                            <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.date" />
                                            <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.amount" />
                                        </x-card-cuota>
                                    @endforeach
                                </div>
                                <x-jet-input-error for="cuotas" />
                                <x-jet-input-error for="amountcuotas" />

                                <div class="w-full flex pt-4 gap-2 justify-end">
                                    <x-button wire:click="savecuotas" wire:loading.attr="disabled"
                                        wire:target="savecuotas">
                                        {{ __('REGISTRAR') }}
                                    </x-button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div x-show="loadingcuotas" wire:loading wire:loading.flex
                    wire:target="calcularcuotas, deletecuota, deletepaycuota, savepayment, savecuotas"
                    class="loading-overlay rounded">
                    <x-loading-next />
                </div>
            </x-form-card>
        @endif
    @endif

    <x-jet-dialog-modal wire:model="openpaycuota" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago cuota compra') }}
            <x-button-add wire:click="$toggle('openpaycuota')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="w-full flex flex-col gap-1">
                <div class="w-full">
                    <x-label value="N° Cuota / Monto:" textSize="xs" />
                    <p class="inline-block text-[10px] font-semibold bg-gray-300 text-gray-700 rounded-lg p-1">
                        Cuota{{ substr('000' . $cuota->cuota, -3) }} /
                        {{ $compra->moneda->simbolo }}
                        {{ number_format($cuota->amount, 3, '.', ', ') }}
                        {{ $compra->moneda->currency }}
                    </p>
                </div>

                <div class="w-full">
                    <x-label value="Método pago :" textSize="xs" />
                    <x-select class="block w-full" id="editcompramethodpayment_id"
                        wire:model.defer="methodpayment_id" data-dropdown-parent="">
                        <x-slot name="options">
                            @if (count($methodpayments))
                                @foreach ($methodpayments as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="methodpayment_id" />
                </div>

                @if (count($cuentas) > 1)
                    <div class="w-full">
                        <x-label value="Cuenta pago :" />
                        <x-select class="block w-full" id="editcompracuenta_id" wire:model.defer="cuenta_id"
                            data-dropdown-parent="">
                            <x-slot name="options">
                                @if (count($cuentas))
                                    @foreach ($cuentas as $item)
                                        <option value="{{ $item->id }}">{{ $item->account }}
                                            ({{ $item->descripcion }})
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="cuenta_id" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Otros (N° operación , Banco, etc) :" textSize="xs" />
                    <x-input class="block w-full" wire:model.defer="detalle" />
                    <x-jet-input-error for="detalle" />
                </div>

                @if ($errors->any())
                    <div class="mt-2">
                        @foreach ($errors->keys() as $key)
                            <x-jet-input-error :for="$key" />
                        @endforeach
                    </div>
                @endif

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
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
            <x-button-add wire:click="$toggle('opencuotas')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <div class="w-full flex flex-col gap-3 relative" x-data="{ updatingcuotas: false }">
                <form wire:submit.prevent="updatecuotas" class="w-full flex flex-wrap justify-around gap-2">
                    @if (count($cuotas))
                        <div class="w-full flex flex-wrap gap-1">
                            @foreach ($cuotas as $item)
                                <x-card-cuota :titulo="substr('000' . $item['cuota'], -3)"
                                    class="w-full sm:w-48 bg-white border shadow shadow-gray-300 hover:shadow-gray-300">
                                    @if (!is_null($item['cajamovimiento_id']))
                                        <span
                                            class="absolute right-1 top-1 w-5 h-5 block rounded-full p-1 bg-green-100 text-next-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M20.9953 6.96425C21.387 6.57492 21.3889 5.94176 20.9996 5.55005C20.6102 5.15834 19.9771 5.15642 19.5854 5.54575L8.97661 16.0903L4.41377 11.5573C4.02196 11.1681 3.3888 11.1702 2.99956 11.562C2.61032 11.9538 2.6124 12.5869 3.0042 12.9762L8.27201 18.2095C8.66206 18.597 9.29179 18.5969 9.68175 18.2093L20.9953 6.96425Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                    @endif

                                    <x-label value="Fecha pago :" textSize="[10px]" />
                                    @if (is_null($item['cajamovimiento_id']))
                                        <x-input class="block w-full" type="date"
                                            wire:model.lazy="cuotas.{{ $loop->iteration - 1 }}.date" />
                                    @else
                                        <x-disabled-text :text="\Carbon\Carbon::parse($item['date'])->format('d/m/Y')" />
                                    @endif


                                    <x-label value="Monto Cuota :" textSize="[10px]" />
                                    @if (is_null($item['cajamovimiento_id']))
                                        <x-input class="block w-full numeric" type="number" min="1"
                                            step="0.0001" wire:model.lazy="cuotas.{{ $loop->iteration - 1 }}.amount"
                                            onkeydown="return numeric(event)" oninput="return notsimbols(event)" />
                                    @else
                                        <x-disabled-text :text="$item['amount']" />
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

                <div x-show="updatingcuotas" wire:loading wire:loading.flex
                    wire:target="editcuotas, updatecuotas, addnewcuota" class="loading-overlay rounded">
                    <x-loading-next />
                </div>
            </div>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener("livewire:load", () => {

            renderSelect2();

            $('#editproveedorcompra_id').on("change", function(e) {
                disabledSelect();
                @this.set('compra.proveedor_id', e.target.value);
            });

            $('#editcompramethodpayment_id').on("change", function(e) {
                disabledSelect();
                @this.set('methodpayment_id', e.target.value);
            });

            $('#editcompracuenta_id').on("change", function(e) {
                disabledSelect();
                @this.set('cuenta_id', e.target.value);
            });

            document.addEventListener('render-select2-editcompra', () => {
                renderSelect2();
            });


            Livewire.on('compra.comfirmDelete', () => {
                swal.fire({
                    title: 'Desea eliminar el registro de la compra ?',
                    text: "Se eliminará un registro de pago de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete();
                        // Livewire.emitTo('almacen::compras.show-compra', 'delete');
                    }
                })
            });

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
                        Livewire.emitTo('almacen::compras.show-compra', 'deletepaycuota', data.id);
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
                        Livewire.emitTo('almacen::compras.show-compra', 'deletecuota', data.id);
                    }
                })
            });

            function renderSelect2() {
                $('#editproveedorcompra_id,#editmonedacompra_id,#editcompramethodpayment_id, #editcompracuenta_id')
                    .select2()
                    .on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });

                $('#editcompracuenta_id').on("change", function(e) {
                    disabledSelect();
                    @this.set('cuenta_id', e.target.value);
                });
            }

            function disabledSelect() {
                $('#editproveedorcompra_id,#editmonedacompra_id,#editcompramethodpayment_id, #editcompracuenta_id')
                    .attr("disabled", true);
            }

        })
    </script>

</div>

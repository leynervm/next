<div>
    <x-form-card titulo="REGISTRAR COMPRA" widthBefore="before:w-28"
        subtitulo="Complete todos los campos para registrar una nueva compra." x-data="{ searchingclient: false }">
        <form wire:submit.prevent="save" class="w-full flex flex-col gap-2 bg-body p-3 rounded">
            <div class="w-full flex flex-col xs:grid xs:grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full xs:col-span-2">
                    <x-label value="Proveedor :" />
                    <div id="parentproveedorcompra_id">
                        <x-select class="block w-full select2" wire:model.defer="proveedor_id" id="proveedorcompra_id"
                            data-minimum-results-for-search="3">
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
                    <x-jet-input-error for="proveedor_id" />
                </div>

                <div class="w-full">
                    <x-label value="Moneda :" />
                    <div id="parentmonedacompra_id">
                        <x-select class="block w-full select2" wire:model.defer="moneda_id" id="monedacompra_id">
                            <x-slot name="options">
                                @if (count($monedas))
                                    @foreach ($monedas as $item)
                                        <option value="{{ $item->id }}">{{ $item->currency }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                    </div>
                    <x-jet-input-error for="moneda_id" />
                </div>

                <div class="w-full">
                    <x-label value="Fecha compra :" />
                    <x-input type="date" class="block w-full" wire:model.defer="date" />
                    <x-jet-input-error for="date" />
                </div>

                <div class="w-full">
                    <x-label value="Boleta/ Factura compra :" />
                    <x-input class="block w-full" wire:model.defer="referencia"
                        placeholder="Boleta o factura de compra..." />
                    <x-jet-input-error for="referencia" />
                </div>

                <div class="w-full">
                    <x-label value="Guía de compra :" />
                    <x-input class="block w-full" wire:model.defer="guia" placeholder="Guia de compra..." />
                    <x-jet-input-error for="guia" />
                </div>

                @if ($moneda->code == 'USD')
                    <div class="w-full">
                        <x-label value="Tipo Cambio :" />
                        <x-input class="block w-full numeric" wire:model.defer="tipocambio" placeholder="0.00"
                            type="number" min="0" step="0.0001" />
                        <x-jet-input-error for="tipocambio" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Total exonerado :" />
                    <x-input class="block w-full numeric" wire:model.lazy="exonerado" placeholder="0.00" type="number"
                        min="0" step="0.0001" />
                    <x-jet-input-error for="exonerado" />
                </div>

                <div class="w-full">
                    <x-label value="Total gravado :" />
                    <x-input class="block w-full numeric" wire:model.lazy="gravado" placeholder="0.00" type="number"
                        min="0" step="0.0001" />
                    <x-jet-input-error for="gravado" />
                </div>

                <div class="w-full">
                    <x-label value="Total IGV :" />
                    <x-input class="block w-full numeric" wire:model.lazy="igv" placeholder="0.00" type="number"
                        min="0" step="0.0001" />
                    <x-jet-input-error for="igv" />
                </div>

                <div class="w-full">
                    <x-label value="Total otros :" />
                    <x-input class="block w-full numeric" wire:model.lazy="otros" placeholder="0.00" type="number"
                        min="0" step="0.0001" />
                    <x-jet-input-error for="otros" />
                </div>

                <div class="w-full">
                    <x-label value="Total compra :" />
                    <x-disabled-text :text="$total" />
                    <x-jet-input-error for="total" />
                </div>

                <div class="w-full">
                    <x-label value="Tipo pago :" />
                    <div id="parenttypepaymentcompra_id">
                        <x-select class="block w-full select2" wire:model.lazy="typepayment_id"
                            id="typepaymentcompra_id">
                            <x-slot name="options">
                                @if (count($typepayments))
                                    @foreach ($typepayments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                    </div>
                    <x-jet-input-error for="typepayment_id" />
                </div>

                @if (!$typepayment->paycuotas)
                    <div class="w-full">
                        <x-label value="Forma pago :" />
                        <div id="parentmethodpaymentcompra_id">
                            <x-select class="block w-full select2" wire:model.lazy="methodpayment_id"
                                id="methodpaymentcompra_id">
                                <x-slot name="options">
                                    @if (count($methodpayments))
                                        @foreach ($methodpayments as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                        </div>
                        <x-jet-input-error for="methodpayment_id" />
                    </div>
                    @if (count($cuentas) > 1)
                        <div class="w-full">
                            <x-label value="Cuenta pago :" />
                            <div id="parentcuentacompra_id">
                                <x-select class="block w-full select2" wire:model.defer="cuenta_id"
                                    id="cuentacompra_id">
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
                    <x-input class="block w-full" wire:model.defer="detalle"
                        placeholder="Descripción de compra..." />
                    <x-jet-input-error for="detalle" />
                </div>
            </div>

            <x-jet-input-error for="opencaja" />

            <div class="w-full flex pt-4 justify-end">
                <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                    {{ __('REGISTRAR') }}
                </x-button>
            </div>
        </form>

        <div x-show="searchingclient" wire:loading wire:loading.flex wire:target="save"
            class="loading-overlay rounded">
            <x-loading-next />
        </div>
    </x-form-card>

    <hr>
    <x-jet-input-error for="cuenta_id" />

    @section('scripts')
        <script>
            renderselect2();

            $('#proveedorcompra_id').on("change", function(e) {
                $('.select2').attr("disabled", true);
                @this.proveedor_id = e.target.value;
            });

            $('#monedacompra_id').on("change", function(e) {
                $('.select2').attr("disabled", true);
                @this.moneda_id = e.target.value;
            });

            $('#typepaymentcompra_id').on("change", function(e) {
                $('.select2').attr("disabled", true);
                @this.typepayment_id = e.target.value;
            });

            $('#methodpaymentcompra_id').on("change", function(e) {
                $('.select2').attr("disabled", true);
                @this.methodpayment_id = e.target.value;
            });

            $('#cuentacompra_id').on("change", function(e) {
                $('.select2').attr("disabled", true);
                @this.cuenta_id = e.target.value;
            });



            document.addEventListener('render-select2-compra', () => {
                renderselect2();
            });

            function renderselect2() {
                $('#proveedorcompra_id, #monedacompra_id, #typepaymentcompra_id, #methodpaymentcompra_id, #cuentacompra_id')
                    .select2()
                    .on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });

                $('#cuentacompra_id').on("change", function(e) {
                    $('.select2').attr("disabled", true);
                    @this.cuenta_id = e.target.value;
                });

                $('#methodpaymentcompra_id').on("change", function(e) {
                    $('.select2').attr("disabled", true);
                    @this.methodpayment_id = e.target.value;
                });

            }
        </script>
    @endsection

</div>

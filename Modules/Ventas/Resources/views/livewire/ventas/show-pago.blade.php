<div>
    <div class="w-full @if (count(auth()->user()->carshoop()->get())) block @else hidden @endif">
        <form wire:submit.prevent="save">
            <x-card-next titulo="Generar comprobante electrónico" class="mt-3 border border-next-500 bg-transparent">
                @if (count($typecomprobantes))
                    <div class="w-full flex item-center flex-wrap gap-1">
                        @foreach ($typecomprobantes as $item)
                            <x-input-radio for="typecomprobante_{{ $item->id }}" :text="$item->descripcion . ' [' . $item->serie . ']'">
                                <input wire:model.defer="typecomprobante_id" class="sr-only peer" type="radio"
                                    id="typecomprobante_{{ $item->id }}" name="typecomprobante"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                    <x-jet-input-error for="typecomprobante_id" />

                    <div class="w-full flex flex-wrap md:flex-nowrap gap-3 mt-2">
                        <div class="w-full lg:w-1/3">
                            <x-label value="Tipo pago :" />
                            <x-select class="block w-full" id="ventatypepayment_id" wire:model="typepayment_id"
                                data-minimum-results-for-search="Infinity" data-placeholder="Seleccionar...">
                                <x-slot name="options">
                                    @if (count($typepayments))
                                        @foreach ($typepayments as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-jet-input-error for="typepayment_id" />
                        </div>

                        @if ($typepayment)
                            @if ($typepayment->paycuotas)
                                <div class="w-full lg:w-1/3">
                                    <x-label value="Incrementar valor venta :" />
                                    <x-input class="block w-full" type="number" min="0" step="0.01"
                                        wire:model.defer="increment" />
                                    <x-jet-input-error for="increment" />
                                </div>
                                <div class="w-full lg:w-1/3">
                                    <x-label value="Cuotas :" />
                                    <div class="w-full inline-flex">
                                        <x-input class="block w-full" type="number" min="1" step="1"
                                            max="10" wire:keydown.enter="calcular_cuotas"
                                            wire:model.defer="countcuotas" />
                                        <x-button class="px-2 py-1.5" wire:click="calcular_cuotas"
                                            wire:loading.attr="disabled" wire:target="calcular_cuotas">
                                            CALCULAR
                                        </x-button>
                                    </div>
                                    <x-jet-input-error for="countcuotas" />
                                </div>
                            @endif
                        @endif
                    </div>
                    @if ($typepayment)
                        @if ($typepayment->paycuotas)
                            <div class="w-full flex flex-wrap gap-3 mt-2">
                                {{-- {{ print_r($cuotas) }} --}}
                                @if (count($cuotas))
                                    @foreach ($cuotas as $item)
                                        <div class="w-48 rounded p-1 border shadow-md hover:shadow-lg">
                                            <h1 class="text-xs font-semibold text-center">
                                                Cuota{{ substr('000' . $item['cuota'], -3) }}</h1>
                                            <x-label value="Fecha pago :" textSize="[10px]" />
                                            <x-input class="block w-full" type="date"
                                                wire:model="cuotas.{{ $loop->iteration - 1 }}.date" />
                                            <x-label value="Monto Cuota :" textSize="[10px]" />
                                            <x-input class="block w-full" type="number" min="1" step="0.01"
                                                wire:model="cuotas.{{ $loop->iteration - 1 }}.amount" />
                                            {{-- {{ $item['suma'] }} --}}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <x-jet-input-error for="cuotas" />
                        @endif
                    @endif
                @endif

            </x-card-next>

            <div class="w-full text-center mt-1" wire:loading wire:target="calcular_cuotas">
                <p
                    class="inline-block py-2 text-xs tracking-widest shadow-lg text-next-500 rounded-lg bg-white p-1 px-2">
                    Calculando cuotas...</p>
            </div>

            <div class="w-full  @if ($typepayment->paycuotas) hidden @else block @endif">
                <x-card-next titulo="Datos pago" class="mt-3 border border-next-500 bg-transparent">
                    <div
                        class="w-full grid grid-cols-1 @if (count($accounts) > 1) md:grid-cols-2 xl:grid-cols-4 @else lg:grid-cols-3 @endif gap-3">
                        <div class="w-full">
                            <x-label value="Seleccionar caja :" />
                            <div id="parentv8">
                                <x-select class="block w-full" id="ventacaja_id" wire:model.defer="caja_id"
                                    data-dropdown-parent="#parentv8" data-minimum-results-for-search="Infinity"
                                    data-placeholder="Seleccionar...">
                                    <x-slot name="options">
                                        @if (count($opencajas))
                                            @foreach ($opencajas as $item)
                                                <option value="{{ $item->id }}">{{ $item->caja->name }}
                                                    ({{ $item->user->name }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="caja_id" />
                        </div>
                        <div class="w-full">
                            <x-label value="Método pago :" />
                            <div id="parentv9">
                                <x-select class="block w-full" id="ventamethodpayment_id"
                                    wire:model.defer="methodpayment_id" data-dropdown-parent="#parentv9"
                                    data-minimum-results-for-search="Infinity" data-placeholder="Seleccionar...">
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


                        <div class="w-full @if (count($accounts) > 1) block @else hidden @endif">
                            <x-label value="Cuenta pago :" />
                            <div id="parentv10">
                                <x-select class="block w-full" id="ventacuenta_id" wire:model.defer="cuenta_id"
                                    data-dropdown-parent="#parentv10" data-minimum-results-for-search="Infinity"
                                    data-placeholder="Seleccionar...">
                                    <x-slot name="options">
                                        @if (count($accounts))
                                            @foreach ($accounts as $item)
                                                <option value="{{ $item->id }}">{{ $item->account }}
                                                    ({{ $item->descripcion }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="cuenta_id" />
                        </div>


                        <div class="w-full">
                            <x-label value="Detalle pago :" />
                            <x-input class="block w-full" wire:model.defer="detallepago" />
                            <x-jet-input-error for="detallepago" />
                        </div>
                    </div>
                </x-card-next>
            </div>

            <div class="mt-3 text-center">

                {{-- <x-jet-input-error for="countcuotas" />
                <x-jet-input-error for="cuotas" />
                <x-jet-input-error for="detallepago" />
                <x-jet-input-error for="increment" /> --}}

                <x-jet-input-error for="empresa_id" />
                <x-jet-input-error for="client_id" />
                <x-jet-input-error for="concept_id" />
                <x-jet-input-error for="typemovement_id" />
                <x-jet-input-error for="tribute_id" />
                <x-jet-input-error for="carrito" />
            </div>

            <div class="w-full flex flex-row mt-2 gap-2 justify-end text-right">
                <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                    wire:target="save">
                    {{ __('REGISTRAR') }}
                    @if (count(auth()->user()->carshoop()->get()))
                        <span
                            class="absolute -top-2 -left-2 block w-4 h-4 p-0.5 leading-3 bg-next-500 ring-1 ring-white rounded-full text-[10px] animate-bounce">
                            {{ count(auth()->user()->carshoop()->get()) }}</span>
                    @endif
                </x-button>
            </div>
        </form>
    </div>
</div>

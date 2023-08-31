<div class="">

    @if (count($ofertas))
        <div class="pb-2">
            {{ $ofertas->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">

        @if (count($ofertas))
            @foreach ($ofertas as $item)
                <div class="w-60 group rounded shadow p-1 text-xs relative hover:shadow-lg cursor-pointer">
                    <div
                        class="absolute top-1 left-1 w-10 h-10 group-hover:shadow group-hover:shadow-red-500 flex flex-col items-center justify-center rounded-full bg-red-500 text-white bg-opacity-80 group-hover:bg-opacity-100 transition-all ease-in-out duration-150">
                        <h1 class="font-semibold leading-3 text-[9px]">{{ $item->descuento }}%</h1>
                        <p class="leading-3 text-[7px]">DSCT</p>
                    </div>

                    @if ($item->disponible == 0)
                        <span
                            class="absolute top-12 left-1 text-[10px] font-semibold leading-3 p-1 rounded-r-lg bg-red-500 text-white bg-opacity-80 group-hover:bg-opacity-100 transition-all ease-in-out duration-150">
                            Agotado</span>
                    @endif

                    @if (count($item->producto->images))
                        <div class="w-full h-32 rounded shadow border">
                            @if ($item->producto->defaultImage)
                                <img src="{{ asset('storage/productos/' . $item->producto->defaultImage->first()->url) }}"
                                    alt="" class="w-full h-full object-scale-down">
                            @else
                                <img src="{{ asset('storage/productos/' . $item->producto->images->first()->url) }}"
                                    alt="" class="w-full h-full object-scale-down">
                            @endif
                        </div>
                    @endif

                    <h1 class="text-[10px] font-semibold leading-3 text-center mt-1">{{ $item->producto->name }}</h1>

                    {{-- <h1 class="mt-2">
                        <span class="text-[10px] font-semibold leading-3 p-1 text-red-500 rounded line-through">
                            S/. 50.00</span>
                    </h1>

                    <h1 class="text-sm font-semibold leading-3 text-green-500">
                        <span class="text-[10px]">OFERTA : </span> S/. {{ $item->descuento }}
                    </h1> --}}

                    <h1 class="mt-2">
                        <span class="text-[10px] font-semibold leading-3 p-1 text-green-500 bg-green-100 rounded">
                            {{ $item->almacen->name }}</span>
                        <span class="text-[10px] font-semibold leading-3 p-1 text-blue-500 bg-blue-100 rounded">
                            STOCK MÁXIMO :{{ $item->limit }}</span>
                    </h1>

                    <h1 class="mt-2">

                    </h1>

                    <h1 class="mt-2">
                        <span class="text-[10px] font-semibold leading-3 p-1 bg-fondospancardproduct rounded">
                            DISPONIBLES :{{ $item->disponible }}</span>
                    </h1>

                    <h1 class="mt-2">
                        <span class="text-[10px] font-semibold leading-3 p-1 bg-fondospancardproduct rounded">
                            PRECIO COMPRA : S/. {{ $item->producto->pricebuy }}</span>
                    </h1>


                    <h1 class="mt-2">
                        <span class="text-[10px] font-semibold leading-3 p-1 bg-fondospancardproduct rounded uppercase">
                            INICIO :
                            {{ \Carbon\Carbon::parse($item->datestart)->locale('es')->isoFormat('D MMMM YYYY h:mm:ss A') }}</span>
                    </h1>

                    <h1 class="mt-2">
                        <span class="text-[10px] font-semibold leading-3 p-1 bg-fondospancardproduct rounded uppercase">
                            FIN :
                            {{ \Carbon\Carbon::parse($item->dateexpire)->locale('es')->isoFormat('D MMMM YYYY h:mm:ss A') }}</span>
                    </h1>

                    <h1 class="mt-2">
                        @if ($item->status)
                            <span class="text-[10px] font-semibold leading-3 p-1 text-red-500 bg-red-100 rounded">
                                Finalizado</span>
                        @else
                            <span class="text-[10px] font-semibold leading-3 p-1 text-green-500 bg-green-100 rounded">
                                Oferta activa</span>
                        @endif
                    </h1>

                    <h1 class="mt-3 mb-1 underline text-[10px] font-semibold text-center">PRECIOS DE OFERTA</h1>
                    
                    @if (count($pricetypes))
                        @foreach ($pricetypes as $lista)
                            <div class="w-full bg-white rounded shadow-md p-1 mt-1">
                                @if (count($lista->rangos))
                                    @foreach ($lista->rangos as $rango)
                                        @if ($item->producto->pricebuy >= $rango->desde && $item->producto->pricebuy <= $rango->hasta)
                                            {{-- <p>{{ $rango->pivot->ganancia }} </p> --}}

                                            @php
                                                $pricesale = number_format($item->producto->pricebuy + ($item->producto->pricebuy * $rango->pivot->ganancia) / 100, $lista->decimalrounded, '.', ',');
                                            @endphp

                                            <div class="w-full flex gap-1 items-center justify-start">
                                                <span
                                                    class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct rounded uppercase">
                                                    {{ $lista->name }} ({{$rango->pivot->ganancia}} %)</span>
                                                <p
                                                    class="text-[10px] font-semibold leading-3 p-1 text-red-500 rounded line-through">
                                                    S/. {{ number_format($pricesale, $lista->decimalrounded) }}
                                                </p>
                                            </div>

                                            <h1 class="mt-1 text-xs font-semibold leading-3 text-green-500">
                                                <span class="text-[10px]">OFERTA : </span> S/.
                                                {{ number_format($pricesale - ($pricesale * $item->descuento) / 100, $lista->decimalrounded) }}
                                            </h1>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    @endif
                    

                    <div class="w-full flex gap-1 justify-end mt-3">
                        <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                            wire:click="edit({{ $item->id }})"></x-button-edit>
                        <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete"
                            wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar oferta') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="block w-full" id="form_create_oferta">
                @if ($oferta->producto)
                    <div class="w-60 mx-auto">
                        @if (count($oferta->producto->images))
                            <div class="w-full h-32 rounded shadow border">
                                @if ($oferta->producto->defaultImage)
                                    <img src="{{ asset('storage/productos/' . $oferta->producto->defaultImage->first()->url) }}"
                                        alt="" class="w-full h-full object-scale-down">
                                @else
                                    <img src="{{ asset('storage/productos/' . $oferta->producto->images->first()->url) }}"
                                        alt="" class="w-full h-full object-scale-down">
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <x-label value="Almacén :" />
                <x-input class="block w-full" value="{{ $oferta->almacen->name ?? '' }}" disabled readonly />
                <x-jet-input-error for="oferta.almacen_id" />

                <x-label value="Producto :" class="mt-2" />
                <x-input class="block w-full" value="{{ $oferta->producto->name ?? '' }}" disabled readonly />
                <x-jet-input-error for="oferta.producto_id" />

                <div class="flex flex-wrap md:flex-nowrap gap-2 mt-2">
                    <div class="w-full md:w-1/2">
                        <x-label value="Fecha inicio :" />
                        <x-input class="block w-full"
                            value="{{ \Carbon\Carbon::parse($oferta->datestart)->format('Y-m-d') ?? '' }}"
                            type="date" disabled readonly />
                        <x-jet-input-error for="oferta.datestart" />
                    </div>
                    <div class="w-full md:w-1/2">
                        <x-label value="Fecha finalización :" />
                        <x-input class="block w-full" wire:model.defer="oferta.dateexpire" type="date" />
                        <x-jet-input-error for="oferta.dateexpire" />
                    </div>
                </div>

                <div class="flex flex-wrap md:flex-nowrap gap-2 mt-2">
                    <div class="w-full md:w-1/2">
                        <x-label value="Descuento (%) :" />
                        <x-input class="block w-full" wire:model.defer="oferta.descuento" type="number" min="0"
                            step="0.1" />
                        <x-jet-input-error for="oferta.descuento" />
                    </div>
                    <div class="w-full md:w-1/2">
                        <x-label value="Vendidos :" />
                        <x-input class="block w-full" value="{{ $oferta->vendidos }}" type="number" disabled
                            readonly />
                    </div>
                </div>

                <div class="flex flex-wrap md:flex-nowrap gap-2 mt-2">
                    <div class="w-full md:w-1/2">
                        <x-label value="Máximo stock :" />
                        <x-input class="block w-full" wire:model.defer="oferta.limit" type="number" min="0"
                            step="1" :disabled="$max == 1 ? true : false" />
                        <x-jet-input-error for="oferta.limit" />
                    </div>
                    <div class="w-full md:w-1/2">
                        <x-label value="Stock disponible :" />
                        <x-input class="block w-full" value="{{ $oferta->disponible }}" type="number" disabled
                            readonly />
                    </div>
                </div>

                <div class="mt-3 mb-1">
                    <x-label textSize="[10px]"
                        class="inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                        for="edit_max">
                        <x-input wire:model="max" name="max" type="checkbox" id="edit_max" />
                        SELECCIONAR MÁXIMO DISPONIBLE
                    </x-label>
                </div>
                <x-jet-input-error for="max" />

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('livewire:load', function() {
            window.addEventListener('ofertas.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.detail.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(data.detail.id);
                        Livewire.emitTo('almacen::ofertas.show-ofertas', 'delete', data.detail.id);
                    }
                })
            })
        })
    </script>
</div>

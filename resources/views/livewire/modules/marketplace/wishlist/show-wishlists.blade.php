<div class="py-5" @updatecart.window ="(data)=> {
    $wire.render().then((result) => {})
    }">
    @if (count($wishlists) > 0)
        <div class="w-full md:max-w-3xl mx-auto flex flex-col gap-3 sm:gap-5">
            <div class="w-full">
                <div class="w-full flex flex-wrap gap-2 items-center justify-between py-3">
                    <h1 class="font-semibold text-lg sm:text-2xl text-primary">
                        Resumen de mis favoritos</h1>

                    <button wire:click="delete" wire:loading.attr="disabled"
                        class="btn-outline-secondary ring-2 inline-block hover:bg-red-700 hover:text-white hover:ring-red-300">
                        ELIMINAR TODO</button>
                </div>

                <div class="w-full flex flex-col gap-3">
                    @foreach ($wishlists as $item)
                        @php
                            $combo = getAmountCombo($item->options->promocion, $pricetype);
                            $image =
                                !is_null($item->model) && !empty($item->model->imagen)
                                    ? pathURLProductImage($item->model->imagen->url)
                                    : null;
                        @endphp

                        <div class="relative w-full flex flex-col gap-2 text-xs p-1 sm:p-2 border border-borderminicard shadow-md shadow-shadowminicard rounded-lg md:rounded-2xl"
                            wire:key="{{ $item->rowId }}">
                            <div
                                class="{{ $item->options->is_disponible ? '' : 'opacity-50' }} w-full flex flex-col xs:flex-row gap-2 text-xs">
                                <div class="w-full h-48 xs:size-32 sm:size-40 rounded overflow-hidden">
                                    @if ($image)
                                        <img src="{{ $image }}" alt=""
                                            class="block w-full h-full object-scale-down object-center overflow-hidden">
                                    @else
                                        <x-icon-file-upload class="w-full h-full" type="unknown" />
                                    @endif
                                </div>
                                <div class="w-ful flex-1">
                                    <p class="leading-tight text-xs text-colorlabel text-justify">
                                        {{ !is_null($item->model) ? $item->model->name : $item->name }}</p>
                                    @if ($combo)
                                        <div class="w-full flex items-center flex-wrap gap-1 sm:gap-3">
                                            @foreach ($combo->products as $itemcombo)
                                                <span class="block w-5 h-5 text-colorsubtitleform">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        color="currentColor" fill="none" stroke="currentColor"
                                                        stroke-width="2.5" stroke-linecap="round"
                                                        stroke-linejoin="round" class="w-full h-full block">
                                                        <path d="M12 4V20M20 12H4" />
                                                    </svg>
                                                </span>

                                                <a class="size-16 sm:size-20 block rounded-lg relative"
                                                    href="{{ route('productos.show', $itemcombo->producto_slug) }}">
                                                    @if ($itemcombo->image)
                                                        <img src="{{ $itemcombo->image }}" alt="{{ $itemcombo->image }}"
                                                            class="block w-full h-auto object-scale-down overflow-hidden rounded-lg">
                                                    @else
                                                        <x-icon-image-unknown
                                                            class="w-full h-full text-colorsubtitleform" />
                                                    @endif

                                                    @if ($itemcombo->price <= 0)
                                                        <x-span-text text="GRATIS" type="green"
                                                            class="text-nowrap absolute bottom-0 left-[50%] -translate-x-[50%] !text-[9px] py-0.5" />
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                        <p class="leading-tight text-xs text-colorlabel">
                                            {{ $item->options->promocion->titulo }}</p>
                                    @endif

                                    @if ($item->options->is_disponible)
                                        <h1
                                            class="text-colorlabel text-sm sm:text-lg md:text-xl font-semibold text-end !leading-none">
                                            @if ($item->options->promocion)
                                                <span
                                                    class="text-[10px] md:text-xs p-0.5 rounded text-colorerror font-medium line-through">
                                                    @if ($combo)
                                                        {{ number_format(getPrecioventa($item->model, $pricetype) + $combo->total_normal, 2, '.', ', ') }}
                                                    @else
                                                        {{ number_format(getPrecioventa($item->model, $pricetype), 2, '.', ', ') }}
                                                    @endif
                                                </span>
                                                <br>
                                            @endif

                                            <small
                                                class="text-[10px] font-medium">{{ $item->options->simbolo }}</small>
                                            {{ number_format($item->price, 2, '.', ', ') }}
                                        </h1>

                                        <h1
                                            class="text-colorlabel text-sm sm:text-lg md:text-xl font-semibold text-end !leading-tight">
                                            <small class="text-[10px] font-medium">TOTAL :
                                                {{ $item->options->simbolo }}</small>
                                            {{ number_format($item->price * $item->qty, 2, '.', ', ') }}
                                        </h1>
                                    @endif
                                </div>
                            </div>

                            @if ($item->options->is_disponible)
                                <div class="w-full flex flex-wrap gap-1 justify-end items-end">
                                    <x-button-add-car type="button" wire:loading.attr="disabled"
                                        class="!text-[10px] !flex !justify-center !gap-1 items-center font-semibold"
                                        wire:click="movetocarshoop('{{ $item->rowId }}')"
                                        wire:key="add_{{ $item->rowId }}">MOVER AL CARRITO</x-button-add-car>

                                    <button wire:click="deleteitem('{{ $item->rowId }}')" wire:loading.attr="disabled"
                                        class="btn-outline-secondary !p-2 !text-[10px] ring-0 border-2 border-red-500 hover:bg-red-700 hover:text-white hover:ring-red-300 font-semibold">
                                        ELIMINAR</button>

                                    {{-- <x-button-like class="activo hover:ring-2 hover:ring-red-400"
                                        wire:loading.attr="disabled" wire:click="deleteitem('{{ $item->rowId }}')" /> --}}
                                </div>
                            @else
                                <div
                                    class="absolute top-0 left-0 bg-body w-full h-full opacity-50 rounded-lg md:rounded-2xl">
                                </div>
                                <div
                                    class="absolute w-full h-full flex flex-col xs:flex-row flex-wrap justify-center gap-2 items-center top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%]">
                                    <span class="btn-outline-secondary bg-inherit inline-block text-center">
                                        {{ !empty($item->options->promocion_id) ? 'PROMOCIÓN NO DISPONIBLE' : 'PRODUCTO NO DISPONIBLE' }}</span>
                                    <x-button-like class="activo inline-block hover:ring-2 hover:ring-red-400"
                                        wire:loading.attr="disabled" wire:click="deleteitem('{{ $item->rowId }}')" />
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <h1 class="text-xs p-2 font-medium text-colorsubtitleform">
            NO EXISTEN PRODUCTOS AGREGADOS EN TUS FAVORITOS...</h1>
    @endif

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.sent', () => {
                $(componentloading).fadeIn();
            });
            Livewire.hook('message.processed', () => {
                $(componentloading).fadeOut();
            });
        })
    </script>
</div>

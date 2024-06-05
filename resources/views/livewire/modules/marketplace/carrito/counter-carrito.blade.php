<div class="" x-data="{ opencounter: @entangle('opencounter') }">
    <button wire:click="open"
        class="hidden xl:flex w-full hover:text-neutral-200 h-full justify-start items-center text-white">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
            stroke-linecap="round" stroke-linejoin="round" class="block w-8 h-8">
            <circle cx="8" cy="21" r="1" />
            <circle cx="19" cy="21" r="1" />
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
        </svg>
    </button>
    <a href="{{ route('carshoop') }}"
        class="flex xl:hidden w-full hover:text-neutral-200 h-full justify-start items-center text-white">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="block w-8 h-8">
            <circle cx="8" cy="21" r="1" />
            <circle cx="19" cy="21" r="1" />
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
        </svg>
    </a>
    <span id="countercart"
        class="{{ Cart::instance('shopping')->count() == 0 ? 'hidden' : 'flex' }} absolute w-4 h-4 top-0 right-1 xl:left-[40px] tracking-tight h-100 justify-center items-center leading-3 text-[9px] bg-white text-next-500 rounded-full">
        {{ Cart::instance('shopping')->count() }}
    </span>

    @if (Cart::instance('shopping')->count() > 0)
        <div x-show="opencounter" x-cloak style="display: none;" @click.outside="opencounter=false"
            class="hidden xl:block absolute z-[1000] w-96 right-0 translate-y-[100%] bg-fondodropdown rounded-xl bottom-0 shadow-md">
            <h1 class="text-xs p-2 border-b border-b-borderminicard font-semibold text-colorsubtitleform">
                CARRITO DE COMPRAS</h1>
            @foreach (Cart::instance('shopping')->content()->take(3) as $item)
                <div class="w-full border-b border-b-borderminicard flex gap-2 p-1 text-xs">
                    <div class="w-14 h-14 rounded overflow-hidden">
                        @if ($item->model->getImageURL())
                            <img src="{{ $item->model->getImageURL() }}" alt=""
                                class="w-full h-full object-scale-down" />
                        @else
                            <x-icon-file-upload class="!w-full !h-full !m-0" type="unknown" />
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="leading-3 text-xs text-colorlabel text-neutral-700">
                            {{ $item->model->name }}</p>
                        <div class="w-full flex justify-between items-end gap-2">
                            <h1 class="text-xs text-colorsubtitleform">
                                <small class="text-[10px]">CANT: </small>
                                {{ $item->qty }}
                                <small class="text-[10px]">{{ $item->model->unit->name }}</small>
                            </h1>
                            <h1 class="text-sm text-green-500">
                                <small class="text-[10px] text-colorsubtitleform">P. UNIT :
                                    {{ $item->options->simbolo }}</small>
                                {{ number_format($item->price, 2, '.', ', ') }}
                            </h1>
                        </div>

                        <div class="w-full flex justify-end items-center">
                            <x-button-delete wire:click="deleteitem('{{ $item->rowId }}')"
                                wire.loading.attr="disabled" />
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="w-full p-3 pt-3 flex items-center justify-center">
                <x-link-web href="{{ route('carshoop') }}" :text="__('MOSTRAR CARRITO')" />
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('updatecart', data => {
            let counter = document.getElementById('countercart');
            if (typeof data.detail === 'object') {
                counter.style.display = 'none';
            } else {
                counter.style.display = 'flex';
                counter.innerHTML = data.detail;
            }
        })
    </script>
</div>

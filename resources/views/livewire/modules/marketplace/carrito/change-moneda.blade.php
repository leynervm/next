<div x-data="datamoney" class="relative">
    @if (count($monedas) > 0)
        <button @click="view = !view"
            class="p-1.5 px-3 h-10 rounded-md text-[10px] font-semibold bg-transparent text-white @if (count($monedas) > 1) hover:text-neutral-300 @endif transition ease-in-out duration-150">
            {{ $moneda->currency }} {{ $moneda->simbolo }}
        </button>
        @if (count($monedas) > 1)
            <div class="absolute right-0 origin-top-right w-full max-w-[100px] z-20 bg-white flex flex-col justify-center items-center rounded shadow shadow-next-300"
                x-show="view" @click.outside="view=false" x-transition>
                @foreach ($monedas as $item)
                    <button
                        class="p-2 font-semibold whitespace-nowrap text-[10px] block w-full text-center rounded hover:bg-neutral-200 text-neutral-600"
                        @click="setMoneda({{ $item->id }})">
                        {{ $item->currency }} {{ $item->simbolo }}
                    </button>
                @endforeach
            </div>
        @endif
    @endif
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('datamoney', () => ({
                view: false,
                moneda_id: @entangle('moneda_id').defer,
                // moneda: @json($moneda),

                init() {
                    const moneda_id = localStorage.getItem('moneda_id') ?? null;
                    if (moneda_id == null || moneda_id == undefined) {
                        localStorage.setItem('moneda_id', this.moneda_id);
                        // localStorage.setItem('moneda', JSON.stringify(this.moneda));
                    } else {
                        this.setMoneda(moneda_id);
                    }
                },
                setMoneda(moneda_id) {
                    if (moneda_id != this.moneda_id) {
                        Livewire.emit('setMoneda', moneda_id);
                        // @this.setMoneda(moneda_id);
                        localStorage.setItem('moneda_id', moneda_id);
                    }

                    this.moneda_id = moneda_id;
                    this.view = false;
                }
            }))
        })
    </script>
</div>

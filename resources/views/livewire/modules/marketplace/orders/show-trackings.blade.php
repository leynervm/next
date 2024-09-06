<div>
    <h1 class="text-xl font-semibold text-colorsubtitleform">
        SEGUIMIENTO DEL PEDIDO</h1>

    {{-- <div class="w-full py-12 flex items-center">
        <div class="flex items-center relative p-3">
            <div class="rounded-full h-12 w-12 bg-next-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 block text-white">
                    <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                </svg>
            </div>
            <h1 class="absolute w-full text-[10px] leading-3 text-center top-[100%] left-1/2 -translate-x-1/2 mt-0.5">
                REGISTRADO
                <br>
                <span class="text-neutral-500">23 NOV. 2024</span>
            </h1>
        </div>

        <div class="h-0.5 flex-1 bg-next-500"></div>
        <div class="flex items-center relative p-3">
            <div class="rounded-full h-12 w-12 bg-next-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 block text-white">
                    <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                </svg>
            </div>
        </div>
    </div> --}}

    @if ($order->isPagoconfirmado())
        @can('admin.marketplace.trackings.create')
            @if (count($trackingstates) > 0)
                @if (!$order->trackings()->finalizados()->exists())
                    <div class="w-full py-5 sm:max-w-md shadow-xl rounded-xl p-3">
                        <form wire:submit.prevent="save" class="flex flex-col gap-2">
                            <div class="w-full">
                                <x-label for="trackingstate_id" value="Seleccionar estado :" />
                                <div class="relative" id="parenttrackingstate_id" x-data="{ trackingstate_id: @entangle('trackingstate_id').defer }"
                                    x-init="select2Tracking">
                                    <x-select class="block w-full" id="trackingstate_id" x-ref="select"
                                        x-model="trackingstate_id">
                                        <x-slot name="options">
                                            @foreach ($trackingstates as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </x-slot>
                                    </x-select>
                                    <x-icon-select />
                                </div>
                                <x-jet-input-error for="trackingstate_id" />
                            </div>

                            <div class="w-full flex pt-4 justify-end">
                                <x-button type="submit" wire:loading.attr="disabled">
                                    {{ __('Save') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                @endif
            @endif
        @endcan
    @endif


    @if (count($order->trackings) > 0)
        <div class="w-full sm:overflow-x-auto pt-6 pb-16 flex flex-col sm:flex-row divide-y sm:divide-y-0">
            @foreach ($order->trackings()->orderBy('id', 'asc')->get() as $item)
                <div class="relative sm:min-w-[130px] w-full sm:max-w-[200px] flex flex-col sm:flex-row items-center">
                    @if (!$loop->first)
                        <div
                            class="absolute h-full w-[1px] left-1.5 bottom-1/2  sm:w-full sm:-left-1/2 sm:right-1/2 sm:h-0.5 bg-next-500">
                        </div>
                    @endif
                    <div class="w-full flex gap-2 justify-center items-center relative py-3 sm:p-3">
                        <div class="flex-shrink rounded-full h-3 w-3 bg-next-500"></div>
                        <div
                            class="{{ $loop->first ? 'sm:mt-6' : '' }} flex-1 sm:absolute w-full flex flex-col justify-center sm:items-center text-[10px] sm:top-[90%] sm:left-1/2 sm:-translate-x-1/2">
                            @can('admin.marketplace.trackings.delete')
                                @if (!$item->trackingstate->isDefault())
                                    <x-button-delete
                                        class="absolute top-1/2 -translate-y-1/2 right-0 sm:relative sm:translate-y-0"
                                        wire:click="delete({{ $item->id }})" wire:loading.attr="disabled" />
                                @endif
                            @endcan

                            <p class="leading-3 sm:text-center">{{ $item->trackingstate->name }}</p>

                            <small
                                class="text-neutral-500 sm:text-center sm:text-[10px]">{{ formatDate($item->date, 'DD MMM Y') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif


    <script>
        function select2Tracking() {
            this.selectTR = $(this.$refs.select).select2();
            this.selectTR.val(this.trackingstate_id).trigger("change");
            this.selectTR.on("select2:select", (event) => {
                this.trackingstate_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("trackingstate_id", (value) => {
                this.selectTR.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectTR.select2().val(this.trackingstate_id).trigger('change');
            });
        }
    </script>
</div>

<div class="w-full  {{-- p-3 rounded-lg border border-borderminicard --}}">
    <h3 class="text-xl font-semibold text-colorsubtitleform">Tracking</h3>
    @can('admin.marketplace.trackings.create')
        @if ($order->isPagoconfirmado())
            @if (count($trackingstates) > 0)
                @if (!$order->trackings()->finalizados()->exists())
                    <form wire:submit.prevent="save" class="flex flex-col gap-2 py-5">
                        <div class="w-full">
                            <x-label for="trackingstate_id" value="Seleccionar estado :" />
                            <div class="relative" id="parenttrackingstate_id" x-data="{ trackingstate_id: @entangle('trackingstate_id').defer }" x-init="select2Tracking">
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

                        <div class="w-full flex justify-end">
                            <x-button type="submit" wire:loading.attr="disabled">
                                {{ __('Save') }}</x-button>
                        </div>
                    </form>
                @endif
            @endif
        @endif
    @endcan


    @if (count($order->trackings) > 0)
        <ol class="relative ms-3 border-s border-borderminicard">
            @foreach ($order->trackings()->orderBy('date', 'desc')->get() as $item)
                <li class="mb-10 ms-6 text-colorlabel">
                    <span
                        class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-next-500 ring-8 ring-body">
                        @if ($item->trackingstate->isFinalizado())
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21 7V12M3 7C3 10.0645 3 16.7742 3 17.1613C3 18.5438 4.94564 19.3657 8.83693 21.0095C10.4002 21.6698 11.1818 22 12 22L12 11.3548" />
                                <path d="M15 19C15 19 15.875 19 16.75 21C16.75 21 19.5294 16 22 15" />
                                <path
                                    d="M8.32592 9.69138L5.40472 8.27785C3.80157 7.5021 3 7.11423 3 6.5C3 5.88577 3.80157 5.4979 5.40472 4.72215L8.32592 3.30862C10.1288 2.43621 11.0303 2 12 2C12.9697 2 13.8712 2.4362 15.6741 3.30862L18.5953 4.72215C20.1984 5.4979 21 5.88577 21 6.5C21 7.11423 20.1984 7.5021 18.5953 8.27785L15.6741 9.69138C13.8712 10.5638 12.9697 11 12 11C11.0303 11 10.1288 10.5638 8.32592 9.69138Z" />
                                <path d="M6 12L8 13" />
                                <path d="M17 4L7 9" />
                            </svg>
                        @else
                            <svg class="h-4 w-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                            </svg>
                        @endif
                    </span>
                    <h4 class="mb-0.5 font-semibold text-sm text-primary">
                        {{ formatDate($item->date, 'DD MMM Y, hh:mm A') }}</h4>
                    <p class="text-xs text-colorsubtitleform">{{ $item->trackingstate->name }}</p>
                    @can('admin.marketplace.trackings.delete')
                        @if (!$item->trackingstate->isDefault())
                            <x-button-delete wire:click="delete({{ $item->id }})" wire:loading.attr="disabled" />
                        @endif
                    @endcan
                </li>
            @endforeach
        </ol>
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

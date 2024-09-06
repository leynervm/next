<div>
    <div>
        <x-button-next titulo="Registrar" wire:click="$set('open', true)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="5" y2="19" />
                <line x1="5" x2="19" y1="12" y2="12" />
            </svg>
        </x-button-next>
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Aperturar caja') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" x-data="data">
                <div class="w-full">
                    <x-label value="Seleccionar caja :" />
                    <div id="parentaperturabox_id" x-init="boxApetura" class="relative">
                        <x-select class="block w-full" x-ref="boxapertura" id="aperturabox_id"
                            data-dropdown-parent="null">
                            <x-slot name="options">
                                @if (count($boxes) > 0)
                                    @foreach ($boxes as $item)
                                        @php
                                            $nameuser = !is_null($item->user)
                                                ? $item->user->name
                                                : '<span class="bg-green-500 leading-3 rounded-md p-1 text-white text-[10px]">DISPONIBLE</span>';
                                        @endphp
                                        <option {{ $item->user ? 'disabled' : '' }} value="{{ $item->id }}"
                                            title="{{ $nameuser }}" data-apertura="{{ $item->apertura }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="box_id" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Saldo apertura :" />
                    <x-input class="block w-full" x-model="apertura" wire:model.defer="apertura" type="number"
                        step="0.01" min="0" onkeypress="return validarDecimal(event, 8)" />
                    <x-jet-input-error for="apertura" />
                </div>

                @if (Module::isDisabled('Employer'))
                    <div class="w-full mt-2">
                        <x-label value="fecha cierre :" />
                        <x-input class="block w-full" wire:model.defer="expiredate" type="datetime-local" />
                        <x-jet-input-error for="expiredate" />
                    </div>
                @endif

                <div>
                    <x-jet-input-error for="monthbox.id" />
                    <x-jet-input-error for="employer.id" />
                    <x-jet-input-error for="concept_id" />
                    <x-jet-input-error for="moneda_id" />
                    <x-jet-input-error for="methodpayment_id" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                box_id: @entangle('box_id').defer,
                apertura: @entangle('apertura').defer,
            }))
        })

        function boxApetura() {
            this.select2B = $(this.$refs.boxapertura).select2({
                templateResult: formatOption
            });
            this.select2B.val(this.box_id).trigger("change");
            this.select2B.on("select2:select", (event) => {
                this.box_id = event.target.value;
                this.apertura = event.target.options[event.target.selectedIndex].getAttribute('data-apertura');
            })
            this.$watch('box_id', (value) => {
                this.select2B.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.select2B.select2({
                    templateResult: formatOption
                }).val(this.box_id).trigger('change');
            });
        }

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text + '</strong><p class="select2-subtitle-option">' + option.title + '</p>'
            );
            return $option;
        };
    </script>
</div>

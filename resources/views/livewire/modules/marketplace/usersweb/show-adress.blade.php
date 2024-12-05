<x-form-card :titulo="__('Shipping Addresses')" :subtitulo="__('Update the shipping address for your purchases.')" classtitulo="!text-lg" x-data="adress">
    <div class="w-full">
        <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(320px,1fr))] gap-2 self-start">
            @foreach ($direccions as $adress)
                <div
                    class="w-full rounded-lg lg:rounded-xl font-medium p-2 border {{ $adress->isDefault() ? 'border-next-500 shadw shadow-next-300' : 'border-borderminicard' }}">
                    <p class="text-colorlabel text-[10px] sm:text-xs">{{ $adress->ubigeo->distrito }} \
                        {{ $adress->ubigeo->provincia }} \
                        {{ $adress->ubigeo->region }}</p>
                    <p class="text-colorsubtitleform text-[10px]">{{ $adress->name }}</p>
                    <p class="text-colorsubtitleform text-[10px] leading-none">{{ $adress->referencia }}</p>

                    <div class="w-full flex items-end justify-end gap-2 pt-2">
                        @if ($adress->isDefault())
                            <div class="p-1 mr-auto">
                                <x-icon-default class="" />
                            </div>
                        @else
                            <button type="button" wire:click="savedefault({{ $adress->id }})"
                                wire:loading.attr="disabled"
                                class="inline-block mr-auto group relative font-semibold text-sm bg-transparent text-yellow-500 p-1 rounded-md hover:bg-yellow-500 focus:bg-yellow-500 hover:ring-2 hover:ring-yellow-300 focus:ring-2 focus:ring-yellow-300 hover:text-white focus:text-white disabled:opacity-25 transition ease-in duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-auto" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M13.7276 3.44418L15.4874 6.99288C15.7274 7.48687 16.3673 7.9607 16.9073 8.05143L20.0969 8.58575C22.1367 8.92853 22.6167 10.4206 21.1468 11.8925L18.6671 14.3927C18.2471 14.8161 18.0172 15.6327 18.1471 16.2175L18.8571 19.3125C19.417 21.7623 18.1271 22.71 15.9774 21.4296L12.9877 19.6452C12.4478 19.3226 11.5579 19.3226 11.0079 19.6452L8.01827 21.4296C5.8785 22.71 4.57865 21.7522 5.13859 19.3125L5.84851 16.2175C5.97849 15.6327 5.74852 14.8161 5.32856 14.3927L2.84884 11.8925C1.389 10.4206 1.85895 8.92853 3.89872 8.58575L7.08837 8.05143C7.61831 7.9607 8.25824 7.48687 8.49821 6.99288L10.258 3.44418C11.2179 1.51861 12.7777 1.51861 13.7276 3.44418Z">
                                    </path>
                                </svg>
                            </button>
                        @endif

                        <x-button-edit wire:click="edit({{ $adress->id }})" wire:loading.attr="disabled"
                            wire:key="edit_{{ $adress->id }}" />
                        <x-button-delete wire:loading.attr="disabled" wire:key="delete_{{ $adress->id }}"
                            @click="confirmDelete('{{ $adress->id }}')" />
                    </div>
                </div>
            @endforeach
        </div>
        <div class="w-full flex items-end justify-end pt-4">
            <x-button wire:click="$toggle('open')" wire:loading.attr="disabled">
                {{ __('Add') }}</x-button>
        </div>
    </div>


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar dirección') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Lugar :" />
                    <div class="relative" x-data="{ ubigeo_id: @entangle('ubigeo_id').defer }" x-init="select2Ubigeo">
                        <x-select class="block w-full" x-ref="selectubigeo" id="ubigeo_id" data-dropdown-parent="null"
                            data-minimum-results-for-search="3" data-placeholder="Seleccionar">
                            <x-slot name="options">
                                @foreach ($ubigeos as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->region }} / {{ $item->provincia }}/ {{ $item->distrito }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="ubigeo_id" />
                </div>
                <div class="w-full">
                    <x-label value="Dirección, calle  y/o avenida :" />
                    <x-input class="block w-full" wire:model.defer="name" />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full">
                    <x-label value="Referencia :" />
                    <x-input class="block w-full" wire:model.defer="referencia" />
                    <x-jet-input-error for="referencia" />
                </div>
                <div class="w-full flex justify-end flex-wrap gap-2">
                    <x-button-secondary wire:loading.attr="disabled"
                        wire:click="$set('open', false)">CANCELAR</x-button-secondary>
                    <x-button type="submit" wire:loading.attr="disabled" wire:click="save">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adress', () => ({
                confirmDelete(direccion_id) {
                    swal.fire({
                        title: '¿ELIMINAR DIRECCIÓN DE ENTREGA ?',
                        text: null,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0FB9B9',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar',
                        didOpen: () => {
                            document.body.style.overflow = 'hidden';
                        },
                        didClose: () => {
                            document.body.style.overflow = 'auto';
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$wire.delete(direccion_id);
                        }
                    })
                }
            }))
        })

        function select2Ubigeo() {
            this.selectU = $(this.$refs.selectubigeo).select2({
                templateResult: formatOption
            });
            this.selectU.val(this.ubigeo_id).trigger("change");
            this.selectU.on("select2:select", (event) => {
                this.ubigeo_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("ubigeo_id", (value) => {
                this.selectU.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectU.select2().val(this.ubigeo_id).trigger('change');
            });
        }

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text + '</strong><p class="select2-subtitle-option text-[10px]">' + option
                .title +
                '</p>'
            );
            return $option;
        }
    </script>
</x-form-card>

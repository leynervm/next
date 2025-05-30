<div x-data="createticket">
    <x-loading-web-next x-cloak wire:loading.flex wire:key="loadingregisterticket" />

    <form wire:submit.prevent="save" class="w-full flex flex-col gap-2 lg:gap-5">

        <x-form-card titulo="DATOS DEL CLIENTE" class="w-full gap-2">
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                <div class="w-full">
                    <x-label value="DNI / RUC :" />
                    @if ($client_id)
                        <div class="w-full inline-flex relative">
                            <x-disabled-text :text="$document" class="w-full flex-1 block" />
                            <x-button-close-modal class="btn-desvincular" wire:click="limpiarcliente"
                                wire:loading.attr="disabled" />
                        </div>
                    @else
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full flex-1 input-number-none" x-model="document"
                                wire:model.defer="document" wire:keydown.enter.prevent="searchcliente" minlength="8"
                                maxlength="11" onkeypress="return validarNumero(event, 11)"
                                onpaste="return validarPasteNumero(event, 11)" />
                            <x-button-add class="px-2" wire:click="searchcliente" wire:loading.attr="disabled">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </x-button-add>
                        </div>
                    @endif
                    <x-jet-input-error for="document" />
                </div>
                <div class="w-full col-span-2">
                    <x-label value="Cliente / Razón Social :" />
                    <x-input class="block w-full" x-model="name" placeholder="" />
                    <x-jet-input-error for="name" />
                </div>
            </div>

            <div class="w-full flex flex-col sm:flex-row gap-1">
                @if (count($telefonos) > 0)
                    <div class="flex-1 flex-wrap gap-2">
                        @foreach ($telefonos as $item)
                            <x-minicard :title="formatTelefono($item['phone'])" wire:key="phone_{{ $item['phone'] }}">
                                <x-slot name="content">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="block size-5 text-colorsubtitleform"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                        </path>
                                    </svg>
                                </x-slot>
                                <x-slot name="buttons">
                                    <x-button-delete wire:click="removephone('{{ $item['phone'] }}')"
                                        wire:loading.attr="disabled" />
                                </x-slot>
                            </x-minicard>
                        @endforeach
                    </div>
                @endif

                <div class="inline-flex gap-2 items-end justify-end">
                    <x-button type="button" wire:loading.attr="disabled" wire:click="$toggle('openphone')">
                        {{ __('Agregar teléfono') }}
                    </x-button>
                    @if ($client_id && strlen($document) == 11)
                        <x-button type="button" wire:loading.attr="disabled">
                            {{ __('Agregar Contacto') }}
                        </x-button>
                    @endif
                </div>
            </div>
        </x-form-card>


        <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 lg:gap-5">
            <x-form-card titulo="PRIORIDAD ATENCIÓN" class="w-full gap-2">
                @if (count($priorities))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($priorities as $item)
                            <x-input-radio class="py-2" for="priority_{{ $item->id }}" :text="$item->name">
                                <input wire:model.defer="priority_id" class="sr-only peer peer-disabled:opacity-25"
                                    type="radio" id="priority_{{ $item->id }}" name="priorities"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
            </x-form-card>

            <x-form-card titulo="TIPO DE ATENCIÓN" class="w-full gap-2">
                @if (count($areawork->atencions))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($areawork->atencions as $item)
                            <x-input-radio class="py-2" for="atencion_{{ $item->id }}" :text="$item->name">
                                <input wire:model.lazy="atencion_id" class="sr-only peer peer-disabled:opacity-25"
                                    type="radio" id="atencion_{{ $item->id }}" name="atencions"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
            </x-form-card>

            <x-form-card titulo="ENTORNO ATENCIÓN" class="w-full gap-2">
                @if (count($entornos))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($entornos as $item)
                            <x-input-radio class="py-2" for="entorno_{{ $item->id }}" :text="$item->name">
                                <input wire:key="entorno_{{ $item->id }}" wire:model.defer="entorno_id"
                                    class="sr-only peer peer-disabled:opacity-25" type="radio"
                                    id="entorno_{{ $item->id }}" name="entornos" value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
            </x-form-card>

            <x-form-card titulo="CONDICIONES DE ATENCIÓN" class="w-full gap-2">
                @if (count($conditions))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($conditions as $item)
                            <x-input-radio class="py-2" for="condition_{{ $item->id }}" :text="$item->name">
                                <input wire:model.defer="condition_id" class="sr-only peer peer-disabled:opacity-25"
                                    type="radio" id="condition_{{ $item->id }}" name="conditions"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
            </x-form-card>
        </div>

        @if ($addequipo)
            <x-form-card titulo="AGREGAR EQUIPO" class="w-full gap-2">
            </x-form-card>
        @endif

        <div class="w-full flex justify-end pt-4">
            <x-button type="submit" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </div>
    </form>

    <x-jet-dialog-modal wire:model="openphone" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar teléfono') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="addphone" class="w-full flex flex-col gap-2">
                <div class="">
                    <x-label value="Teléfono :" />
                    <x-input type="number" class="block w-full input-number-none" wire:model.defer="phonenumber" />
                    <x-jet-input-error for="phonenumber" />
                </div>

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                    <x-button type="button" wire:click="addphone(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('createticket', () => ({
                document: @entangle('document').defer,
                name: @entangle('name').defer,
                direccion: @entangle('direccion').defer,
                init() {
                    Livewire.hook('message.processed', () => {});
                    // this.$watch("typepayment_id", (value) => {
                    //     this.selectTP.val(value).trigger("change");
                    // });
                }
                // savepay(event) {
                //     this.$wire.save();
                //     event.preventDefault();
                // }
            }));
        })
    </script>
</div>

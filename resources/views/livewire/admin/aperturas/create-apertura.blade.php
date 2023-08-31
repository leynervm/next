<div>
    @if (count(
            \App\Models\Opencaja::CajasAbiertas()->CajasUser()->get()) < 1)

        <div class="w-full flex flex-wrap gap-3">
            <x-button-next titulo="Registrar" wire:click="$set('open', true)">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" x2="12" y1="5" y2="19" />
                    <line x1="5" x2="19" y1="12" y2="12" />
                </svg>
            </x-button-next>
            @if (session('message'))
                <div class="animate-pulse">
                    <div class="flex p-5 rounded-lg shadow bg-amber-50">

                        {{-- <svg class="w-6 h-6 fill-current text-yellow-500" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path d="M12 5.99L19.53 19H4.47L12 5.99M12 2L1 21h22L12 2zm1 14h-2v2h2v-2zm0-6h-2v4h2v-4z" />
                            </svg> --}}
                        <svg class="w-6 h-6 text-yellow-500 animate-ping block" stroke-width="2" viewBox="0 0 24 24"
                            fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                            <path d="M11 6l-6 6 6 6M19 6l-6 6 6 6" />
                        </svg>

                        <div class="ml-3">
                            <h2 class=" text-sm text-yellow-500">Mensaje</h2>
                            <p class="mt-2 font-semibold text-sm text-gray-500 leading-relaxed">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>




        <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
            <x-slot name="title">
                {{ __('Aperturar caja') }}
                <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </x-button-add>
            </x-slot>

            <x-slot name="content">
                <form wire:submit.prevent="save">

                    <div class="w-full">
                        <x-label value="Lista precio :" />
                        <x-select class="block w-full" wire:model.defer="caja_id" id="aperturacaja_id"
                            data-placeholder="Seleccionar..." data-minimum-results-for-search="Infinity">
                            <x-slot name="options">
                                @if (count($cajas))
                                    @foreach ($cajas as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="caja_id" />
                    </div>

                    <div class="w-full mt-2">
                        <x-label value="Saldo inicial :" />
                        <x-input class="block w-full" wire:model.defer="startmount" type="number" step="0.01"
                            min="0" />
                        <x-jet-input-error for="startmount" />
                    </div>

                    <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                        <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                            wire:target="save">
                            {{ __('REGISTRAR') }}
                        </x-button>
                    </div>
                </form>
            </x-slot>
        </x-jet-dialog-modal>


        <script>
            document.addEventListener("livewire:load", () => {

                renderSelect2();

                $("#aperturacaja_id").on("change", (e) => {
                    deshabilitarSelects();
                    @this.caja_id = e.target.value;
                });

                window.addEventListener('render-apertura-select2', () => {
                    renderSelect2();
                });

                function renderSelect2() {
                    $("#aperturacaja_id").select2();
                }

                function deshabilitarSelects() {
                    $("#aperturacaja_id").disabled = true;
                }

            })
        </script>

    @endif
</div>

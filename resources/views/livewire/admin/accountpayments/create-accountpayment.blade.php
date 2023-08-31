<div>
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nueva cuenta pago') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" id="form_create_accountpayment">

                <x-label value="Tipo banco :" />
                <div id="parent_accountbanco_id">
                    <x-select class="block w-full" wire:model.defer="banco_id" id="banco_id"
                        data-placeholder="Seleccionar..." data-minimum-results-for-search="Infinity">
                        @if (count($bancos))
                            <x-slot name="options">
                                @foreach ($bancos as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </x-slot>
                        @endif
                    </x-select>
                    <x-jet-input-error for="banco_id" />
                </div>

                <x-label value="N° Cuenta :" class="mt-2" />
                <x-input class="block w-full" wire:model.defer="account" placeholder="N° cuenta pago..." type="number" maxlength="15"/>
                <x-jet-input-error for="account" />

                <x-label value="Descripción cuenta :" class="mt-2" />
                <x-input class="block w-full" wire:model.defer="descripcion"
                    placeholder="Descripción de cuenta pago..." />
                <x-jet-input-error for="descripcion" />

                <div class="mt-2">
                    <x-label textSize="[10px]"
                        class="inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                        for="default">
                        <x-input wire:model.defer="default" name="default" type="checkbox" id="default" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label>
                </div>
                <x-jet-input-error for="default" />

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

            $("#banco_id").on("change", (e) => {
                deshabilitarSelects();
                @this.banco_id = e.target.value;
            });

            window.addEventListener('render-createaccount-select2', () => {
                renderSelect2();
            });

            function renderSelect2() {
                var formulario = document.getElementById("form_create_accountpayment");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    if (selects[i].id !== "") {
                        $("#" + selects[i].id).select2();
                    }
                }
            }

            function deshabilitarSelects() {
                var formulario = document.getElementById("form_create_accountpayment");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    selects[i].disabled = true;
                }
            }

        })
    </script>
</div>

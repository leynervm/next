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
            {{ __('Registrar usuario web') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Documento :" />
                    @if ($exists)
                        <div class="w-full inline-flex relative">
                            <x-disabled-text :text="$document" class="w-full block" />
                            <x-button-close-modal class="btn-desvincular" wire:click="limpiaruserweb"
                                wire:loading.attr="disabled" />
                        </div>
                    @else
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full flex-1 prevent" wire:model.defer="document"
                                wire:keydown.enter="getClient" placeholder="DNI..." type="number"
                                onkeypress="return validarNumero(event, 11)" onkeydown="disabledEnter(event)" />
                            <x-button-add class="px-2" wire:click="getClient" wire:loading.attr="disabled"
                                wire:target="getClient">
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

                <div class="w-full">
                    <x-label value="Nombres :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Nombres del usuario..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full">
                    <x-label value="Correo electrónico :" />
                    <x-input class="block w-full" type="email" wire:model.defer="email"
                        placeholder="correo electrónico..." />
                    <x-jet-input-error for="email" />
                </div>

                <div class="w-full">
                    <x-label value="Contraseña :" />
                    <x-input class="block w-full" type="password" wire:model.defer="password"
                        placeholder="contraseña..." />
                    <x-jet-input-error for="password" />
                </div>

                <div class="w-full">
                    <x-label value="Confirmar contraseña :" />
                    <x-input class="block w-full" type="password" wire:model.defer="password_confirmation"
                        placeholder="Confirmar contraseña..." />
                    <x-jet-input-error for="password_confirmation" />
                </div>

                <div class="w-full flex flex-wrap gap-1 pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                    <x-button wire:click="save(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>

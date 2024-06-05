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
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Documento :" />
                    <x-input class="block w-full" onkeypress="return validarNumero(event, 11)"
                        wire:model.defer="document" placeholder="Documento del usuario..." />
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

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>

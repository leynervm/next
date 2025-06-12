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
            {{ __('Nuevo status') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <x-label value="Nombre del status :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre del status..." />
                <x-jet-input-error for="name" />

                <x-label value="Descripción :" class="mt-2" />
                <x-input class="block w-full" wire:model.defer="descripcion"
                    placeholder="Ingrese descripción del status..." />
                <x-jet-input-error for="descripcion" />

                <div class="w-full">
                    <x-label value="Color :" class="mt-2" />
                    <input wire:model.defer="color" type="color" value="#000000" class="h-14 w-14" />
                    <x-jet-input-error for="color" />
                </div>

                <x-label class="mt-2 inline-flex items-center gap-1 cursor-pointer" for="default">
                    <x-input wire:model.defer="default" type="checkbox" id="default" value="1" />
                    Definir valor como predeterminado
                </x-label>

                <x-label class="mt-2 inline-flex items-center gap-1 cursor-pointer" for="finish">
                    <x-input wire:model.defer="finish" type="checkbox" id="finish" value="1" />
                    Definir status como finalización
                </x-label>
                <x-jet-input-error for="finish" />
                <x-jet-input-error for="default" />

                <x-label class="mt-6 border-b" value="Atenciones asignar" />


                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="save">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

</div>

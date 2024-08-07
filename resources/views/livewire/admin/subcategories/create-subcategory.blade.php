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
            {{ __('Nueva subcategoría') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="name"
                        placeholder="Ingrese nombre subcategoría..." />
                    <x-jet-input-error for="name" />
                </div>

                <x-label value="Seleccionar categorías :" />
                @if (count($categories))
                    <div class="w-full flex flex-wrap gap-1">
                        @foreach ($categories as $item)
                            <x-input-radio class="py-2" for="category_{{ $item->id }}" :text="$item->name">
                                <input wire:model.defer="selectedCategories"
                                    class="sr-only peer peer-disabled:opacity-25" type="checkbox"
                                    id="category_{{ $item->id }}" name="categories" value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedCategories" />

                <div class="w-full flex items-end gap-1 pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                    <x-button type="submit" wire:click="save(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>

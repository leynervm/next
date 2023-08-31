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
            {{ __('Nueva área') }}
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
                <x-label value="Área :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre de área..." />
                <x-jet-input-error for="name" />

                <x-label class="mt-2" value="Slug :" />
                <x-input class="block w-full" wire:model.defer="slug" />
                <x-jet-input-error for="slug" />

                <x-label class="mt-2 flex items-center gap-1 cursor-pointer" for="visible">
                    <x-input wire:model.defer="visible" type="checkbox" id="visible" value="1" />
                    Permitir vincular ordenes de trabajo.
                </x-label>

                <x-label class="mt-6" value="Entornos atención" />
                @if (count($entornos))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($entornos as $item)
                            <x-label class="flex items-center gap-1 cursor-pointer bg-next-100 rounded-lg p-1"
                                for="entorno_{{ $item->id }}">
                                <x-input wire:model.defer="arrayEntornos" name="entornos[]" type="checkbox"
                                    id="entorno_{{ $item->id }}" value="{{ $item->id }}" />
                                {{ $item->name }}
                            </x-label>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="arrayAtencions" />


                {{-- <x-label class="mt-6" value="Atenciones asignar" />
                @if (count($atenciones))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($atenciones as $item)
                            <x-label class="flex items-center gap-1 cursor-pointer bg-next-100 rounded-lg p-1"
                                for="estate_{{ $item->id }}">
                                <x-input wire:model.defer="arrayAtencions" name="atencions[]" type="checkbox"
                                    id="estate_{{ $item->id }}" value="{{ $item->id }}" />
                                {{ $item->name }}
                            </x-label>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="arrayAtencions" /> --}}


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

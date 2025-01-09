<div class="w-full flex flex-col gap-3 md:gap-5 lg:gap-8">
    <x-form-card titulo="PERFIL USUARIO" class="">
        <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
            <div class="w-full">
                <x-label value="Documento :" />
                <div class="w-full inline-flex gap-1">
                    <x-disabled-text :text="$user->document" class="w-full block flex-1" />
                    <x-button-add class="px-2 flex-shrink-0" wire:click="searchclient" wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </x-button-add>
                </div>
                <x-jet-input-error for="user.document" />
            </div>

            <div class="w-full">
                <x-label value="Nombres :" />
                <x-input class="block w-full" wire:model.defer="user.name" placeholder="Nombres del usuario..." />
                <x-jet-input-error for="user.name" />
            </div>

            <div class="w-full">
                <x-label value="Correo electrónico :" />
                <x-disabled-text :text="$user->email" class="w-full block" />
                <x-jet-input-error for="user.email" />
            </div>

            <div class="w-full flex flex-wrap gap-1 pt-4 justify-end">
                <x-button type="submit" wire:loading.attr="disabled">
                    {{ __('Save') }}</x-button>
            </div>
        </form>
    </x-form-card>

    <x-form-card titulo="DIRECCIONES DE ENTREGA" class="">
        @if (count($user->direccions) > 0)
            <div class="w-full flex flex-col gap-2">
                @foreach ($user->direccions as $item)
                    <div
                        class="w-full rounded-lg lg:rounded-xl font-medium p-2 border {{ $item->isDefault() ? 'border-next-500 shadw shadow-next-300' : 'border-borderminicard' }}">
                        <p class="text-colorlabel text-[10px] sm:text-xs">
                            {{ $item->ubigeo->distrito }}, {{ $item->ubigeo->provincia }}, {{ $item->ubigeo->region }}
                        </p>
                        <p class="text-colorsubtitleform text-[10px]">{{ $item->name }}</p>
                        <p class="text-colorsubtitleform text-[10px] leading-none">
                            REF.: {{ $item->referencia }}</p>

                        <div class="w-full flex items-end justify-end gap-2 pt-2">
                            @if ($item->isDefault())
                                <div class="p-1 mr-auto">
                                    <x-icon-default class="" />
                                </div>
                            @else
                                <button type="button" wire:click="savedefault({{ $item->id }})"
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
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs font-medium text-colorerror">No existen direcciones registradas...</p>
        @endif
    </x-form-card>
</div>

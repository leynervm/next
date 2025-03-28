<div x-data="importar">
    @can('admin.administracion.rangos.import')
        <x-button-next titulo="Importar lista rangos" wire:click="$set('open', true)">
            <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M3.5 13V12.1963C3.5 9.22892 3.5 7.74523 3.96894 6.56024C4.72281 4.65521 6.31714 3.15255 8.33836 2.44201C9.59563 2.00003 11.1698 2.00003 14.3182 2.00003C16.1173 2.00003 17.0168 2.00003 17.7352 2.25259C18.8902 2.65861 19.8012 3.51728 20.232 4.60587C20.5 5.283 20.5 6.13082 20.5 7.82646V12.0142V13" />
                <path
                    d="M3.5 12C3.5 10.1591 4.99238 8.66667 6.83333 8.66667C7.49912 8.66667 8.28404 8.78333 8.93137 8.60988C9.50652 8.45576 9.95576 8.00652 10.1099 7.43136C10.2833 6.78404 10.1667 5.99912 10.1667 5.33333C10.1667 3.49238 11.6591 2 13.5 2" />
                <path
                    d="M7.5 17.2196C7.44458 16.0292 6.62155 16 5.50505 16C3.78514 16 3.5 16.406 3.5 18V20C3.5 21.594 3.78514 22 5.50505 22C6.62154 22 7.44458 21.9708 7.5 20.7804M20.5 16L18.7229 20.6947C18.3935 21.5649 18.2288 22 17.968 22C17.7071 22 17.5424 21.5649 17.213 20.6947L15.4359 16M12.876 16H11.6951C11.2231 16 10.9872 16 10.8011 16.0761C10.1672 16.3354 10.1758 16.9448 10.1758 17.5C10.1758 18.0553 10.1672 18.6647 10.8011 18.9239C10.9872 19 11.2232 19 11.6951 19C12.167 19 12.4029 19 12.5891 19.0761C13.2229 19.3354 13.2143 19.9447 13.2143 20.5C13.2143 21.0553 13.2229 21.6647 12.5891 21.9239C12.4029 22 12.167 22 11.6951 22H10.4089" />
            </svg>
        </x-button-next>
    @endcan

    <x-jet-dialog-modal wire:model="open" maxWidth="2xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('IMPORTAR LISTA DE RANGOS') }}
        </x-slot>

        <x-slot name="content">
            <div wire:loading.flex class="loading-overlay rounded hidden fixed z-[200]">
                <x-loading-next />
            </div>

            <form wire:submit.prevent="import" class="w-full flex flex-col justify-center items-center gap-2">
                <label class="">
                    <x-icon-file-upload type="excel"
                        class="size-28 border {{ $file ? 'border-fondobutton text-primary animate-pulse' : '' }} cursor-pointer hover:text-fondobutton transition ease-in-out duration-150" />
                    <input type="file" class="hidden" wire:model="file" id="{{ $identificador }}"
                        accept=".xlsx, .csv" />

                    <p class="block w-full max-w-28 text-wrap text-[10px] mt-2 text-center leading-none font-semibold">
                        @if ($file)
                            ARCHIVO CARGADO CORRECTAMENTE
                        @else
                            IMPORTAR LISTA RANGOS
                        @endif
                    </p>
                </label>
                <x-jet-input-error for="file" />

                @if (isset($errors) && $errors->any())
                    @foreach ($errors->all() as $item)
                        <x-jet-input-error :for="$item" />
                    @endforeach
                @endif

                @if (count($failures) > 0)
                    <x-table class="block w-full max-h-[500px]">
                        <x-slot name="header">
                            <tr>
                                <th class="p-1.5">COLUMNA</th>
                                <th class="p-1.5">FILA</th>
                                <th class="p-1.5">ERRORES</th>
                                <th class="p-1.5">VALOR</th>
                            </tr>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($failures as $item)
                                <tr class="text-colorsubtitleform">
                                    <td class="p-1 text-center w-6">{{ $item['attribute'] }}</td>
                                    <td class="p-1 text-center w-2">{{ $item['row'] }}</td>
                                    <td class="p-1">
                                        @if (count($item['errors']) > 0)
                                            <ul>
                                                @foreach ($item['errors'] as $error)
                                                    <li class="text-colorerror">{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td class="p-1 text-center">
                                        @if (count($item['errors']) > 0)
                                            {{ $item['values'][$item['attribute']] ?? '' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-table>
                @endif
                {{-- {{ var_dump($failures) }} --}}

                <div class="w-full flex flex-wrap gap-1 pt-4 justify-end">
                    @if ($file)
                        <x-button wire:loading.attr="disabled" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" />
                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                <path d="M2 15h10" />
                                <path d="m9 18 3-3-3-3" />
                            </svg>
                            IMPORTAR
                        </x-button>
                        <x-button type="reset" class="inline-flex" wire:loading.attr="disabled"
                            wire:click="resetFile">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3 6h18" />
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                <line x1="10" x2="10" y1="11" y2="17" />
                                <line x1="14" x2="14" y1="11" y2="17" />
                            </svg>
                            LIMPIAR
                        </x-button>
                    @endif
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('importar', () => ({
                open: false,

                init() {},
                openModal() {
                    this.open = true;
                    document.body.style.overflow = 'hidden';
                },
                close() {
                    this.open = false;
                    document.body.style.overflow = 'auto';
                }
            }))
        })
    </script>
</div>

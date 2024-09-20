<div>
    <div wire:loading.flex class="loading-overlay hidden overflow-hidden fixed">
        <x-loading-next />
    </div>

    @if ($users->hasPages())
        <div class="pb-2">
            {{ $users->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex items-center gap-2 mt-4">
        <div class="w-full max-w-sm">
            <x-label value="Buscar usuario :" />
            <div class="relative flex items-center">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mx-3 text-next-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <x-input placeholder="Buscar email, documento, nombres del usuario..." class="block w-full pl-9"
                    wire:model.lazy="search">
                </x-input>
            </div>
        </div>
    </div>

    <x-table class="mt-1">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>NOMBRES</span>

                        <svg class="h-3 w-3" viewBox="0 0 10 11" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" stroke="currentColor" stroke-width="0.1">
                            <path
                                d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" />
                            <path
                                d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" />
                            <path
                                d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                stroke-width="0.3" />
                        </svg>
                    </button>
                </th>
                <th scope="col" class="p-2 font-medium text-left">
                    CORREO</th>
                <th scope="col" class="p-2 font-medium">
                    VERIFICACIÓN</th>
                <th scope="col" class="p-2 font-medium">
                    ACCESO</th>
                <th scope="col" class="p-2 font-medium">
                    STATUS</th>
                @canany(['admin.marketplace.userweb.edit', 'admin.marketplace.userweb.delete'])
                    <th scope="col" class="p-2 relative">
                        <span class="sr-only">OPCIONES</span>
                    </th>
                @endcanany
            </tr>
        </x-slot>
        @if (count($users) > 0)
            <x-slot name="body">
                @foreach ($users as $item)
                    <tr>
                        <td class="p-2 uppercase">
                            <h1 class="w-full">{{ $item->document }}</h1>
                            <p>{{ $item->name }}</p>
                        </td>

                        <td class="p-2">
                            {{ $item->email }}
                        </td>

                        <td class="p-2 text-center">
                            @if ($item->email_verified_at)
                                <x-icon-default class="inline-block" />
                            @else
                                <x-span-text text="NO VERIFICADO" type="red" class="leading-3 !tracking-normal" />
                            @endif
                        </td>

                        <td class="p-2 text-center">
                            <x-span-text text="WEB" class="leading-3 !tracking-normal inline-block" />
                        </td>
                        <td class="p-2 text-center">
                            @if ($item->trashed())
                                <x-span-text text="BAJA" type="red" class="leading-3 !tracking-normal" />
                            @else
                                <x-span-text text="ACTIVO" type="green" class="leading-3 !tracking-normal" />
                            @endif
                        </td>
                        @canany(['admin.marketplace.userweb.edit', 'admin.marketplace.userweb.delete'])
                            <td class="p-2 text-center">
                                @can('admin.marketplace.userweb.delete')
                                    @if (!$item->isAdmin())
                                        @if ($item->trashed())
                                            <x-button-toggle onclick="restoreUser({{ $item }})"
                                                wire:loading.attr="disabled" wire:key="restoreuser{{ $item->id }}"
                                                :checked="false" />
                                        @else
                                            <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})"
                                                wire:key="editeuser_{{ $item->id }}" />

                                            <x-button-toggle onclick="confirmDelete({{ $item }})"
                                                wire:loading.attr="disabled" wire:key="deleteuser_{{ $item->id }}" />
                                        @endif
                                    @endif
                                @endcan
                            </td>
                        @endcanany
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar usuario web') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Documento :" />
                    <x-input class="block w-full" onkeypress="return validarNumero(event, 11)"
                        wire:model.defer="user.document" placeholder="Documento del usuario..." />
                    <x-jet-input-error for="user.document" />
                </div>

                <div class="w-full">
                    <x-label value="Nombres :" />
                    <x-input class="block w-full" wire:model.defer="user.name" placeholder="Nombres del usuario..." />
                    <x-jet-input-error for="user.name" />
                </div>

                <div class="w-full">
                    <x-label value="Correo electrónico :" />
                    <x-input class="block w-full" type="email" wire:model.defer="user.email"
                        placeholder="correo electrónico..." />
                    <x-jet-input-error for="user.email" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function confirmDelete(user) {
            swal.fire({
                title: 'REALIZAR BAJA DE USUARIO WEB CON NOMBRES ' + user.name,
                text: "Se dará de baja un usuario web de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(user.id);
                }
            })
        }

        function restoreUser(user) {
            swal.fire({
                title: 'ACTIVAR USUARIO WEB ' + user.name + ' ?',
                text: "Se actualizará un registro en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.restoreuser(user.id);
                }
            })
        }
    </script>
</div>

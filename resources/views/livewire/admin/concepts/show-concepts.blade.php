<div class="">

    @if ($concepts->hasPages())
        <div class="pb-2">
            {{ $concepts->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($concepts))
            @foreach ($concepts as $item)
                @php
                    $default = '';
                    switch ($item->default) {
                        case 1:
                            $default = 'Pago ventas';
                            break;
                        case 2:
                            $default = 'Pago internet';
                            break;
                        case 3:
                            $default = 'Pago cuotas';
                            break;
                        default:
                            $default = null;
                            break;
                    }
                @endphp

                <x-minicard :title="$item->name" :content="$default" :alignFooter="$item->default ? 'justify-between' : 'justify-end'" size="md">
                    <x-slot name="buttons">
                        <div class="inline-flex">
                            @if ($item->default == 1)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-next-500 scale-125"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path fill="currentColor"
                                        d="M18.9905 19H19M18.9905 19C18.3678 19.6175 17.2393 19.4637 16.4479 19.4637C15.4765 19.4637 15.0087 19.6537 14.3154 20.347C13.7251 20.9374 12.9337 22 12 22C11.0663 22 10.2749 20.9374 9.68457 20.347C8.99128 19.6537 8.52349 19.4637 7.55206 19.4637C6.76068 19.4637 5.63218 19.6175 5.00949 19C4.38181 18.3776 4.53628 17.2444 4.53628 16.4479C4.53628 15.4414 4.31616 14.9786 3.59938 14.2618C2.53314 13.1956 2.00002 12.6624 2 12C2.00001 11.3375 2.53312 10.8044 3.59935 9.73817C4.2392 9.09832 4.53628 8.46428 4.53628 7.55206C4.53628 6.76065 4.38249 5.63214 5 5.00944C5.62243 4.38178 6.7556 4.53626 7.55208 4.53626C8.46427 4.53626 9.09832 4.2392 9.73815 3.59937C10.8044 2.53312 11.3375 2 12 2C12.6625 2 13.1956 2.53312 14.2618 3.59937C14.9015 4.23907 15.5355 4.53626 16.4479 4.53626C17.2393 4.53626 18.3679 4.38247 18.9906 5C19.6182 5.62243 19.4637 6.75559 19.4637 7.55206C19.4637 8.55858 19.6839 9.02137 20.4006 9.73817C21.4669 10.8044 22 11.3375 22 12C22 12.6624 21.4669 13.1956 20.4006 14.2618C19.6838 14.9786 19.4637 15.4414 19.4637 16.4479C19.4637 17.2444 19.6182 18.3776 18.9905 19Z">
                                    </path>
                                    <path stroke="#fff" fill="#fff"
                                        d="M9 12.8929C9 12.8929 10.2 13.5447 10.8 14.5C10.8 14.5 12.6 10.75 15 9.5">
                                    </path>
                                </svg>
                            @endif
                            @if ($item->web)
                                <span
                                    class="bg-green-100 text-green-500 p-1 rounded-full @if ($item->default) absolute left-6 ring-2 ring-white @endif">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="2" x2="22" y1="12" y2="12" />
                                        <path
                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                    </svg>
                                </span>
                            @endif
                        </div>

                        <div class="">
                            <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                                wire:click="edit({{ $item->id }})"></x-button-edit>
                            <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete"
                                wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar concepto pago') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <x-label value="Descripción concepto :" />
                <x-input class="block w-full" wire:model.defer="concept.name"
                    placeholder="Descripción del concepto pago..." />
                <x-jet-input-error for="concept.name" />

                <fieldset class="border border-solid border-next-300 mt-2">
                    <legend class="text-xs text-next-500 tracking-wider block">Asignar predeterminado</legend>

                    <div class="w-full flex gap-1 items-start p-1">

                        <div class="block">
                            <x-label-check for="edit_ninguno">
                                <x-input wire:model.defer="concept.default" name="default" type="radio" value="0"
                                    id="edit_ninguno" class="checked:rounded-full" />
                                NINGUNO
                            </x-label-check>
                        </div>

                        <div class="block">
                            <x-label-check for="edit_ventas">
                                <x-input wire:model.defer="concept.default" name="default" type="radio" value="1"
                                    id="edit_ventas" class="checked:rounded-full" />
                                PAGO VENTAS
                            </x-label-check>
                        </div>

                        <div class="block">
                            <x-label-check for="edit_internet">
                                <x-input wire:model.defer="concept.default" name="default" type="radio" value="2"
                                    id="edit_internet" class="checked:rounded-full" />
                                PAGO INTERNET
                            </x-label-check>
                        </div>

                        <div class="block">
                            <x-label-check for="edit_cuota">
                                <x-input wire:model.defer="concept.default" name="default" type="radio" value="3"
                                    id="edit_cuota" class="checked:rounded-full" />
                                PAGO CUOTA
                            </x-label-check>
                        </div>
                    </div>
                </fieldset>
                <x-jet-input-error for="concept.default" />


                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        document.addEventListener('livewire:load', function() {
            window.addEventListener('concepts.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.detail.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.concepts.show-concepts', 'delete', data.detail
                            .id);
                    }
                })
            })
        })
    </script>
</div>

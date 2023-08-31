<div class="">

    @if (count($methodpayments))
        <div class="pb-2">
            {{ $methodpayments->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($methodpayments))
            @foreach ($methodpayments as $item)
                <div class="w-60">
                    <x-card-next :titulo="$item->name" class="h-full" :alignFooter="$item->default ? 'justify-between' : 'justify-end'">
                        @if (count($item->cuentas))
                            <div class="w-full flex flex-wrap gap-1">
                                @foreach ($item->cuentas as $account)
                                    <p
                                        class="inline-block text-[8px] font-semibold p-1 rounded-lg bg-fondospancardproduct text-textspancardproduct">
                                        {{ $account->account }}
                                        ({{ $account->descripcion }})</p>
                                @endforeach
                            </div>
                        @endif
                        <x-slot name="footer">

                            @if ($item->default)
                                <span class="bg-green-100 text-green-500 p-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                            @endif
                            <div>
                                <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                                    wire:click="edit({{ $item->id }})"></x-button-edit>
                                <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete"
                                    wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                            </div>
                        </x-slot>
                    </x-card-next>
                </div>

                {{-- <x-minicard :title="$item->name" :alignFooter="$item->default == 1 ? 'justify-between' : 'justify-end'" size="lg">
                    <x-slot name="buttons">
                        <div class="inline-flex">
                            @if ($item->default)
                                <span class="bg-green-100 text-green-500 p-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
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
                </x-minicard> --}}
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar área') }}
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
                <x-label value="Forma pago :" />
                <x-input class="block w-full" wire:model.defer="methodpayment.name"
                    placeholder="Ingrese descripción de forma pago..." />
                <x-jet-input-error for="methodpayment.name" />

                <x-label textSize="[10px]"
                    class="mt-1 inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                    for="default_edit">
                    <x-input wire:model.defer="methodpayment.default" name="default" value="1" type="checkbox"
                        id="default_edit" />
                    SELECCIONAR COMO PREDETERMINADO
                </x-label>
                <x-jet-input-error for="methodpayment.default" />

                <x-label value="Asignar cuentas pago :" class="mt-2 underline" />

                @if (count($cuentas))
                    <div class="w-full flex gap-1 flex-wrap mt-1">
                        @foreach ($cuentas as $item)
                            <x-label-check for="edit_{{ $item->id }}">
                                <x-input wire:model.defer="selectedCuentas" name="default" value="{{ $item->id }}"
                                    type="checkbox" id="edit_{{ $item->id }}" />
                                {{ $item->account }} ({{ $item->descripcion }} - {{ $item->banco->name }})
                            </x-label-check>
                        @endforeach
                    </div>
                @endif

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
            window.addEventListener('methodpayments.confirmDelete', data => {
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
                        Livewire.emitTo('methodpayments.show-methodpayments', 'delete', data
                            .detail.id);

                    }
                })
            })
        })
    </script>
</div>

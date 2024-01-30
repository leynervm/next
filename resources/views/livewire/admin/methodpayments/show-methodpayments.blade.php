<div class="">

    @if ($methodpayments->hasPages())
        <div class="pb-2">
            {{ $methodpayments->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    @if (count($methodpayments))
        <div class="flex gap-3 flex-wrap justify-start mt-3">
            @foreach ($methodpayments as $item)
                <div
                    class="w-full xs:w-60 flex flex-col items-center justify-between bg-fondominicard p-2 rounded-md shadow shadow-shadowminicard hover:shadow-md hover:shadow-shadowminicard">
                    <div>
                        <h1 class="text-xs text-colorlinknav">{{ $item->name }}</h1>
                        @if (count($item->cuentas))
                            <div class="space-y-1 mt-3">
                                <h1
                                    class="text-[10px] text-colorsubtitleform font-semibold tracking-widest relative before:absolute before:bottom-0 before:w-12 before:h-0.5 before:bg-colorsubtitleform">
                                    CUENTAS</h1>
                                @foreach ($item->cuentas as $account)
                                    <p class="text-[10px] text-colorminicard leading-3">
                                        [ {{ $account->account }}] - {{ $account->descripcion }}
                                    </p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="w-full flex items-center gap-2 {{ $item->default ? 'justify-between' : 'justify-end' }}">
                        @if ($item->default)
                            <x-icon-default class="inline-block" />
                        @endif

                        <div class="flex gap-2">
                            <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                            <x-button-delete wire:loading.attr="disabled"
                                wire:click="$emit('methodpayments.confirmDelete', {{ $item }})" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar forma pago') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <x-label value="Forma pago :" />
                <x-input class="block w-full" wire:model.defer="methodpayment.name"
                    placeholder="Ingrese descripción de forma pago..." />
                <x-jet-input-error for="methodpayment.name" />

                <div class="block mt-1">
                    <x-label-check for="default_edit">
                        <x-input wire:model.defer="methodpayment.default" name="default" value="1" type="checkbox"
                            id="default_edit" />SELECCIONAR COMO PREDETERMINADO </x-label-check>
                    <x-jet-input-error for="methodpayment.default" />
                </div>

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

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('methodpayments.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar forma de pago, ' + data.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(data.id);
                    }
                })
            })
        })
    </script>
</div>

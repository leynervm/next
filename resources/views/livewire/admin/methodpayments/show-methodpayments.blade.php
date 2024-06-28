<div class="">

    @if ($methodpayments->hasPages())
        <div class="pb-2">
            {{ $methodpayments->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    @if (count($methodpayments))
        <div class="flex gap-3 flex-wrap justify-start mt-3">
            @foreach ($methodpayments as $item)
                @php
                    $tipo = null;
                    if (!$item->isDefinido()) {
                        $tipo = $item->isTransferencia() ? 'TRANSFERENCIA' : 'EFECTIVO';
                    }
                @endphp

                <x-minicard :title="$item->name" :content="$tipo" size="md"
                    alignFooter="{{ $item->default == '1' ? 'justify-between' : 'justify-end' }}">
                    <x-slot name="buttons">
                        @if ($item->default)
                            <x-icon-default class="inline-block" />
                        @endif

                        <div class="flex gap-2">
                            @can('admin.cajas.methodpayments.edit')
                                <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})"
                                    wire:key="editmethod_{{ $item->id }}" />
                            @endcan

                            @if (!$item->isDefinido())
                                @can('admin.cajas.methodpayments.delete')
                                    <x-button-delete wire:loading.attr="disabled"
                                        onclick="confirmDelete({{ $item }})"
                                        wire:key="deletemethod_{{ $item->id }}" />
                                @endcan
                            @endif
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        </div>
    @endif


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar forma pago') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Forma pago :" />
                    <div class="w-full flex flex-wrap gap-2">
                        <x-input-radio class="py-2" for="edit_efectivo" text="EFECTIVO">
                            <input wire:model.defer="methodpayment.type" class="sr-only peer peer-disabled:opacity-25"
                                type="radio" id="edit_efectivo" name="type" value="0" />
                        </x-input-radio>
                        <x-input-radio class="py-2" for="edit_transferencia" text="TRANSFERENCIA">
                            <input wire:model.defer="methodpayment.type" class="sr-only peer peer-disabled:opacity-25"
                                type="radio" id="edit_transferencia" name="type" value="1" />
                        </x-input-radio>
                    </div>
                    <x-jet-input-error for="methodpayment.type" />
                </div>

                <div>
                    <x-label value="Medio pago :" />
                    <x-input class="block w-full" wire:model.defer="methodpayment.name"
                        placeholder="Ingrese descripción del pago..." />
                    <x-jet-input-error for="methodpayment.name" />
                </div>

                <div class="block">
                    <x-label-check for="default_edit">
                        <x-input wire:model.defer="methodpayment.default" name="default" value="1" type="checkbox"
                            id="default_edit" />SELECCIONAR COMO PREDETERMINADO </x-label-check>
                    <x-jet-input-error for="methodpayment.default" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        function confirmDelete(methodpayment) {
            swal.fire({
                title: 'Eliminar forma de pago ' + methodpayment.name,
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(methodpayment.id);
                }
            })
        }
    </script>
</div>

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
                    class="w-60 flex flex-col items-center justify-center bg-fondominicard p-2 rounded-md shadow shadow-shadowminicard hover:shadow-md hover:shadow-shadowminicard">
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
            @endforeach
        </div>
    @endif


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar forma pago') }}
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

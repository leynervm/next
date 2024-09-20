<div>
    <div class="loading-overlay fixed hidden" wire:loading.flex>
        <x-loading-next />
    </div>

    @if ($typepayments->hasPages())
        <div class="pb-2">
            {{ $typepayments->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    @if (count($typepayments) > 0)
        <div class="w-full flex-1 flex gap-3 flex-wrap justify-start">
            @foreach ($typepayments as $item)
                <x-minicard :title="null" size="lg"
                    alignFooter="{{ $item->isdefault() ? 'justify-between' : 'justify-end' }}">

                    <h1 class="text-center text-xs font-semibold">{{ $item->name }}</h1>

                    @if ($item->isCredito())
                        <p class="text-center text-[10px] leading-none mt-2 text-colorsubtitleform">
                            REQUIERE CUOTAS <br> DE PAGO</p>
                    @endif

                    @can('admin.administracion.typepayments.edit')
                        <x-slot name="buttons">
                            @if ($item->isdefault())
                                <x-icon-default />
                            @endif
                            <x-button-toggle onclick="confirmDisablePayment({{ $item }}, {{ $item->isActivo() }})"
                                wire:loading.attr="disabled" wire:key="restorebox_{{ $item->id }}" :checked="$item->isActivo() ? true : false" />
                        </x-slot>
                    @endcan
                </x-minicard>
            @endforeach
        </div>
    @endif

    <script>
        function confirmDisablePayment(typepayment, estado) {
            let title = estado ? 'DESHABILITAR' : 'HABILITAR';
            let text = estado ? 'No permitirá generar ventas con el tipo de pago a deshabilitar.' :
                'Tipo de pago volverá a estar disponible para generar ventas.';
            swal.fire({
                title: title + ' TIPO DE PAGO  ' + typepayment.name,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.update(typepayment.id);
                }
            })
        }
    </script>
</div>

<div>
    <div wire:loading.flex class="fixed loading-overlay hidden z-[99999]">
        <x-loading-next />
    </div>

    @if ($order->isPagoconfirmado())
        <p class="text-green-600 inline-block text-[10px]">
            PAGO CONFIRMADO CON ÉXITO</p>
        @can('admin.marketplace.orders.confirmpay')
            <x-button-secondary onclick="confirmAnularPago()" wire:loading.attr="disabled" wire:key="anularpago">
                ANULAR PAGO</x-button-secondary>
        @endcan
    @elseif ($order->isPagado())
        <span class="text-green-600 inline-block text-[10px]">
            PAGADO</span>
        <p class="text-[10px] text-orange-600">EN ESPERA DE CONFIRMACIÓN</p>
        @can('admin.marketplace.orders.confirmpay')
            <x-button onclick="confirmPagoPedido()" wire:loading.attr="disabled" wire:key="confirmarpago">
                CONFIRMAR PAGO</x-button>
        @endcan
    @endif

    <script>
        function confirmPagoPedido() {
            swal.fire({
                title: 'CONFIRMAR PAGO DEL PEDIDO?',
                text: "Se actualizará el pago del pedido como confirmado, esta información tambien será visualizado por el usuario del pedido.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.confirmarpago();
                }
            })
        }

        function confirmAnularPago() {
            swal.fire({
                title: 'ANULAR PAGO DEL PEDIDO?',
                text: "Se actualizará el pago del pedido a pediente, esta información tambien será visualizado por el usuario del pedido.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.anularpago();
                }
            })
        }
    </script>
</div>

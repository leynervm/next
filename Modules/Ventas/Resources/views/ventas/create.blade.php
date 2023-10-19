<x-app-layout>
    <div
        class="w-full mt-3 flex flex-wrap xl:flex-nowrap gap-8 overflow-hidden xl:h-[calc(100vh_-_5rem)] animate__animated animate__fadeIn animate__faster">
        <div class="w-full xl:flex-shrink-0 xl:w-96 overflow-hidden">
            <livewire:ventas::ventas.show-resumen-venta :empresa="$empresa" :typepayment="$typepayment" :methodpayment="$methodpayment"
                :typecomprobante="$typecomprobante" :moneda="$moneda" :concept="$concept" :opencaja="$opencaja" />
        </div>
        <div class="w-full xl:flex-shrink-1 overflow-hidden">
            <livewire:ventas.ventas.create-venta :empresa="$empresa" :pricetype="$pricetype" :moneda="$moneda" />
        </div>
    </div>
</x-app-layout>

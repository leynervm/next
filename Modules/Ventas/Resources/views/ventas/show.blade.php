<x-app-layout>
    <div class="mx-auto lg:max-w-4xl xl:max-w-7xl py-10 lg:px-10 animate__animated animate__fadeIn animate__faster">
        <livewire:ventas::ventas.show-venta :venta="$venta" :concept="$concept" :methodpayment="$methodpayment" :opencaja="$opencaja" />
    </div>
</x-app-layout>

<x-app-layout>

    <div class="w-full">
        @livewire('admin.methodpayments.create-methodpayment')
    </div>

    <x-title-next titulo="Formas pago" class="mt-5" />

    @livewire('admin.methodpayments.show-methodpayments')
</x-app-layout>
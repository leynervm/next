<x-app-layout>

    <div class="mt-3 flex gap-2">
        @livewire('admin.accountpayments.create-accountpayment')
    </div>

    <x-title-next titulo="Cuentas de pago" class="mt-5" />
    
    <div class="mt-3">
        @livewire('admin.accountpayments.show-accountpayments')
    </div>

    <x-title-next titulo="Bancos" class="mt-5" />

    {{-- <div class="mt-3 flex gap-2">
        @livewire('admin.bancos.create-banco')
    </div> --}}

    <div class="mt-3">
        @livewire('admin.bancos.show-bancos')
    </div>

</x-app-layout>


<x-admin-layout>

    <div class="mt-3">
        @livewire('soporte::status.create-status')
    </div>

    <x-title-next titulo="Estados de órdenes de trabajo" class="mt-5" />

    <div class="mt-3">
        @livewire('soporte::status.show-status')
    </div>

</x-admin-layout>

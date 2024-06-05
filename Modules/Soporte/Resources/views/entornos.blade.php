<x-admin-layout>

    <div class="mt-3">
        @livewire('soporte::entornos.create-entorno')
    </div>

    <x-title-next titulo="Entornos de atención" class="mt-5" />

    <div class="mt-3">
        @livewire('soporte::entornos.show-entornos')
    </div>

</x-admin-layout>

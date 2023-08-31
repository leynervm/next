<x-app-layout>

    <div class="mt-3">
        @livewire('almacen::units.create-unit')
    </div>

    <x-title-next titulo="Unidades medida" class="mt-5" />

    <div class="mt-3">
        @livewire('almacen::units.show-units')
    </div>

</x-app-layout>

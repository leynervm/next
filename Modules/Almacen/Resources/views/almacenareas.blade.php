<x-app-layout>

    <x-title-next titulo="Áreas almacén" />

    <div class="mt-3">
        @livewire('almacen::almacenareas.show-almacenareas')
    </div>

    <x-title-next titulo="Estantes almacén" class="mt-5" />

    <div class="mt-3">
        @livewire('almacen::estantes.show-estantes')
    </div>

</x-app-layout>
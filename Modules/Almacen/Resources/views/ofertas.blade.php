<x-app-layout>

    <div class="mt-3">
        @livewire('almacen::ofertas.create-oferta')
    </div>

    <x-title-next titulo="Oferta productos" class="mt-5" />

    <div class="mt-3">
        @livewire('almacen::ofertas.show-ofertas')
    </div>

</x-app-layout>

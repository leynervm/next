<x-app-layout>

    {{-- <livewire:almacen::productos.view-producto :producto="$producto" :key="$producto->id" /> --}}
    @livewire('almacen::productos.view-producto', ['producto' => $producto])
</x-app-layout>

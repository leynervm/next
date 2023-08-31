<x-app-layout>

    <div class="mt-3 flex gap-2">
        @livewire('admin.cajas.create-caja')
    </div>

    <x-title-next titulo="Listado Cajas" class="mt-5" />
    
    <div class="mt-3">
        @livewire('admin.cajas.show-cajas')
    </div>

</x-app-layout>


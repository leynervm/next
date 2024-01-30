<x-app-layout>

    <div class="mt-3 flex gap-2">
        @livewire('admin.pricetypes.create-pricetype')
        @livewire('admin.rangos.create-rango')
    </div>

    <x-title-next titulo="LISTA PRECIOS" class="mt-5" />

    <div class="mt-3">
        @livewire('admin.pricetypes.show-pricetypes')
    </div>

    <x-title-next titulo="RANGO PRECIOS COMPRA" class="mt-5" />
    
    <div class="mt-3">
        @livewire('admin.rangos.show-rangos')
    </div>

</x-app-layout>

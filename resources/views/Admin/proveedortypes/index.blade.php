<x-app-layout>

    <div class="mt-3 flex gap-2">
        @livewire('admin.proveedortypes.create-proveedortype')
    </div>

    <x-title-next titulo="Tipo proveedores" class="mt-5" />

    <div class="mt-3">
        @livewire('admin.proveedortypes.show-proveedortypes')
    </div>

</x-app-layout>

<x-app-layout>

    <div class="mt-3 flex gap-2">
        @livewire('admin.concepts.create-concept')
    </div>

    <x-title-next titulo="Listado conceptos" class="mt-5" />
    
    <div class="mt-3">
        @livewire('admin.concepts.show-concepts')
    </div>

</x-app-layout>


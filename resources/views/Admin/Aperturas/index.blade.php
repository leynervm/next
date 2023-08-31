<x-app-layout>

    <div class="mt-3 flex gap-2">
        @livewire('admin.aperturas.create-apertura')
    </div>

    <x-title-next titulo="Historial aperturas" class="mt-5" />



    <div class="mt-3">
        @livewire('admin.aperturas.show-aperturas')
    </div>

</x-app-layout>

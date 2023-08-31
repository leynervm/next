<x-app-layout>

    <div class="mt-3">
        @livewire('soporte::priorities.create-priority')
    </div>

    <x-title-next titulo="Tipos prioridad" class="mt-5" />

    <div class="mt-3">
        @livewire('soporte::priorities.show-priorities')
    </div>

</x-app-layout>

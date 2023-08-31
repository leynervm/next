<x-app-layout>

    <div class="mt-3">
        @livewire('soporte::equipos.create-equipo')
    </div>

    <x-title-next titulo="Equipos" class="mt-5" />

    <div class="mt-3">
        @livewire('soporte::equipos.show-equipos')
    </div>

</x-app-layout>

<x-admin-layout>

    <div class="mt-3 flex flex-wrap gap-2">
        @livewire('soporte::atenciones.create-atencion')
        <x-link-next href="{{ route('typeatencions') }}" titulo="Tipos Atención">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18" />
                <path d="M7 12h10" />
                <path d="M10 18h4" />
            </svg>
        </x-link-next>
    </div>

    <x-title-next titulo="Atenciones" class="mt-5" />

    <div class="mt-3">
        @livewire('soporte::atenciones.show-atenciones')
    </div>

</x-admin-layout>

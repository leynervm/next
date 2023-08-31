<x-app-layout>
    <div class="flex flex-wrap gap-2 mt-3">
        <x-link-next href="{{ route('equipos') }}" titulo="Equipos">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8" />
                <path d="M10 19v-3.96 3.15" />
                <path d="M7 19h5" />
                <rect width="6" height="10" x="16" y="12" rx="2" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('marcas') }}" titulo="Marcas">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m16 6 4 14" />
                <path d="M12 6v14" />
                <path d="M8 8v12" />
                <path d="M4 4v16" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('status') }}" titulo="Status">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="5" width="6" height="6" rx="1" />
                <path d="m3 17 2 2 4-4" />
                <path d="M13 6h8" />
                <path d="M13 12h8" />
                <path d="M13 18h8" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('atenciones') }}" titulo="Atenciones">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 17 2 2 4-4" />
                <path d="m3 7 2 2 4-4" />
                <path d="M13 6h8" />
                <path d="M13 12h8" />
                <path d="M13 18h8" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('typeatencions') }}" titulo="Tipos Atención">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18" />
                <path d="M7 12h10" />
                <path d="M10 18h4" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('entornos') }}" titulo="Entornos Atención">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v8" />
                <path d="m4.93 10.93 1.41 1.41" />
                <path d="M2 18h2" />
                <path d="M20 18h2" />
                <path d="m19.07 10.93-1.41 1.41" />
                <path d="M22 22H2" />
                <path d="m8 6 4-4 4 4" />
                <path d="M16 18a4 4 0 0 0-8 0" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('priorities') }}" titulo="Prioridades Atención">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 8 4-4 4 4" />
                <path d="M7 4v16" />
                <path d="M11 12h4" />
                <path d="M11 16h7" />
                <path d="M11 20h10" />
            </svg>
        </x-link-next>
    </div>

    <x-title-next titulo="Condiciones de atención" class="mt-5" />

    <div class="mt-3 w-full">

        @livewire('soporte::condiciones.show-condiciones')
        {{-- <livewire:{module-lower-name}::condiciones.show-condiciones /> --}}

    </div>

    <x-title-next titulo="Tipo de notificaciones" class="mt-5" />

    <div class="mt-3 w-full">

        {{-- @livewire('soporte::tiponotificaciones.create-tipo-notificacion') --}}
        @livewire('soporte::tiponotificaciones.show-tipo-notificaciones')

    </div>

</x-app-layout>

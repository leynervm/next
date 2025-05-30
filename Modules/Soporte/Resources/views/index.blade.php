<x-admin-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="SOPORTE TÉCNICO" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 10 2 2 4-4" />
                    <rect width="20" height="14" x="2" y="3" rx="2" />
                    <path d="M12 17v4" />
                    <path d="M8 21h8" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="flex flex-wrap gap-2 mt-3">
        <x-link-next href="{{ route('admin.soporte.tickets.selectarea') }}" titulo="Registrar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="5" y2="19" />
                <line x1="5" x2="19" y1="12" y2="12" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('admin.soporte.tickets') }}" titulo="Tickets">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m8 11 2 2 4-4" />
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('admin.soporte.centerservices') }}" titulo="Centros Atención">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                <path d="m9 12 2 2 4-4" />
            </svg>
        </x-link-next>
        <x-link-next href="#" titulo="Listado de Servicios">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m8 11 2 2 4-4" />
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.soporte.typeequipos') }}" titulo="Tipos de Equipos">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8" />
                <path d="M10 19v-3.96 3.15" />
                <path d="M7 19h5" />
                <rect width="6" height="10" x="16" y="12" rx="2" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('admin.soporte.status') }}" titulo="Estados del Ticket">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="5" width="6" height="6" rx="1" />
                <path d="m3 17 2 2 4-4" />
                <path d="M13 6h8" />
                <path d="M13 12h8" />
                <path d="M13 18h8" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('admin.soporte.atenciones') }}" titulo="Atenciones">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 17 2 2 4-4" />
                <path d="m3 7 2 2 4-4" />
                <path d="M13 6h8" />
                <path d="M13 12h8" />
                <path d="M13 18h8" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('admin.soporte.typeatencions') }}" titulo="Tipos Atención">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18" />
                <path d="M7 12h10" />
                <path d="M10 18h4" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('admin.soporte.entornos') }}" titulo="Entornos Atención">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M13.13 14.833a5.002 5.002 0 0 1 -1.13 -.833a5 5 0 0 0 -7 0v-9a5 5 0 0 1 7 0a5 5 0 0 0 7 0v8">
                </path>
                <path d="M5 21v-7"></path>
                <path d="M16 22l5 -5"></path>
                <path d="M21 21.5v-4.5h-4.5"></path>
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('admin.soporte.priorities') }}" titulo="Prioridades Atención">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 8 4-4 4 4" />
                <path d="M7 4v16" />
                <path d="M11 12h4" />
                <path d="M11 16h7" />
                <path d="M11 20h10" />
            </svg>
        </x-link-next>
        <x-link-next href="{{ route('admin.soporte.conditions') }}" titulo="Condiciones Atención">
            {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m16 6 4 14" />
                <path d="M12 6v14" />
                <path d="M8 8v12" />
                <path d="M4 4v16" />
            </svg> --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                <path d="M12 9h.01" />
                <path d="M11 12h1v4h1" />
            </svg>
        </x-link-next>
    </div>

    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('soporte.name') !!}
    </p>

    <x-title-next titulo="Tipo de notificaciones" class="mt-5" />

    <div class="mt-3 w-full">
        @livewire('soporte::tiponotificaciones.show-tipo-notificaciones')
    </div>
</x-admin-layout>

<x-app-layout>

    <div class="flex flex-wrap gap-2 mt-3">
        <x-link-next href="{{ route('admin.almacen.productos') }}" titulo="Productos">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m16 16 2 2 4-4" />
                <path
                    d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14" />
                <path d="M16.5 9.4 7.55 4.24" />
                <polyline points="3.29 7 12 12 20.71 7" />
                <line x1="12" x2="12" y1="22" y2="12" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Ingreso productos">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="8" height="8" x="2" y="2" rx="2" />
                <path d="M14 2c1.1 0 2 .9 2 2v4c0 1.1-.9 2-2 2" />
                <path d="M20 2c1.1 0 2 .9 2 2v4c0 1.1-.9 2-2 2" />
                <path d="M10 18H5c-1.7 0-3-1.3-3-3v-1" />
                <polyline points="7 21 10 18 7 15" />
                <rect width="8" height="8" x="14" y="14" rx="2" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Listado partes">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 17 2 2 4-4" />
                <path d="m3 7 2 2 4-4" />
                <path d="M13 6h8" />
                <path d="M13 12h8" />
                <path d="M13 18h8" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Inventario herramientas">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                <path d="M12 11h4" />
                <path d="M12 16h4" />
                <path d="M8 11h.01" />
                <path d="M8 16h.01" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Inventario patrimonio">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                <path d="M12 11h4" />
                <path d="M12 16h4" />
                <path d="M8 11h.01" />
                <path d="M8 16h.01" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Kardex">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ">
                <path d=" m3 16 4 4 4-4" />
            <path d="M7 20V4" />
            <path d="m21 8-4-4-4 4" />
            <path d="M17 4v16" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.almacen.typegarantias') }}" titulo="Tipo garantía">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                <path d="m9 12 2 2 4-4" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.almacen.almacenareas') }}" titulo="Áreas & estantes">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m4 6 3-3 3 3" />
                <path d="M7 17V3" />
                <path d="m14 6 3-3 3 3" />
                <path d="M17 17V3" />
                <path d="M4 21h16" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.almacen.units') }}" titulo="Unidades Medida">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path
                    d="M21.3 8.7 8.7 21.3c-1 1-2.5 1-3.4 0l-2.6-2.6c-1-1-1-2.5 0-3.4L15.3 2.7c1-1 2.5-1 3.4 0l2.6 2.6c1 1 1 2.5 0 3.4Z" />
                <path d="m7.5 10.5 2 2" />
                <path d="m10.5 7.5 2 2" />
                <path d="m13.5 4.5 2 2" />
                <path d="m4.5 13.5 2 2" />
            </svg>
        </x-link-next>

        {{-- <x-link-next href="#" titulo="Estantes almmacén">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 2h10" />
                <path d="M5 6h14" />
                <rect width="18" height="12" x="3" y="10" rx="2" />
            </svg>
        </x-link-next> --}}
    </div>

    <x-title-next titulo="Almacén" class="mt-5" />

    <div class="mt-3">
        @livewire('almacen::almacens.show-almacens')
    </div>

</x-app-layout>

<x-app-layout>

    <div class="flex flex-wrap gap-2 mt-3">
        @livewire('almacen::almacens.create-almacen')
        <x-link-next href="{{ route('admin.almacen') }}" titulo="Menú Principal Almacén">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                {{-- <polygon points="12 2 2 7 12 12 22 7 12 2" />
                <polyline points="2 17 12 22 22 17" />
                <polyline points="2 12 12 17 22 12" /> --}}

                <path d="M13 10L22 6L13 2L4 6L13 10Z" />
                <path d="M17.5 4L8.5 8" />
                <path stroke="none" fill="currentColor"
                    d="M22 18L22.3046 18.6854L22.75 18.4874V18H22ZM13 22L12.6954 22.6854C12.8893 22.7715 13.1107 22.7715 13.3046 22.6854L13 22ZM6.25 19L6.5546 18.3146L6.40916 18.25H6.25V19ZM22.75 18V6H21.25V18H22.75ZM4.75 10.5V6H3.25V10.5H4.75ZM13.75 22V10H12.25V22H13.75ZM21.6954 17.3146L12.6954 21.3146L13.3046 22.6854L22.3046 18.6854L21.6954 17.3146ZM6.25 18.25H2V19.75H6.25V18.25ZM13.3046 21.3146L6.5546 18.3146L5.9454 19.6854L12.6954 22.6854L13.3046 21.3146Z" />
                <path d="M2 13H6" />
                <path d="M2 16H6" />
            </svg>
        </x-link-next>
    </div>

    <x-title-next titulo="Sucursales - Almacén" class="mt-5" />

    <div class="mt-3">
        @livewire('almacen::almacens.show-almacens')
    </div>
</x-app-layout>

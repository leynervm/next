<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="PROVEEDORES" route="admin.proveedores">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 17h4V5H2v12h3" />
                    <path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5" />
                    <path d="M14 17h1" />
                    <circle cx="7.5" cy="17.5" r="2.5" />
                    <circle cx="17.5" cy="17.5" r="2.5" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>

        <x-link-breadcrumb text="TIPOS PROVEEDORES" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 10l-2 -6" />
                    <path d="M7 10l2 -6" />
                    <path
                        d="M10.5 20h-3.256a3 3 0 0 1 -2.965 -2.544l-1.255 -7.152a2 2 0 0 1 1.977 -2.304h13.999a2 2 0 0 1 1.977 2.304l-.133 .757" />
                    <path d="M13.596 12.794a2 2 0 0 0 -3.377 2.116" />
                    <path
                        d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>


    <div class="mt-3 flex gap-2">
        @livewire('admin.proveedortypes.create-proveedortype')
    </div>

    {{-- <x-title-next titulo="Tipo proveedores" class="mt-5" /> --}}

    <div class="mt-3">
        @livewire('admin.proveedortypes.show-proveedortypes')
    </div>

</x-app-layout>

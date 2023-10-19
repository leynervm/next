<x-app-layout>

    <div class="mt-3 flex gap-2">
        <x-link-next href="{{ route('admin.almacen.compras.create') }}" titulo="Registrar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="5" y2="19" />
                <line x1="5" x2="19" y1="12" y2="12" />
            </svg>
        </x-link-next>
    </div>

    <x-title-next titulo="Historial compras" class="mt-5" />
    <div class="mt-3">
        <livewire:almacen.compras.show-compras />
    </div>

</x-app-layout>
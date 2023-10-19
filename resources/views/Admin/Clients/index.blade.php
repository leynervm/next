<x-app-layout>
    <div class="flex flex-wrap gap-2 mt-3">

        @livewire('admin.clients.create-client')

        <x-link-next href="{{ route('admin.channelsales') }}" titulo="Canales Venta">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8.3 10a.7.7 0 0 1-.626-1.079L11.4 3a.7.7 0 0 1 1.198-.043L16.3 8.9a.7.7 0 0 1-.572 1.1Z" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <circle cx="17.5" cy="17.5" r="3.5" />
            </svg>
        </x-link-next>
    </div>

    <x-title-next titulo="Listado clientes" class="mt-5" />
    <div class="mt-3">
        @livewire('admin.clients.show-clients')
    </div>

</x-app-layout>

<x-app-layout>

    <div class="mt-3 flex gap-2 flex-wrap">

        @livewire('almacen::categories.create-category')

        <x-link-next href="{{ route('admin.almacen.subcategorias') }}" titulo="Subcategorías">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                <path d="m9 12 2 2 4-4" />
            </svg>
        </x-link-next>

    </div>

    <x-title-next titulo="Categorías" class="mt-5" />

    <div class="mt-3">
        @livewire('almacen::categories.show-categories')
    </div>

</x-app-layout>

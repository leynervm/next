<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="OFERTAS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 7l.867 12.143a2 2 0 0 0 2 1.857h10.276a2 2 0 0 0 2 -1.857l.867 -12.143h-16z" />
                    <path d="M8.5 7c0 -1.653 1.5 -4 3.5 -4s3.5 2.347 3.5 4" />
                    <path
                        d="M9.5 17c.413 .462 1 1 2.5 1s2.5 -.897 2.5 -2s-1 -1.5 -2.5 -2s-2 -1.47 -2 -2c0 -1.104 1 -2 2 -2s1.5 0 2.5 1" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="w-full p-1">
        @if (count($ofertas) > 0)
            <h1 class="font-medium uppercase text-xs py-3 text-colorsubtitleform">
                Descubre nuestras ofertas que tenemos para tí</h1>
            <div class="w-full flex justify-end py-2">
                {{ $ofertas->onEachSide(0)->links('pagination::pagination-default') }}
            </div>

            <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(220px,1fr))] gap-1 xs:gap-0">
                @foreach ($ofertas as $item)
                    <x-card-producto-oferta :producto="$item" />
                @endforeach
            </div>
        @else
            <p class="text-xs text-center text-colorsubtitleform">
                ACTUALMENTE NO TENEMOS OFERTAS DISPONIBLES...</p>

            <x-link-web text="VER TODOS LOS PRODUCTOS" class="mx-auto" href="{{ route('productos') }}" />
        @endif
    </div>
</x-app-layout>

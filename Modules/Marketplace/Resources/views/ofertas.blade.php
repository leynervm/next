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

    <div class="w-full">
        <div class="relative w-full m-0 mb-10">
            <h1 class="text-colorsubtitleform flex items-start text-xl leading-normal px-0 py-4 gap-2.5 bg-white">
                Descubre nuestras ofertas que tenemos para tí</h1>
            <div class="w-full m-0 relative">
                <div class="w-full relative overflow-hidden border-t border-borderminicard mb-9">
                    <div
                        class="flex max-h-full bg-fondominicard active w-full float-left -mr-[100%] transition-transform ease-in-out duration-700">
                        @foreach ($ofertas as $item)
                            <x-card-producto-oferta :producto="$item" :empresa="$empresa" :moneda="$moneda" :pricetype="$pricetype" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

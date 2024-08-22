<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="PRODUCTOS" active>
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

    {{-- <div class="flex flex-wrap gap-2">
    </div> --}}
    @if ($empresa && $empresa->isDefault())
        <div class="w-full p-1 sm:p-3">
            <livewire:modules.marketplace.productos.show-productos :empresa="$empresa" :moneda="$moneda"
                :pricetype="$pricetype" />
        </div>
    @endif
</x-app-layout>

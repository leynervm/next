<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="LISTA DESEOS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9.73 17.753l-5.23 -5.181a5 5 0 1 1 7.5 -6.566a5 5 0 0 1 8.563 5.041" />
                    <path stroke-width="1.5"
                        d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="w-full">
        <livewire:modules.marketplace.wishlist.show-wishlists :pricetype="$pricetype" />
    </div>
</x-app-layout>

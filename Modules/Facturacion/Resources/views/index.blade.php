<x-admin-layout>
    <x-slot name="breadcrumb">
        @can('admin.facturacion')
            <x-link-breadcrumb text="FACTURACIÓN" route="admin.facturacion">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M9.72727 2C6.46607 2 4.83546 2 3.70307 2.79784C3.37862 3.02643 3.09058 3.29752 2.8477 3.60289C2 4.66867 2 6.20336 2 9.27273V11.8182C2 14.7814 2 16.2629 2.46894 17.4462C3.22281 19.3486 4.81714 20.8491 6.83836 21.5586C8.09563 22 9.66981 22 12.8182 22C14.6173 22 15.5168 22 16.2352 21.7478C17.3902 21.3424 18.3012 20.4849 18.732 19.3979C19 18.7217 19 17.8751 19 16.1818V15.5" />
                        <path d="M15 7.5C15 7.5 15.5 7.5 16 8.5C16 8.5 17.5882 6 19 5.5" />
                        <path
                            d="M22 7C22 9.76142 19.7614 12 17 12C14.2386 12 12 9.76142 12 7C12 4.23858 14.2386 2 17 2C19.7614 2 22 4.23858 22 7Z" />
                        <path
                            d="M2 12C2 13.8409 3.49238 15.3333 5.33333 15.3333C5.99912 15.3333 6.78404 15.2167 7.43137 15.3901C8.00652 15.5442 8.45576 15.9935 8.60988 16.5686C8.78333 17.216 8.66667 18.0009 8.66667 18.6667C8.66667 20.5076 10.1591 22 12 22" />
                    </svg>
                </x-slot>
            </x-link-breadcrumb>
        @endcan

        <x-link-breadcrumb text="COMPROBANTES ELECTRÓNICOS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M11 22L9.52157 19.6461C8.49181 18.0065 7.97692 17.1867 7.16053 17.0393C5.83152 16.7993 4.45794 17.7045 3.5 18.509" />
                    <path
                        d="M3.5 9V16.0279C3.5 17.2307 3.5 17.8321 3.7987 18.3154C4.0974 18.7987 4.63531 19.0677 5.71115 19.6056L9.65542 21.5777C10.4962 21.9981 10.5043 22 11.4443 22H14.5C17.3284 22 18.7426 22 19.6213 21.1213C20.5 20.2426 20.5 18.8284 20.5 16V9C20.5 6.17157 20.5 4.75736 19.6213 3.87868C18.7426 3 17.3284 3 14.5 3H9.5C6.67157 3 5.25736 3 4.37868 3.87868C3.5 4.75736 3.5 6.17157 3.5 9Z" />
                    <path d="M12 9H8M16 14H8" />
                    <path d="M17 2V4M12 2V4M7 2V4" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    @can('admin.facturacion.guias')
        <div class="flex flex-wrap gap-2 mt-3">
            <x-link-next href="{{ route('admin.facturacion.guias') }}" titulo="Guías remisión">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13.5 20C13.5 20 14.5 20 15.5 22C15.5 22 18.6765 17 21.5 16" />
                    <path d="M7 16H11M7 11H15" />
                    <path
                        d="M6.5 3.5C4.9442 3.54667 4.01661 3.71984 3.37477 4.36227C2.49609 5.24177 2.49609 6.6573 2.49609 9.48836L2.49609 15.9944C2.49609 18.8255 2.49609 20.241 3.37477 21.1205C4.25345 22 5.66767 22 8.49609 22L10.9961 22M15.4922 3.5C17.048 3.54667 17.9756 3.71984 18.6174 4.36228C19.4961 5.24177 19.4961 6.6573 19.4961 9.48836V13.5" />
                    <path
                        d="M6.49609 3.75C6.49609 2.7835 7.2796 2 8.24609 2H13.7461C14.7126 2 15.4961 2.7835 15.4961 3.75C15.4961 4.7165 14.7126 5.5 13.7461 5.5H8.24609C7.2796 5.5 6.49609 4.7165 6.49609 3.75Z" />
                </svg>
            </x-link-next>
        </div>
    @endcan


    <div class="w-full mt-3">
        @livewire('modules.facturacion.comprobantes.show-comprobantes')
    </div>
</x-admin-layout>

<x-app-layout>

    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="VENTAS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M11.5 21h-2.926a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304h11.339a2 2 0 0 1 1.977 2.304l-.117 .761" />
                    <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
                    <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                    <path d="M20.2 20.2l1.8 1.8" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    @if (session('message'))
        <x-alert :titulo="session('message')->getData()->title" :mensaje="session('message')->getData()->text" :type="session('message')->getData()->type">
            <x-slot name="icono">
                <svg class="w-6 h-6 p-0.5 animate-bounce" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M5.32171 9.68293C7.73539 5.41199 8.94222 3.27651 10.5983 2.72681C11.5093 2.4244 12.4907 2.4244 13.4017 2.72681C15.0578 3.27651 16.2646 5.41199 18.6783 9.68293C21.092 13.9539 22.2988 16.0893 21.9368 17.8293C21.7376 18.7866 21.2469 19.6549 20.535 20.3097C19.241 21.5 16.8274 21.5 12 21.5C7.17265 21.5 4.75897 21.5 3.46496 20.3097C2.75308 19.6549 2.26239 18.7866 2.06322 17.8293C1.70119 16.0893 2.90803 13.9539 5.32171 9.68293Z" />
                    <path
                        d="M12.2422 17V13C12.2422 12.5286 12.2422 12.2929 12.0957 12.1464C11.9493 12 11.7136 12 11.2422 12" />
                    <path d="M11.992 9H12.001" />
                </svg>
            </x-slot>
        </x-alert>
    @endif

    <div class="flex flex-wrap gap-2 mt-3">
        <x-link-next href="{{ route('admin.ventas.create') }}" titulo="Registrar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M12.5 21h-3.926a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304h11.339a2 2 0 0 1 1.977 2.304l-.263 1.708" />
                <path d="M16 19h6" />
                <path d="M19 16v6" />
                <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
            </svg>
        </x-link-next>

        {{-- <x-link-next href="{{ route('admin.ventas.methodpayments') }}" titulo="Formas pago">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M2 11l2.807-3.157A4 4 0 017.797 6.5H8M2 19.5h5.5l4-3s.81-.547 2-1.5c2.5-2 0-5.166-2.5-3.5C8.964 12.857 7 14 7 14" />
                <path d="M8 13.5V7a2 2 0 012-2h10a2 2 0 012 2v6a2 2 0 01-2 2h-6.5" />
                <path d="M18.25 12c.5-1.5.5-2.5 0-4M16 9c.227.5.227 1.5 0 2" />
            </svg>
        </x-link-next> --}}

        <x-link-next href="{{ route('admin.ventas.cobranzas') }}" titulo="Cuentas por cobrar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M18 5.44232C18 5.44232 20 4.43881 21.241 5.45484C21.443 5.6182 21.6952 5.94158 21.8059 6.1793C22 6.59611 22 7.0003 22 7.80867V18.8176C22 19.8241 20.96 20.5154 20 20.2907C19.0803 20.0754 18.0659 19.9561 17 19.9561C15.0829 19.9561 13.3325 20.342 12 20.9781C10.6675 21.6141 8.91707 22.0001 7 22.0001C5.93408 22.0001 4.91969 21.8808 4 21.6655C3.4088 21.5271 3.11319 21.4579 2.75898 21.1715C2.55696 21.0081 2.30483 20.6847 2.19412 20.447C2 20.0302 2 19.626 2 18.8176V7.80867C2 6.80219 3.04003 6.1109 4 6.33561C4.77473 6.51696 5.61667 6.63021 6.5 6.6614" />
                <path
                    d="M14.5 13.5C14.5 14.8807 13.3807 16 12 16C10.6193 16 9.5 14.8807 9.5 13.5C9.5 12.1193 10.6193 11 12 11C13.3807 11 14.5 12.1193 14.5 13.5Z" />
                <path d="M5.5 14.5L5.5 14.509" />
                <path d="M18.5 12.4922L18.5 12.5012" />
                <path d="M9.5 5.5C9.99153 6.0057 11.2998 8 12 8M14.5 5.5C14.0085 6.0057 12.7002 8 12 8M12 8V2" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Ventas por internet">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 7l.867 12.143a2 2 0 0 0 2 1.857h10.276a2 2 0 0 0 2 -1.857l.867 -12.143h-16z" />
                <path d="M8.5 7c0 -1.653 1.5 -4 3.5 -4s3.5 2.347 3.5 4" />
                <path
                    d="M9.5 17c.413 .462 1 1 2.5 1s2.5 -.897 2.5 -2s-1 -1.5 -2.5 -2s-2 -1.47 -2 -2c0 -1.104 1 -2 2 -2s1.5 0 2.5 1" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.facturacion.typecomprobantes') }}" titulo="Tipos de comprobantes">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M12 2H12.7727C16.0339 2 17.6645 2 18.7969 2.79784C19.1214 3.02643 19.4094 3.29752 19.6523 3.60289C20.5 4.66867 20.5 6.20336 20.5 9.27273V11.8182C20.5 14.7814 20.5 16.2629 20.0311 17.4462C19.2772 19.3486 17.6829 20.8491 15.6616 21.5586C14.4044 22 12.8302 22 9.68182 22C7.88275 22 6.98322 22 6.26478 21.7478C5.10979 21.3424 4.19875 20.4849 3.76796 19.3979C3.5 18.7217 3.5 17.8751 3.5 16.1818V11.5" />
                <path
                    d="M20.5 12C20.5 13.8409 19.0076 15.3333 17.1667 15.3333C16.5009 15.3333 15.716 15.2167 15.0686 15.3901C14.4935 15.5442 14.0442 15.9935 13.8901 16.5686C13.7167 17.216 13.8333 18.0009 13.8333 18.6667C13.8333 20.5076 12.3409 22 10.5 22" />
                <path
                    d="M7.70569 9.44141C8.45931 10.1862 9.68117 10.1862 10.4348 9.44141C11.1884 8.69662 11.1884 7.48908 10.4348 6.74429L8.7291 5.05859C8.06295 4.40025 7.03095 4.32384 6.27987 4.82935M6.29431 2.55859C5.54069 1.8138 4.31883 1.8138 3.56521 2.55859C2.8116 3.30338 2.8116 4.51092 3.56521 5.25571L5.2709 6.94141C5.94932 7.61187 7.00718 7.67878 7.76133 7.14213" />
            </svg>
        </x-link-next>
    </div>

    {{-- <x-title-next titulo="Resumen ventas" class="mt-5" /> --}}

    <livewire:modules.ventas.ventas.show-ventas>

</x-app-layout>

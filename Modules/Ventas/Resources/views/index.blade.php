<x-app-layout>

    <div class="flex flex-wrap gap-2 mt-3">
        <x-link-next href="{{ route('admin.ventas.create') }}" titulo="Registrar">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-bag-plus" width="24"
                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path
                    d="M12.5 21h-3.926a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304h11.339a2 2 0 0 1 1.977 2.304l-.263 1.708">
                </path>
                <path d="M16 19h6"></path>
                <path d="M19 16v6"></path>
                <path d="M9 11v-5a3 3 0 0 1 6 0v5"></path>
            </svg>
        </x-link-next>

        {{-- <x-link-next href="#" titulo="Listado ventas">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-bag-search"
                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path
                    d="M11.5 21h-2.926a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304h11.339a2 2 0 0 1 1.977 2.304l-.117 .761">
                </path>
                <path d="M9 11v-5a3 3 0 0 1 6 0v5"></path>
                <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                <path d="M20.2 20.2l1.8 1.8"></path>
            </svg>
        </x-link-next> --}}

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
                    d="M16 13c-2.761 0-5-1.12-5-2.5S13.239 8 16 8s5 1.12 5 2.5-2.239 2.5-5 2.5zM11 14.5c0 1.38 2.239 2.5 5 2.5s5-1.12 5-2.5M3 9.5C3 10.88 5.239 12 8 12c1.126 0 2.165-.186 3-.5M3 13c0 1.38 2.239 2.5 5 2.5 1.126 0 2.164-.186 3-.5" />
                <path
                    d="M3 5.5v11C3 17.88 5.239 19 8 19c1.126 0 2.164-.186 3-.5M13 8.5v-3M11 10.5v8c0 1.38 2.239 2.5 5 2.5s5-1.12 5-2.5v-8" />
                <path d="M8 8C5.239 8 3 6.88 3 5.5S5.239 3 8 3s5 1.12 5 2.5S10.761 8 8 8z" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Ventas por internet">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-shopee" width="24"
                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M4 7l.867 12.143a2 2 0 0 0 2 1.857h10.276a2 2 0 0 0 2 -1.857l.867 -12.143h-16z"></path>
                <path d="M8.5 7c0 -1.653 1.5 -4 3.5 -4s3.5 2.347 3.5 4"></path>
                <path
                    d="M9.5 17c.413 .462 1 1 2.5 1s2.5 -.897 2.5 -2s-1 -1.5 -2.5 -2s-2 -1.47 -2 -2c0 -1.104 1 -2 2 -2s1.5 0 2.5 1">
                </path>
            </svg>
        </x-link-next>
    </div>

    <x-title-next titulo="Resumen ventas" class="mt-5" />

    @livewire('ventas.ventas.show-ventas')

</x-app-layout>

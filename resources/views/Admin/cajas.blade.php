<x-app-layout>

    <div class="flex flex-wrap gap-2 mt-3">

        <x-link-next href="{{ route('admin.cajas.aperturas') }}" titulo="Aperturas">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M14 20H6a2 2 0 01-2-2V6a2 2 0 012-2h12a2 2 0 012 2v6M12 9V4M21.167 18.5h.233a.6.6 0 01.6.6v2.3a.6.6 0 01-.6.6h-3.8a.6.6 0 01-.6-.6v-2.3a.6.6 0 01.6-.6h.233m3.334 0v-1.75c0-.583-.334-1.75-1.667-1.75s-1.667 1.167-1.667 1.75v1.75m3.334 0h-3.334" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.cajas.administrar') }}" titulo="Administrar Cajas">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 17 2 2 4-4" />
                <path d="m3 7 2 2 4-4" />
                <path d="M13 6h8" />
                <path d="M13 12h8" />
                <path d="M13 18h8" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Movimientos">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 19l3 3 5-5M17 14V4m0 0l3 3m-3-3l-3 3M7 4v16m0 0l3-3m-3 3l-3-3" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.cajas.conceptos') }}" titulo="Concepto Cajas">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zM13.5 9.17a3 3 0 100 5.659" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.ventas.cobranzas') }}" titulo="Cuentas Cobrar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M16 13c-2.761 0-5-1.12-5-2.5S13.239 8 16 8s5 1.12 5 2.5-2.239 2.5-5 2.5zM11 14.5c0 1.38 2.239 2.5 5 2.5s5-1.12 5-2.5M3 9.5C3 10.88 5.239 12 8 12c1.126 0 2.165-.186 3-.5M3 13c0 1.38 2.239 2.5 5 2.5 1.126 0 2.164-.186 3-.5" />
                <path
                    d="M3 5.5v11C3 17.88 5.239 19 8 19c1.126 0 2.164-.186 3-.5M13 8.5v-3M11 10.5v8c0 1.38 2.239 2.5 5 2.5s5-1.12 5-2.5v-8" />
                <path d="M8 8C5.239 8 3 6.88 3 5.5S5.239 3 8 3s5 1.12 5 2.5S10.761 8 8 8z" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.cajas.methodpayments') }}" titulo="Método Pagos">
            {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M17 15V17.8C17 18.9201 17 19.4802 16.782 19.908C16.5903 20.2843 16.2843 20.5903 15.908 20.782C15.4802 21 14.9201 21 13.8 21H8.2C7.0799 21 6.51984 21 6.09202 20.782C5.71569 20.5903 5.40973 20.2843 5.21799 19.908C5 19.4802 5 18.9201 5 17.8V5.57143C5 5.04025 5 4.77465 5.05014 4.55496C5.2211 3.80597 5.80597 3.2211 6.55496 3.05014C6.77465 3 7.04025 3 7.57143 3H11M10 18H12M19 4.50003C18.5 4.37601 17.6851 4.37145 17 4.37601M17 4.37601C16.7709 4.37754 16.9094 4.3678 16.6 4.37601C15.7926 4.4012 15.0016 4.73678 15 5.68753C14.9982 6.70037 16 7.00003 17 7.00003C18 7.00003 19 7.23123 19 8.31253C19 9.12512 18.1925 9.48118 17.1861 9.59908C16.3861 9.59908 16 9.62503 15 9.50003M17 4.37601L17 3M17 9.5995V11" />
            </svg> --}}
            <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                viewBox="0 0 481.8 481.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                fill="currentColor">
                <path
                    d="M384.759,213.6c-4.7-3.7-46.2-49.4-69.7-70.7c-0.1-0.1-0.1-0.1-0.1-0.2V44.3c0-24.4-19.9-44.3-44.3-44.3h-145.1 c-22.7,0-41.2,18.6-41.2,41.2V272c0,24.4,19.9,44.3,44.3,44.3h42.2h10.7c1.8,0,3.3-1.6,3.1-3.4l-2.3-19.6 c-0.2-1.6-1.5-2.7-3.1-2.7h-3.9h-2.4c-1.7,0-3.1-1.4-3.1-3.1V25.3c0-1.7,1.4-3.1,3.1-3.1h98c9.9,0,18,8.1,18,18v214.2v21.1 c0,6.8-4.7,12.6-11,14.4c-0.1,0-0.1,0-0.2,0c-10.7-5.8-23.3-30.6-25.2-34.5c-0.2-0.3-0.3-0.7-0.3-1c-0.7-6.3-7.8-67.3-40.3-67.3 c-0.9,0-1.8,0-2.7,0.1c0,0-26,2.2-4.5,76.3c0,0.2,0.1,0.3,0.1,0.5l9.5,82.1c0,0.1,0,0.2,0.1,0.4c0.8,3.1,11.6,45,39.5,74.7 c0.5,0.6,0.8,1.3,0.8,2.1v7.1c0,0.2-0.1,0.3-0.3,0.3h-2.5c-6.1,0-11.1,5-11.1,11.1v28.9c0,6.1,5,11.1,11.1,11.1h117.7 c6.1,0,11.1-5,11.1-11.1V442c0-6.1-5-11.1-11.1-11.1c-0.2,0-0.3-0.1-0.3-0.3c3.9-36.2,17.9-160,25.4-169.7c0.2-0.3,0.4-0.6,0.5-1 C396.559,255.4,403.059,227.9,384.759,213.6z M130.659,287.7c0,1.6-1.3,2.8-2.8,2.8c-9.8,0-17.8-8-17.8-17.8V40 c0-9.8,8-17.8,17.8-17.8c1.6,0,2.8,1.3,2.8,2.8V287.7z" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.cajas.cuentas') }}" titulo="Cuentas pago">
            {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 9V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2h8M22 9H6m16 0v2" />
                <path
                    d="M18.992 14.125l2.556.649c.266.068.453.31.445.584C21.821 21.116 18.5 22 18.5 22s-3.321-.884-3.493-6.642a.588.588 0 01.445-.584l2.556-.649c.323-.082.661-.082.984 0z" />
            </svg> --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M22 9v8a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2h16a2 2 0 012 2v2zm0 0H6M16.5 13.382a1.5 1.5 0 110 2.236M16.5 13.382a1.5 1.5 0 100 2.236" />
            </svg>
        </x-link-next>

    </div>

    <x-title-next titulo="Resumen caja" class="mt-5" />

    <div class="mt-3">
        {{-- @livewire('almacen::almacens.show-almacens') --}}
    </div>

</x-app-layout>

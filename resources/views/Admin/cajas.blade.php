<x-app-layout>

    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="CAJAS" route="admin.cajas">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="m14,18c4.4183,0 8,-3.5817 8,-8c0,-4.41828 -3.5817,-8 -8,-8c-4.41828,0 -8,3.58172 -8,8c0,4.4183 3.58172,8 8,8z" />
                    <path
                        d="m3.15657,11c-0.73134,1.1176 -1.15657,2.4535 -1.15657,3.8888c0,3.9274 3.18378,7.1112 7.11116,7.1112c1.43534,0 2.77124,-0.4252 3.88884,-1.1566" />
                    <path
                        d="m14,7c-1.1046,0 -2,0.67157 -2,1.5c0,0.82843 0.8954,1.5 2,1.5c1.1046,0 2,0.6716 2,1.5c0,0.8284 -0.8954,1.5 -2,1.5m0,-6c0.8708,0 1.6116,0.4174 1.8862,1m-1.8862,5c-0.8708,0 -1.6116,-0.4174 -1.8862,-1m1.8862,1" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>

        <x-link-breadcrumb text="MOVIMIENTOS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 21H10C6.70017 21 5.05025 21 4.02513 19.9749C3 18.9497 3 17.2998 3 14V3" />
                    <path d="M10 7L12 7" />
                    <path d="M18 7L20 7" />
                    <path d="M8 15L10 15" />
                    <path d="M16 15L18 15" />
                    <path d="M10 5L10 17" />
                    <path d="M18 5L18 17" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    @if (session('message'))
        <x-alert :titulo="session('message')->getData()->title" :mensaje="session('message')->getData()->text" type="warning">
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

        <x-link-next href="{{ route('admin.cajas.aperturas') }}" titulo="Apertura Cajas">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M16.6667 14L7.33333 14C5.14718 14 4.0541 14 3.27927 14.5425C2.99261 14.7433 2.74327 14.9926 2.54254 15.2793C2 16.0541 2 17.1472 2 19.3333C2 20.4264 2 20.9729 2.27127 21.3604C2.37164 21.5037 2.4963 21.6284 2.63963 21.7287C3.02705 22 3.57359 22 4.66667 22L19.3333 22C20.4264 22 20.9729 22 21.3604 21.7287C21.5037 21.6284 21.6284 21.5037 21.7287 21.3604C22 20.9729 22 20.4264 22 19.3333C22 17.1472 22 16.0541 21.4575 15.2793C21.2567 14.9926 21.0074 14.7433 20.7207 14.5425C19.9459 14 18.8528 14 16.6667 14Z" />
                <path
                    d="M20 14L19.593 10.3374C19.311 7.79863 19.1699 6.52923 18.3156 5.76462C17.4614 5 16.1842 5 13.6297 5L10.3703 5C7.81585 5 6.53864 5 5.68436 5.76462C4.83009 6.52923 4.68904 7.79862 4.40695 10.3374L4 14" />
                <path d="M11.5 2H14M16.5 2H14M14 2V5" />
                <path
                    d="M9 17.5L9.99615 18.1641C10.3247 18.3831 10.7107 18.5 11.1056 18.5H12.8944C13.2893 18.5 13.6753 18.3831 14.0038 18.1641L15 17.5" />
                <path d="M8 8H10" />
            </svg>

        </x-link-next>

        <x-link-next href="{{ route('admin.cajas.administrar') }}" titulo="Administrar Cajas">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.5 22L14.1845 21.5811C13.4733 20.6369 13.2969 19.1944 13.7468 18" />
                <path d="M9.5 22L9.8155 21.5811C10.5267 20.6369 10.7031 19.1944 10.2532 18" />
                <path d="M7 22H17" />
                <path
                    d="M12 7C10.8954 7 10 7.67157 10 8.5C10 9.32843 10.8954 10 12 10C13.1046 10 14 10.6716 14 11.5C14 12.3284 13.1046 13 12 13M12 7C12.8708 7 13.6116 7.4174 13.8862 8M12 7V6M12 13C11.1292 13 10.3884 12.5826 10.1138 12M12 13V14" />
                <path
                    d="M14 2H10C6.72077 2 5.08116 2 3.91891 2.81382C3.48891 3.1149 3.1149 3.48891 2.81382 3.91891C2 5.08116 2 6.72077 2 10C2 13.2792 2 14.9188 2.81382 16.0811C3.1149 16.5111 3.48891 16.8851 3.91891 17.1862C5.08116 18 6.72077 18 10 18H14C17.2792 18 18.9188 18 20.0811 17.1862C20.5111 16.8851 20.8851 16.5111 21.1862 16.0811C22 14.9188 22 13.2792 22 10C22 6.72077 22 5.08116 21.1862 3.91891C20.8851 3.48891 20.5111 3.1149 20.0811 2.81382C18.9188 2 17.2792 2 14 2Z" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.cajas.conceptos') }}" titulo="Concepto Pagos">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zM13.5 9.17a3 3 0 100 5.659" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.cajas.methodpayments') }}" titulo="Formas Pago">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M16 14C16 14.8284 16.6716 15.5 17.5 15.5C18.3284 15.5 19 14.8284 19 14C19 13.1716 18.3284 12.5 17.5 12.5C16.6716 12.5 16 13.1716 16 14Z" />
                <path
                    d="M10 7H16C18.8284 7 20.2426 7 21.1213 7.87868C22 8.75736 22 10.1716 22 13V15C22 17.8284 22 19.2426 21.1213 20.1213C20.2426 21 18.8284 21 16 21H10C6.22876 21 4.34315 21 3.17157 19.8284C2 18.6569 2 16.7712 2 13V11C2 7.22876 2 5.34315 3.17157 4.17157C4.34315 3 6.22876 3 10 3H14C14.93 3 15.395 3 15.7765 3.10222C16.8117 3.37962 17.6204 4.18827 17.8978 5.22354C18 5.60504 18 6.07003 18 7" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.cajas.cuentas') }}" titulo="Cuentas pago">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M12.5 20H10.5C6.74142 20 4.86213 20 3.60746 19.0091C3.40678 18.8506 3.22119 18.676 3.0528 18.4871C2 17.3062 2 15.5375 2 12C2 8.46252 2 6.69377 3.0528 5.5129C3.22119 5.32403 3.40678 5.14935 3.60746 4.99087C4.86213 4 6.74142 4 10.5 4H13.5C17.2586 4 19.1379 4 20.3925 4.99087C20.5932 5.14935 20.7788 5.32403 20.9472 5.5129C21.8394 6.51358 21.9755 7.93642 21.9963 10.5" />
                <path d="M18.5 20L18.5 13M15 16.5H22" />
                <path d="M2 9H22" />
            </svg>
        </x-link-next>

    </div>

    {{-- <x-title-next titulo="Resumen movimientos" class="mt-5" /> --}}

    <div class="mt-3">
        @livewire('admin.cajas.show-movimientos')
    </div>

</x-app-layout>

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

        <x-link-breadcrumb text="CUENTAS PAGO" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M12.5 20H10.5C6.74142 20 4.86213 20 3.60746 19.0091C3.40678 18.8506 3.22119 18.676 3.0528 18.4871C2 17.3062 2 15.5375 2 12C2 8.46252 2 6.69377 3.0528 5.5129C3.22119 5.32403 3.40678 5.14935 3.60746 4.99087C4.86213 4 6.74142 4 10.5 4H13.5C17.2586 4 19.1379 4 20.3925 4.99087C20.5932 5.14935 20.7788 5.32403 20.9472 5.5129C21.8394 6.51358 21.9755 7.93642 21.9963 10.5" />
                    <path d="M18.5 20L18.5 13M15 16.5H22" />
                    <path d="M2 9H22" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div
        class="w-full flex flex-col gap-8 animate__animated animate__fadeIn animate__faster">
        <x-form-card titulo="CUENTAS DE PAGO" widthBefore="before:w-[105px]">
            <div class="w-full flex flex-col gap-3">
                <div class="">
                    @livewire('admin.accountpayments.create-accountpayment')
                </div>
                <div class="">
                    @livewire('admin.accountpayments.show-accountpayments')
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="TIPOS DE BANCOS" widthBefore="before:w-[105px]">
            <div class="w-full flex flex-col gap-3">
                {{-- <div>
                    @livewire('admin.bancos.create-banco')
                </div> --}}
                {{-- <div> --}}
                @livewire('admin.bancos.show-bancos')
                {{-- </div> --}}
            </div>
        </x-form-card>
    </div>
</x-app-layout>

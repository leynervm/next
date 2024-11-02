<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="MIS COMPRAS" route="orders">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M3.06164 15.1933L3.42688 13.1219C3.85856 10.6736 4.0744 9.44952 4.92914 8.72476C5.78389 8 7.01171 8 9.46734 8H14.5327C16.9883 8 18.2161 8 19.0709 8.72476C19.9256 9.44952 20.1414 10.6736 20.5731 13.1219L20.9384 15.1933C21.5357 18.5811 21.8344 20.275 20.9147 21.3875C19.995 22.5 18.2959 22.5 14.8979 22.5H9.1021C5.70406 22.5 4.00504 22.5 3.08533 21.3875C2.16562 20.275 2.4643 18.5811 3.06164 15.1933Z" />
                    <path
                        d="M7.5 8L7.66782 5.98618C7.85558 3.73306 9.73907 2 12 2C14.2609 2 16.1444 3.73306 16.3322 5.98618L16.5 8" />
                    <path d="M15 11C14.87 12.4131 13.5657 13.5 12 13.5C10.4343 13.5 9.13002 12.4131 9 11" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
        <x-link-breadcrumb text="RESUMEN DE ORDEN" active>
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

    <div class="contenedor py-5">
        @if (session('message'))
            <div class="w-full flex gap-2 items-center justify-center mb-5">
                <svg class="inline-block w-6 h-6 bg-primary text-white rounded-full" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 11.917 9.724 16.5 19 7.5"></path>
                </svg>
                <h1 class="text-2xl font-semibold text-primary">
                    ! Gracias por su compra !</h1>
            </div>
        @endif

        <div
            class="w-full text-xs grid grid-cols-1 xs:grid-cols-3 lg:grid-cols-7 xl:grid-cols-7 font-medium gap-3 xl:gap-5">
            <div class="w-full border border-borderminicard p-3 rounded-lg">
                <svg class="w-10 h-10 inline-block text-colorsubtitleform" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M10 3v4a1 1 0 0 1-1 1H5m4 6 2 2 4-4m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z">
                    </path>
                </svg>
                <p class="text-sm text-colorsubtitleform font-semibold">
                    #{{ $order->purchase_number }}</p>

                <p class="text-xs font-medium text-colorsubtitleform">
                    {{ formatDate($order->transaccion->date, 'DD MMM Y') }}</p>
            </div>
            <div class="w-full xs:col-span-2 xl:col-span-2 border border-borderminicard p-3 rounded-lg">
                <span class="inline-block w-10 h-10 text-colorsubtitleform">
                    @if ($order->shipmenttype->isEnviodomicilio())
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33" fill-rule="evenodd"
                            clip-rule="evenodd" fill="currentColor" class="w-full h-full">
                            <path
                                d="M3 7.5C3 6.39543 3.89543 5.5 5 5.5H17C18.1046 5.5 19 6.39543 19 7.5V10.5H24.4338C25.1363 10.5 25.7873 10.8686 26.1488 11.471L28.715 15.748C28.9015 16.0588 29 16.4145 29 16.777V22.5C29 23.6046 28.1046 24.5 27 24.5H25.874C25.4299 26.2252 23.8638 27.5 22 27.5C20.0283 27.5 18.3898 26.0734 18.0604 24.1961C17.753 24.3887 17.3895 24.5 17 24.5H12.874C12.4299 26.2252 10.8638 27.5 9 27.5C7.12577 27.5 5.55261 26.211 5.1187 24.4711C3.91896 24.2875 3 23.2511 3 22V21.5C3 20.9477 3.44772 20.5 4 20.5C4.55228 20.5 5 20.9477 5 21.5V22C5 22.1459 5.06252 22.2773 5.16224 22.3687C5.65028 20.7105 7.18378 19.5 9 19.5C10.8638 19.5 12.4299 20.7748 12.874 22.5H17V16.5V7.5H5V8.5C5 9.05228 4.55228 9.5 4 9.5C3.44772 9.5 3 9.05228 3 8.5V7.5ZM19 15.5V12.5H24.4338L26.2338 15.5H19ZM19 17.5H27V22.5H25.874C25.4299 20.7748 23.8638 19.5 22 19.5C20.8053 19.5 19.7329 20.0238 19 20.8542V17.5ZM22 21.5C23.1046 21.5 24 22.3954 24 23.5C24 24.6046 23.1046 25.5 22 25.5C20.8954 25.5 20 24.6046 20 23.5C20 22.3954 20.8954 21.5 22 21.5ZM7 23.5C7 24.6046 7.89543 25.5 9 25.5C10.1046 25.5 11 24.6046 11 23.5C11 22.3954 10.1046 21.5 9 21.5C7.89543 21.5 7 22.3954 7 23.5ZM2 10.5C1.44772 10.5 1 10.9477 1 11.5C1 12.0523 1.44772 12.5 2 12.5H7C7.55228 12.5 8 12.0523 8 11.5C8 10.9477 7.55228 10.5 7 10.5H2ZM3 13.5C2.44772 13.5 2 13.9477 2 14.5C2 15.0523 2.44772 15.5 3 15.5H7C7.55228 15.5 8 15.0523 8 14.5C8 13.9477 7.55228 13.5 7 13.5H3ZM3 17.5C3 16.9477 3.44772 16.5 4 16.5H7C7.55229 16.5 8 16.9477 8 17.5C8 18.0523 7.55229 18.5 7 18.5H4C3.44772 18.5 3 18.0523 3 17.5Z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33" fill-rule="evenodd"
                            clip-rule="evenodd" fill="currentColor" class="w-full h-full">
                            <path
                                d="M18.4449 14.2024C19.4296 12.8623 20 11.5761 20 10.5C20 8.29086 18.2091 6.5 16 6.5C13.7909 6.5 12 8.29086 12 10.5C12 11.5761 12.5704 12.8623 13.5551 14.2024C14.3393 15.2698 15.2651 16.2081 16 16.8815C16.7349 16.2081 17.6607 15.2698 18.4449 14.2024ZM16.8669 18.7881C18.5289 17.3455 22 13.9227 22 10.5C22 7.18629 19.3137 4.5 16 4.5C12.6863 4.5 10 7.18629 10 10.5C10 13.9227 13.4712 17.3455 15.1331 18.7881C15.6365 19.2251 16.3635 19.2251 16.8669 18.7881ZM5 11.5H8.27078C8.45724 12.202 8.72804 12.8724 9.04509 13.5H5V26.5H10.5V22C10.5 21.4477 10.9477 21 11.5 21H20.5C21.0523 21 21.5 21.4477 21.5 22V26.5H27V13.5H22.9549C23.272 12.8724 23.5428 12.202 23.7292 11.5H27C28.1046 11.5 29 12.3954 29 13.5V26.5C29.5523 26.5 30 26.9477 30 27.5C30 28.0523 29.5523 28.5 29 28.5H3C2.44772 28.5 2 28.0523 2 27.5C2 26.9477 2.44772 26.5 3 26.5V13.5C3 12.3954 3.89543 11.5 5 11.5ZM19.5 23V26.5H12.5V23H19.5ZM17 10.5C17 11.0523 16.5523 11.5 16 11.5C15.4477 11.5 15 11.0523 15 10.5C15 9.94772 15.4477 9.5 16 9.5C16.5523 9.5 17 9.94772 17 10.5ZM19 10.5C19 12.1569 17.6569 13.5 16 13.5C14.3431 13.5 13 12.1569 13 10.5C13 8.84315 14.3431 7.5 16 7.5C17.6569 7.5 19 8.84315 19 10.5Z" />
                        </svg>
                    @endif
                </span>
                <p class="text-sm text-colorsubtitleform font-semibold leading-none">
                    {{ $order->shipmenttype->name }}</p>

                @if ($order->shipmenttype->isEnvioDomicilio())
                    <p class="text-colorsubtitleform text-[10px] leading-3">
                        DIRECCIÓN: {{ $order->direccion->name }}</p>
                    <p class="text-colorsubtitleform text-[10px] leading-3">
                        REF.: {{ $order->direccion->referencia }}</p>
                    <p class="text-colorsubtitleform text-[10px] leading-3">
                        {{ $order->direccion->ubigeo->distrito }},
                        {{ $order->direccion->ubigeo->provincia }},
                        {{ $order->direccion->ubigeo->region }}</p>
                @else
                    <p class="text-colorsubtitleform text-[10px]">
                        {{ $order->entrega->sucursal->name }}</p>
                    <p class="text-colorsubtitleform text-[10px] leading-3">
                        {{ $order->entrega->sucursal->direccion }} <br>
                        {{ $order->entrega->sucursal->ubigeo->distrito }},
                        {{ $order->entrega->sucursal->ubigeo->provincia }},
                        {{ $order->entrega->sucursal->ubigeo->region }}</p>
                    <p class="text-colorsubtitleform text-[10px]">
                        FECHA RECLAMO : {{ formatDate($order->entrega->date, 'DD MMMM Y') }}</p>
                @endif
            </div>
            <div class="w-full lg:col-span-2 border border-borderminicard p-3 rounded-lg">
                <h1 class="text-sm text-colorsubtitleform font-semibold leading-none">RECIBE PEDIDO</h1>
                <p class="text-colorsubtitleform font-medium text-[10px] uppercase">
                    {{ $order->receiverinfo['name'] }} <br>
                    {{ $order->receiverinfo['document'] }}</p>
                <p class="text-colorsubtitleform font-medium text-[10px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block text-green-600 w-4 h-4 p-0.5"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                    {{ formatTelefono($order->receiverinfo['telefono']) }}
                </p>
            </div>
            @if ($order->transaccion)
                <div class="w-full xs:col-span-2 md:col-span-2 border border-borderminicard p-3 rounded-lg">
                    <table class="w-full text-xs">
                        <tbody class="">
                            <tr>
                                <td class="text-colorsubtitleform">Método de Pago</td>
                                <td class="font-medium text-colorlabel text-end">
                                    @if ($order->transaccion->brand == 'visa')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-8 block ml-auto"
                                            viewBox="0 0 88 48" fill="none">
                                            <path
                                                d="M56.9919 10C50.7791 10 45.227 13.1804 45.227 19.0565C45.227 25.7951 55.0738 26.2605 55.0738 29.6459C55.0738 31.0713 53.4198 32.3473 50.5949 32.3473C46.5857 32.3473 43.5893 30.5644 43.5893 30.5644L42.3072 36.494C42.3072 36.494 45.759 38 50.3419 38C57.1345 38 62.4795 34.6634 62.4795 28.687C62.4795 21.5663 52.5917 21.1147 52.5917 17.9726C52.5917 16.8558 53.9494 15.6324 56.7663 15.6324C59.9445 15.6324 62.5376 16.9291 62.5376 16.9291L63.7924 11.2021C63.7924 11.2021 60.9709 10 56.9919 10ZM0.150475 10.4323L0 11.2967C0 11.2967 2.61379 11.7691 4.96788 12.7116C7.99894 13.7922 8.21493 14.4213 8.72539 16.3753L14.2881 37.5542H21.7451L33.2331 10.4323H25.7932L18.4115 28.8726L15.3994 13.2417C15.1231 11.4528 13.7238 10.4323 12.0111 10.4323H0.150475V10.4323ZM36.2247 10.4323L30.3884 37.5542H37.4829L43.2987 10.4322H36.2247V10.4323ZM75.7932 10.4323C74.0825 10.4323 73.1761 11.3368 72.511 12.9175L62.117 37.5542H69.5569L70.9963 33.448H80.0601L80.9355 37.5542H87.5L81.7731 10.4323H75.7932V10.4323ZM76.7608 17.7598L78.9661 27.9372H73.0579L76.7608 17.7598Z"
                                                fill="#1434CB" />
                                        </svg>
                                    @elseif ($order->transaccion->brand == 'mastercard')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-6 block ml-auto"
                                            viewBox="0 0 156 96" fill="none">
                                            <path d="M98.6625 10.2627H56.6626V85.7378H98.6625V10.2627Z"
                                                fill="#FF5F00" />
                                            <path
                                                d="M59.3294 47.9999C59.3227 40.7311 60.9699 33.5561 64.1461 27.018C67.3224 20.4799 71.9445 14.7501 77.6625 10.2624C70.5815 4.69659 62.0775 1.2353 53.1223 0.274141C44.1672 -0.687021 35.1223 0.890695 27.0215 4.82703C18.9206 8.76336 12.0907 14.8995 7.31232 22.534C2.53396 30.1685 0 38.9934 0 47.9999C0 57.0065 2.53396 65.8314 7.31232 73.4659C12.0907 81.1004 18.9206 87.2365 27.0215 91.1728C35.1223 95.1091 44.1672 96.6869 53.1223 95.7257C62.0775 94.7645 70.5815 91.3033 77.6625 85.7374C71.9445 81.2497 67.3225 75.5199 64.1462 68.9818C60.97 62.4437 59.3228 55.2687 59.3294 47.9999Z"
                                                fill="#EB001B" />
                                            <path
                                                d="M155.323 47.9999C155.323 57.0064 152.789 65.8312 148.011 73.4657C143.233 81.1002 136.403 87.2363 128.303 91.1727C120.202 95.1091 111.157 96.6868 102.202 95.7257C93.2473 94.7645 84.7434 91.3032 77.6626 85.7374C83.3756 81.2452 87.9941 75.5145 91.1698 68.9774C94.3455 62.4403 95.9957 55.2676 95.9957 47.9999C95.9957 40.7323 94.3455 33.5595 91.1698 27.0224C87.9941 20.4853 83.3756 14.7546 77.6626 10.2624C84.7434 4.69657 93.2473 1.23528 102.202 0.274128C111.157 -0.687022 120.202 0.890764 128.303 4.82714C136.403 8.76351 143.233 14.8996 148.011 22.5341C152.789 30.1686 155.323 38.9935 155.323 47.9999Z"
                                                fill="#F79E1B" />
                                        </svg>
                                    @elseif ($order->transaccion->brand == 'paypal')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-6 block ml-auto"
                                            viewBox="0 0 96 32" fill="none">
                                            <g clip-path="url(#clip0_66_11401)">
                                                <path
                                                    d="M35.475 8.51457H30.2249C29.8656 8.51457 29.5601 8.77558 29.5041 9.13024L27.3807 22.593C27.3384 22.8586 27.5442 23.0981 27.8136 23.0981H30.3201C30.6794 23.0981 30.9849 22.8371 31.041 22.4817L31.6136 18.8506C31.6689 18.4951 31.9752 18.2341 32.3337 18.2341H33.9957C37.4541 18.2341 39.4501 16.5606 39.9713 13.2442C40.2062 11.7933 39.9813 10.6533 39.3019 9.85493C38.5557 8.97824 37.2323 8.51457 35.475 8.51457ZM36.0807 13.4315C35.7936 15.3154 34.3542 15.3154 32.9624 15.3154H32.1702L32.726 11.7972C32.759 11.5845 32.9433 11.4279 33.1582 11.4279H33.5213C34.4694 11.4279 35.3637 11.4279 35.8259 11.9683C36.1015 12.2908 36.1859 12.7698 36.0807 13.4315Z"
                                                    fill="#253B80" />
                                                <path
                                                    d="M51.1688 13.3709H48.6546C48.4405 13.3709 48.2555 13.5275 48.2224 13.7401L48.1111 14.4433L47.9353 14.1885C47.3911 13.3985 46.1774 13.1344 44.966 13.1344C42.1877 13.1344 39.8149 15.2386 39.3527 18.1904C39.1124 19.6628 39.454 21.0707 40.2893 22.0525C41.0554 22.9553 42.1517 23.3315 43.4559 23.3315C45.6945 23.3315 46.9358 21.8921 46.9358 21.8921L46.8237 22.5907C46.7815 22.8578 46.9873 23.0973 47.2552 23.0973H49.5198C49.8799 23.0973 50.1839 22.8363 50.2407 22.4809L51.5994 13.876C51.6424 13.6112 51.4375 13.3709 51.1688 13.3709ZM47.6643 18.2641C47.4218 19.7004 46.2818 20.6646 44.8278 20.6646C44.0977 20.6646 43.5143 20.4304 43.1397 19.9867C42.7681 19.5461 42.6269 18.9189 42.7451 18.2203C42.9715 16.7963 44.1307 15.8006 45.5624 15.8006C46.2764 15.8006 46.8567 16.0378 47.2391 16.4853C47.6221 16.9375 47.7741 17.5685 47.6643 18.2641Z"
                                                    fill="#253B80" />
                                                <path
                                                    d="M64.5586 13.3708H62.0322C61.7911 13.3708 61.5646 13.4906 61.428 13.691L57.9435 18.8237L56.4665 13.8913C56.3736 13.5827 56.0888 13.3708 55.7664 13.3708H53.2837C52.982 13.3708 52.7724 13.6656 52.8684 13.9497L55.6512 22.1162L53.035 25.8095C52.8292 26.1005 53.0365 26.5004 53.3919 26.5004H55.9153C56.1548 26.5004 56.379 26.3837 56.5149 26.1872L64.9178 14.0579C65.119 13.7677 64.9125 13.3708 64.5586 13.3708Z"
                                                    fill="#253B80" />
                                                <path
                                                    d="M72.9231 8.51457H67.6722C67.3137 8.51457 67.0082 8.77558 66.9521 9.13024L64.8287 22.593C64.7865 22.8586 64.9923 23.0981 65.2602 23.0981H67.9547C68.205 23.0981 68.4192 22.9154 68.4583 22.6667L69.0609 18.8506C69.1162 18.4951 69.4225 18.2341 69.781 18.2341H71.4423C74.9014 18.2341 76.8966 16.5606 77.4186 13.2442C77.6543 11.7933 77.4279 10.6533 76.7485 9.85493C76.003 8.97824 74.6803 8.51457 72.9231 8.51457ZM73.5288 13.4315C73.2425 15.3154 71.8031 15.3154 70.4105 15.3154H69.619L70.1756 11.7972C70.2086 11.5845 70.3913 11.4279 70.607 11.4279H70.9702C71.9175 11.4279 72.8126 11.4279 73.2747 11.9683C73.5503 12.2908 73.634 12.7698 73.5288 13.4315Z"
                                                    fill="#253B80" />
                                                <path
                                                    d="M88.616 13.3709H86.1034C85.8877 13.3709 85.7042 13.5275 85.672 13.7401L85.5607 14.4433L85.3841 14.1885C84.8398 13.3985 83.6269 13.1344 82.4155 13.1344C79.6373 13.1344 77.2652 15.2386 76.803 18.1904C76.5635 19.6628 76.9036 21.0707 77.7388 22.0525C78.5065 22.9553 79.6012 23.3315 80.9055 23.3315C83.144 23.3315 84.3854 21.8921 84.3854 21.8921L84.2733 22.5907C84.2311 22.8578 84.4368 23.0973 84.7062 23.0973H86.9701C87.3286 23.0973 87.6342 22.8363 87.6902 22.4809L89.0498 13.876C89.0912 13.6112 88.8855 13.3709 88.616 13.3709ZM85.1116 18.2641C84.8705 19.7004 83.729 20.6646 82.275 20.6646C81.5465 20.6646 80.9615 20.4304 80.5869 19.9867C80.2153 19.5461 80.0756 18.9189 80.1923 18.2203C80.4203 16.7963 81.578 15.8006 83.0097 15.8006C83.7236 15.8006 84.304 16.0378 84.6863 16.4853C85.0709 16.9375 85.2229 17.5685 85.1116 18.2641Z"
                                                    fill="#253B80" />
                                                <path
                                                    d="M91.58 8.88377L89.4251 22.5929C89.3829 22.8586 89.5886 23.0981 89.8565 23.0981H92.0229C92.383 23.0981 92.6885 22.8371 92.7438 22.4816L94.8687 9.01965C94.9109 8.75404 94.7052 8.51375 94.4372 8.51375H92.0114C91.7972 8.51452 91.613 8.67113 91.58 8.88377Z"
                                                    fill="#253B80" />
                                                <path
                                                    d="M5.57789 25.7144L5.97938 23.1642L5.08504 23.1434H0.814453L3.78229 4.32537C3.7915 4.26856 3.82144 4.21559 3.8652 4.17797C3.90896 4.14036 3.965 4.11963 4.02334 4.11963H11.2241C13.6147 4.11963 15.2644 4.61708 16.1258 5.59894C16.5296 6.05955 16.7867 6.54088 16.9111 7.07058C17.0416 7.62638 17.0439 8.29042 16.9165 9.10032L16.9073 9.15943V9.67838L17.3111 9.90714C17.6511 10.0875 17.9214 10.2941 18.1286 10.5305C18.4741 10.9243 18.6975 11.4248 18.7919 12.0183C18.8894 12.6286 18.8572 13.3548 18.6975 14.177C18.5132 15.1227 18.2154 15.9465 17.8131 16.6205C17.4431 17.2415 16.9717 17.7566 16.4121 18.1558C15.8778 18.5351 15.2429 18.8229 14.5252 19.0072C13.8296 19.1884 13.0366 19.2797 12.1669 19.2797H11.6064C11.2057 19.2797 10.8165 19.424 10.511 19.6827C10.2047 19.9468 10.002 20.3076 9.93982 20.7022L9.8976 20.9318L9.18827 25.4265L9.15603 25.5915C9.14758 25.6438 9.133 25.6699 9.1115 25.6875C9.09231 25.7036 9.06467 25.7144 9.0378 25.7144H5.57789Z"
                                                    fill="#253B80" />
                                                <path
                                                    d="M17.6934 9.21924C17.6719 9.35665 17.6474 9.49714 17.6197 9.64146C16.6701 14.517 13.4213 16.2013 9.27201 16.2013H7.15936C6.65193 16.2013 6.22433 16.5697 6.14526 17.0703L5.06361 23.9302L4.7573 25.8748C4.70587 26.2033 4.9592 26.4996 5.29084 26.4996H9.03787C9.48159 26.4996 9.85851 26.1772 9.92837 25.7396L9.96522 25.5493L10.6707 21.0722L10.716 20.8265C10.7851 20.3874 11.1628 20.065 11.6065 20.065H12.1669C15.7973 20.065 18.6392 18.591 19.4698 14.3258C19.8168 12.544 19.6372 11.0563 18.719 10.0099C18.4411 9.69443 18.0965 9.43265 17.6934 9.21924Z"
                                                    fill="#179BD7" />
                                                <path
                                                    d="M16.7 8.82319C16.5549 8.78097 16.4052 8.74258 16.2516 8.70804C16.0973 8.67426 15.9392 8.64432 15.7765 8.61822C15.2068 8.5261 14.5827 8.48234 13.9141 8.48234H8.27011C8.13116 8.48234 7.99912 8.51382 7.8809 8.57062C7.62066 8.69575 7.4272 8.94218 7.38037 9.24388L6.17973 16.8485L6.14526 17.0703C6.22433 16.5697 6.65193 16.2013 7.15936 16.2013H9.27201C13.4213 16.2013 16.6701 14.5162 17.6197 9.64146C17.6481 9.49714 17.6719 9.35665 17.6934 9.21924C17.4531 9.0918 17.1928 8.98287 16.9126 8.88998C16.8435 8.86695 16.7721 8.84468 16.7 8.82319Z"
                                                    fill="#222D65" />
                                                <path
                                                    d="M7.38037 9.24388C7.4272 8.94218 7.62072 8.69572 7.88096 8.57135C7.99995 8.51455 8.13122 8.48307 8.27017 8.48307H13.9141C14.5828 8.48307 15.2069 8.52683 15.7765 8.61895C15.9393 8.64505 16.0974 8.67499 16.2517 8.70877C16.4052 8.74331 16.5549 8.7817 16.7 8.82392C16.7722 8.84542 16.8436 8.86768 16.9134 8.88994C17.1936 8.98283 17.4539 9.09261 17.6942 9.21927C17.9767 7.41754 17.6919 6.19079 16.7177 5.07996C15.6437 3.85705 13.7053 3.3335 11.225 3.3335H4.02415C3.51749 3.3335 3.08528 3.70198 3.00698 4.20327L0.00766819 23.2148C-0.0514429 23.591 0.238739 23.9303 0.617971 23.9303L5.06361 23.9302L6.17973 16.8485L7.38037 9.24388Z"
                                                    fill="#253B80" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_66_11401">
                                                    <rect width="95.3333" height="32" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-6 block ml-auto"
                                            viewBox="0 0 287 205" fill="none">
                                            <rect x="4" y="4" width="279" height="197" rx="32"
                                                stroke="#9CA3AF" stroke-width="8" />
                                            <rect y="56" width="280" height="47" fill="#9CA3AF" />
                                            <rect x="189" y="135" width="79" height="31" rx="2"
                                                fill="white" />
                                            <path
                                                d="M211.136 144.364L213.045 147.75H213.136L215.068 144.364H218.591L215.114 150.182L218.727 156H215.114L213.136 152.545H213.045L211.068 156H207.477L211.045 150.182L207.591 144.364H211.136ZM226.527 144.364L228.436 147.75H228.527L230.459 144.364H233.982L230.504 150.182L234.118 156H230.504L228.527 152.545H228.436L226.459 156H222.868L226.436 150.182L222.982 144.364H226.527ZM241.918 144.364L243.827 147.75H243.918L245.849 144.364H249.372L245.895 150.182L249.509 156H245.895L243.918 152.545H243.827L241.849 156H238.259L241.827 150.182L238.372 144.364H241.918Z"
                                                fill="#111928" />
                                            <path
                                                d="M258.5 151C258.5 157.237 255.2 163.058 249.523 167.399C243.844 171.742 235.889 174.5 227 174.5C218.111 174.5 210.156 171.742 204.477 167.399C198.8 163.058 195.5 157.237 195.5 151C195.5 144.763 198.8 138.942 204.477 134.601C210.156 130.258 218.111 127.5 227 127.5C235.889 127.5 243.844 130.258 249.523 134.601C255.2 138.942 258.5 144.763 258.5 151Z"
                                                stroke="#E71717" stroke-width="5" />
                                        </svg>
                                    @endif

                                    {{ $order->transaccion->brand }}
                                    <br>
                                    {{ $order->transaccion->card }}
                                </td>
                            </tr>
                            <tr>
                                <td class="w-full text-end align-middle" colspan="2">
                                    <div class="w-full flex items-center justify-end">
                                        <x-span-text :text="$order->transaccion->action_description" class="text-xs" type="green" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-colorsubtitleform">ID Transacción</td>
                                <td class="font-medium text-colorlabel text-end">
                                    {{ $order->transaccion->transaction_id }}</td>
                            </tr>
                            <tr>
                                <td class="text-colorsubtitleform">Correo</td>
                                <td class="font-medium text-colorlabel text-end">
                                    {{ $order->transaccion->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-colorsubtitleform">Nombres</td>
                                <td class="font-medium text-colorlabel text-end">
                                    {{ $order->transaccion->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-colorsubtitleform">Monto</td>
                                <td class="font-semibold text-colorsubtitleform text-end text-xl">
                                    {{ number_format($order->transaccion->amount, 2, '.', ', ') }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="contenedor w-full grid grid-cols-1 lg:grid-cols-7 gap-3 xl:gap-5">
        <div class="lg:col-span-5 w-full overflow-x-auto {{-- rounded-xl border border-borderminicard --}}">
            <table class="w-full min-w-full text-[10px] md:text-xs">
                <tbody class="divide-y">
                    @foreach ($order->tvitems as $item)
                        @php
                            $image = $item->producto->getImageURL();
                        @endphp

                        <tr class="text-colorlabel">
                            <td class="text-left py-2 align-middle">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0 w-16 h-16 xl:w-24 xl:h-24 rounded overflow-hidden">
                                        @if ($image)
                                            <img src="{{ $image }}" alt=""
                                                class="w-full h-full object-scale-down rounded aspect-square overflow-hidden">
                                        @else
                                            <x-icon-file-upload
                                                class="!w-full !h-full !m-0 text-colorsubtitleform !border-0"
                                                type="unknown" />
                                        @endif
                                    </div>
                                    <div
                                        class="w-full flex-1 sm:flex justify-between gap-3 items-center text-colorsubtitleform">
                                        <div class="w-full text-xs sm:flex-1">
                                            <p class="w-full">{{ $item->producto->name }}</p>
                                            @if (!empty($item->promocion_id))
                                                <span
                                                    class="p-1 font-semibold inline-block ring-1 rounded-lg text-[10px] ring-green-600 text-end text-green-600 whitespace-nowrap">
                                                    PROMOCIÓN</span>
                                            @endif
                                        </div>

                                        <div class="flex items-end sm:items-center sm:w-60 sm:flex-shrink-0 ">
                                            <span
                                                class="text-left p-2 text-xs sm:text-end font-semibold whitespace-nowrap">
                                                x{{ decimalOrInteger($item->cantidad) }}
                                                {{ $item->producto->unit->name }}
                                            </span>

                                            @if ($item->isGratuito())
                                                <div class="flex-1 flex justify-end items-center">
                                                    <span
                                                        class="p-2 font-semibold inline-block ring-1 rounded-lg text-xs ring-green-600 text-end text-green-600 whitespace-nowrap">
                                                        GRATUITO</span>
                                                </div>
                                            @else
                                                <span
                                                    class="p-2 font-semibold text-lg flex-1 text-end text-colorlabel whitespace-nowrap">
                                                    {{ $order->moneda->simbolo }}
                                                    {{ number_format($item->total, 2, '.', ', ') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="lg:col-span-2 w-full pb-5">
            <div class="w-full space-y-6 {{-- p-3 rounded-lg border border-borderminicard --}}">
                <h3 class="text-xl font-semibold text-colorsubtitleform">Tracking</h3>

                <ol class="relative ms-3 border-s border-borderminicard">
                    @foreach ($order->trackings as $item)
                        <li class="mb-10 ms-6 text-colorlabel">
                            <span
                                class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-next-500 ring-8 ring-body">
                                @if ($item->trackingstate->isFinalizado())
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                        class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M21 7V12M3 7C3 10.0645 3 16.7742 3 17.1613C3 18.5438 4.94564 19.3657 8.83693 21.0095C10.4002 21.6698 11.1818 22 12 22L12 11.3548" />
                                        <path d="M15 19C15 19 15.875 19 16.75 21C16.75 21 19.5294 16 22 15" />
                                        <path
                                            d="M8.32592 9.69138L5.40472 8.27785C3.80157 7.5021 3 7.11423 3 6.5C3 5.88577 3.80157 5.4979 5.40472 4.72215L8.32592 3.30862C10.1288 2.43621 11.0303 2 12 2C12.9697 2 13.8712 2.4362 15.6741 3.30862L18.5953 4.72215C20.1984 5.4979 21 5.88577 21 6.5C21 7.11423 20.1984 7.5021 18.5953 8.27785L15.6741 9.69138C13.8712 10.5638 12.9697 11 12 11C11.0303 11 10.1288 10.5638 8.32592 9.69138Z" />
                                        <path d="M6 12L8 13" />
                                        <path d="M17 4L7 9" />
                                    </svg>
                                @else
                                    <svg class="h-4 w-4 text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                                    </svg>
                                @endif
                            </span>
                            <h4 class="mb-0.5 font-semibold text-sm text-primary">
                                {{ formatDate($item->date, 'DD MMM Y, hh:mm A') }}</h4>
                            <p class="text-xs text-colorsubtitleform">{{ $item->trackingstate->name }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</x-app-layout>

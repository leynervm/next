<x-admin-layout>
    <x-slot name="breadcrumb">
        @canany(['admin.cajas', 'admin.cajas.methodpayments', 'admin.cajas.movimientos', 'admin.cajas.mensuales.create',
            'admin.cajas.aperturas', 'admin.administracion.employers.adelantos.create', 'admin.cajas.conceptos'])
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
        @endcanany

        <x-link-breadcrumb text="ADMINISTRAR CAJAS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 22L14.1845 21.5811C13.4733 20.6369 13.2969 19.1944 13.7468 18" />
                    <path d="M9.5 22L9.8155 21.5811C10.5267 20.6369 10.7031 19.1944 10.2532 18" />
                    <path d="M7 22H17" />
                    <path
                        d="M12 7C10.8954 7 10 7.67157 10 8.5C10 9.32843 10.8954 10 12 10C13.1046 10 14 10.6716 14 11.5C14 12.3284 13.1046 13 12 13M12 7C12.8708 7 13.6116 7.4174 13.8862 8M12 7V6M12 13C11.1292 13 10.3884 12.5826 10.1138 12M12 13V14" />
                    <path
                        d="M14 2H10C6.72077 2 5.08116 2 3.91891 2.81382C3.48891 3.1149 3.1149 3.48891 2.81382 3.91891C2 5.08116 2 6.72077 2 10C2 13.2792 2 14.9188 2.81382 16.0811C3.1149 16.5111 3.48891 16.8851 3.91891 17.1862C5.08116 18 6.72077 18 10 18H14C17.2792 18 18.9188 18 20.0811 17.1862C20.5111 16.8851 20.8851 16.5111 21.1862 16.0811C22 14.9188 22 13.2792 22 10C22 6.72077 22 5.08116 21.1862 3.91891C20.8851 3.48891 20.5111 3.1149 20.0811 2.81382C18.9188 2 17.2792 2 14 2Z" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    @can('admin.cajas.mensuales.create')
        <div class="">
            @livewire('admin.mounthboxes.create-mounthbox')
        </div>
    @endcan

    @can('admin.cajas.mensuales')
        <div class="mt-5">
            @livewire('admin.mounthboxes.show-mounthboxes')
        </div>
    @endcan
</x-admin-layout>

<x-admin-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="VENTAS EN LÍNEA" route="admin.marketplace">
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
        <x-link-breadcrumb text="USUARIOS WEB" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M18.505 19h.44c1.035 0 1.859-.471 2.598-1.13 1.877-1.676-2.536-3.37-4.043-3.37M15 5.069Q15.341 5 15.705 5C17.525 5 19 6.343 19 8s-1.475 3-3.295 3q-.364 0-.705-.069m-10.217 4.18c-1.1.632-3.986 1.922-2.229 3.536C3.413 19.436 4.37 20 5.571 20h6.858c1.202 0 2.158-.564 3.017-1.353 1.757-1.614-1.128-2.904-2.229-3.536-2.58-1.481-5.854-1.481-8.434 0M13 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>


    @can('admin.marketplace.userweb.create')
        <div class="">
            <livewire:modules.marketplace.usersweb.create-userweb />
        </div>
    @endcan

    @can('admin.marketplace.userweb')
        <div class="w-full ">
            <livewire:modules.marketplace.usersweb.show-usersweb />
        </div>
    @endcan
</x-admin-layout>

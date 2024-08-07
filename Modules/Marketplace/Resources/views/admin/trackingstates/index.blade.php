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
        <x-link-breadcrumb text="ESTADOS DE SEGUIMIENTO" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M18.7185 10.7151C18.5258 10.8979 18.2682 11 18.0001 11C17.732 11 17.4744 10.8979 17.2817 10.7151C15.5167 9.03169 13.1515 7.15111 14.305 4.42085C14.9286 2.94462 16.4257 2 18.0001 2C19.5745 2 21.0715 2.94462 21.6952 4.42085C22.8472 7.14767 20.4878 9.03749 18.7185 10.7151Z" />
                    <path d="M18 6H18.009" />
                    <circle cx="5" cy="19" r="3" />
                    <path
                        d="M11 7H9.5C7.567 7 6 8.34315 6 10C6 11.6569 7.567 13 9.5 13H12.5C14.433 13 16 14.3431 16 16C16 17.6569 14.433 19 12.5 19H11" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    @can('admin.marketplace.trackingstates.create')
        <div class="w-full">
            <livewire:modules.marketplace.trackingstates.create-trackingstate />
        </div>
    @endcan

    @can('admin.marketplace.trackingstates')
        <div class="w-full mt-3">
            <livewire:modules.marketplace.trackingstates.show-trackingstates />
        </div>
    @endcan
</x-admin-layout>

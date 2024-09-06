<x-admin-layout>
    <x-slot name="breadcrumb">
        @can('admin.almacen')
            @if (Module::isEnabled('Almacen'))
                <x-link-breadcrumb text="ALMACÉN" route="admin.almacen">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M11 22C10.1818 22 9.40019 21.6698 7.83693 21.0095C3.94564 19.3657 2 18.5438 2 17.1613C2 16.7742 2 10.0645 2 7M11 22L11 11.3548M11 22C11.3404 22 11.6463 21.9428 12 21.8285M20 7V11.5" />
                            <path stroke="none" fill="currentColor"
                                d="M21.4697 22.5303C21.7626 22.8232 22.2374 22.8232 22.5303 22.5303C22.8232 22.2374 22.8232 21.7626 22.5303 21.4697L21.4697 22.5303ZM19.8697 20.9303L21.4697 22.5303L22.5303 21.4697L20.9303 19.8697L19.8697 20.9303ZM21.95 17.6C21.95 15.1976 20.0024 13.25 17.6 13.25V14.75C19.174 14.75 20.45 16.026 20.45 17.6H21.95ZM17.6 13.25C15.1976 13.25 13.25 15.1976 13.25 17.6H14.75C14.75 16.026 16.026 14.75 17.6 14.75V13.25ZM13.25 17.6C13.25 20.0024 15.1976 21.95 17.6 21.95V20.45C16.026 20.45 14.75 19.174 14.75 17.6H13.25ZM17.6 21.95C20.0024 21.95 21.95 20.0024 21.95 17.6H20.45C20.45 19.174 19.174 20.45 17.6 20.45V21.95Z" />
                            <path
                                d="M7.32592 9.69138L4.40472 8.27785C2.80157 7.5021 2 7.11423 2 6.5C2 5.88577 2.80157 5.4979 4.40472 4.72215L7.32592 3.30862C9.12883 2.43621 10.0303 2 11 2C11.9697 2 12.8712 2.4362 14.6741 3.30862L17.5953 4.72215C19.1984 5.4979 20 5.88577 20 6.5C20 7.11423 19.1984 7.5021 17.5953 8.27785L14.6741 9.69138C12.8712 10.5638 11.9697 11 11 11C10.0303 11 9.12883 10.5638 7.32592 9.69138Z" />
                            <path d="M5 12L7 13" />
                            <path d="M16 4L6 9" />
                        </svg>
                    </x-slot>
                </x-link-breadcrumb>
            @endif
        @endcan

        @if (Module::isEnabled('Ventas') || Module::isEnabled('Almacen'))
            @can('admin.almacen.productos')
                <x-link-breadcrumb text="PRODUCTOS" route="admin.almacen.productos">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4.5 17V6H19.5V17H4.5Z" />
                            <path d="M4.5 6L6.5 2.00001L17.5 2L19.5 6" />
                            <path d="M10 9H14" />
                            <path
                                d="M11.9994 19.5V22M11.9994 19.5L6.99939 19.5M11.9994 19.5H16.9994M6.99939 19.5H1.99939V22M6.99939 19.5V22M16.9994 19.5H22L21.9994 22M16.9994 19.5V22" />
                        </svg>
                    </x-slot>
                </x-link-breadcrumb>
            @endcan
        @endif

        <x-link-breadcrumb text="CATEGORÍAS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H2v7l6.29 6.29c.94.94 2.48.94 3.42 0l3.58-3.58c.94-.94.94-2.48 0-3.42L9 5Z" />
                    <path d="M6 9.01V9" />
                    <path d="m15 5 6.3 6.3a2.4 2.4 0 0 1 0 3.4L17 19" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="mt-3 flex gap-2 flex-wrap">

        @can('admin.almacen.categorias.create')
            <livewire:admin.categories.create-category />
        @endcan

        @can('admin.almacen.subcategorias')
            <x-link-next href="{{ route('admin.almacen.subcategorias') }}" titulo="Subcategorías">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M16.8284 7.06234C18 8.12469 18 9.83451 18 13.2541V14.7459C18 18.1655 18 19.8753 16.8284 20.9377C15.6569 22 13.7712 22 10 22C6.22876 22 4.34315 22 3.17157 20.9377C2 19.8753 2 18.1655 2 14.7459V13.2541C2 9.83451 2 8.12469 3.17157 7.06234C4.34315 6 6.22876 6 10 6C13.7712 6 15.6569 6 16.8284 7.06234Z" />
                    <path
                        d="M6.06641 6C6.17344 4.61213 6.451 3.71504 7.1708 3.06234C8.34237 2 10.228 2 13.9992 2C17.7705 2 19.6561 2 20.8277 3.06234C21.9992 4.12469 21.9992 5.83451 21.9992 9.25414V10.7459C21.9992 14.1655 21.9992 15.8753 20.8277 16.9377C20.1745 17.5299 19.2993 17.792 17.9992 17.908" />
                    <path
                        d="M10.6911 10.5777L11.395 11.9972C11.491 12.1947 11.7469 12.3843 11.9629 12.4206L13.2388 12.6343C14.0547 12.7714 14.2467 13.3682 13.6587 13.957L12.6668 14.9571C12.4989 15.1265 12.4069 15.4531 12.4589 15.687L12.7428 16.925C12.9668 17.9049 12.4509 18.284 11.591 17.7718L10.3951 17.0581C10.1791 16.929 9.82315 16.929 9.60318 17.0581L8.40731 17.7718C7.5514 18.284 7.03146 17.9009 7.25543 16.925L7.5394 15.687C7.5914 15.4531 7.49941 15.1265 7.33143 14.9571L6.33954 13.957C5.7556 13.3682 5.94358 12.7714 6.75949 12.6343L8.03535 12.4206C8.24732 12.3843 8.5033 12.1947 8.59929 11.9972L9.30321 10.5777C9.68717 9.80744 10.3111 9.80744 10.6911 10.5777Z" />
                </svg>
            </x-link-next>
        @endcan
    </div>

    @can('admin.almacen.categorias')
        <div class="mt-3">
            <livewire:admin.categories.show-categories />
        </div>
    @endcan
</x-admin-layout>

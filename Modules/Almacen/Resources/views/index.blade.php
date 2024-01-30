<x-app-layout>

    <x-slot name="breadcrumb">
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

        <x-link-breadcrumb text="PRODUCTOS" active>
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
    </x-slot>

    <div class="flex flex-wrap gap-2">
        <x-link-next href="{{ route('admin.almacen.productos.create') }}" titulo="Registrar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="5" y2="19" />
                <line x1="5" x2="19" y1="12" y2="12" />
            </svg>
        </x-link-next>



        {{-- <x-link-next href="{{ route('admin.almacen.almacenes') }}" titulo="Almacenes">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M11 22C10.1818 22 9.40019 21.6698 7.83693 21.0095C3.94564 19.3657 2 18.5438 2 17.1613C2 16.7742 2 10.0645 2 7M11 22L11 11.3548M11 22C11.3404 22 11.6463 21.9428 12 21.8285M20 7V11.5" />
                <path stroke-width=".3" fill="currentColor"
                    d="M21.4697 22.5303C21.7626 22.8232 22.2374 22.8232 22.5303 22.5303C22.8232 22.2374 22.8232 21.7626 22.5303 21.4697L21.4697 22.5303ZM19.8697 20.9303L21.4697 22.5303L22.5303 21.4697L20.9303 19.8697L19.8697 20.9303ZM21.95 17.6C21.95 15.1976 20.0024 13.25 17.6 13.25V14.75C19.174 14.75 20.45 16.026 20.45 17.6H21.95ZM17.6 13.25C15.1976 13.25 13.25 15.1976 13.25 17.6H14.75C14.75 16.026 16.026 14.75 17.6 14.75V13.25ZM13.25 17.6C13.25 20.0024 15.1976 21.95 17.6 21.95V20.45C16.026 20.45 14.75 19.174 14.75 17.6H13.25ZM17.6 21.95C20.0024 21.95 21.95 20.0024 21.95 17.6H20.45C20.45 19.174 19.174 20.45 17.6 20.45V21.95Z" />
                <path
                    d="M7.32592 9.69138L4.40472 8.27785C2.80157 7.5021 2 7.11423 2 6.5C2 5.88577 2.80157 5.4979 4.40472 4.72215L7.32592 3.30862C9.12883 2.43621 10.0303 2 11 2C11.9697 2 12.8712 2.4362 14.6741 3.30862L17.5953 4.72215C19.1984 5.4979 20 5.88577 20 6.5C20 7.11423 19.1984 7.5021 17.5953 8.27785L14.6741 9.69138C12.8712 10.5638 11.9697 11 11 11C10.0303 11 9.12883 10.5638 7.32592 9.69138Z" />
                <path d="M5 12L7 13" />
                <path d="M16 4L6 9" />
            </svg>
        </x-link-next> --}}

        {{-- <x-link-next href="{{ route('admin.almacen.productos') }}" titulo="Productos">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4.5 17V6H19.5V17H4.5Z" />
                <path d="M4.5 6L6.5 2.00001L17.5 2L19.5 6" />
                <path d="M10 9H14" />
                <path
                    d="M11.9994 19.5V22M11.9994 19.5L6.99939 19.5M11.9994 19.5H16.9994M6.99939 19.5H1.99939V22M6.99939 19.5V22M16.9994 19.5H22L21.9994 22M16.9994 19.5V22" />
            </svg>
        </x-link-next> --}}

        <x-link-next href="{{ route('admin.almacen.compras') }}" titulo="Compras">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle r="1.5" transform="matrix(1 0 0 -1 17.5 6.5)" />
                <path
                    d="M2.77423 11.1439C1.77108 12.2643 1.7495 13.9546 2.67016 15.1437C4.49711 17.5033 6.49674 19.5029 8.85633 21.3298C10.0454 22.2505 11.7357 22.2289 12.8561 21.2258C15.8979 18.5022 18.6835 15.6559 21.3719 12.5279C21.6377 12.2187 21.8039 11.8397 21.8412 11.4336C22.0062 9.63798 22.3452 4.46467 20.9403 3.05974C19.5353 1.65481 14.362 1.99377 12.5664 2.15876C12.1603 2.19608 11.7813 2.36233 11.472 2.62811C8.34412 5.31646 5.49781 8.10211 2.77423 11.1439Z" />
                <path stroke-width="0.5" fill="currentColor"
                    d="M11.0863 12.9089L11.6162 12.3782L11.0863 12.9089ZM13.3332 10.6657L12.8033 11.1965L13.3332 10.6657ZM11.218 15.4562L11.7479 15.987L11.218 15.4562ZM8.66654 15.3247L9.19643 14.7939L8.66654 15.3247ZM14.5299 10.5308C14.823 10.2381 14.8234 9.76324 14.5308 9.47011C14.2381 9.17697 13.7632 9.17658 13.4701 9.46923L14.5299 10.5308ZM7.47011 15.4594C7.17697 15.752 7.17658 16.2269 7.46923 16.52C7.76189 16.8132 8.23676 16.8136 8.52989 16.5209L7.47011 15.4594ZM13.0644 12.1568C12.9548 12.5563 13.1898 12.9689 13.5893 13.0785C13.9887 13.1881 14.4014 12.9531 14.511 12.5536L13.0644 12.1568ZM8.82372 13.8411C8.86014 13.4285 8.55518 13.0645 8.14257 13.0281C7.72996 12.9916 7.36595 13.2966 7.32953 13.7092L8.82372 13.8411ZM11.6162 12.3782C11.2265 11.9891 11.1584 11.7557 11.1528 11.6524C11.1486 11.5741 11.1725 11.427 11.4234 11.1766L10.3636 10.115C9.9463 10.5317 9.61938 11.0697 9.655 11.7329C9.68929 12.3712 10.0517 12.9358 10.5564 13.4397L11.6162 12.3782ZM11.4234 11.1766C11.7907 10.8099 12.4087 10.8025 12.8033 11.1965L13.8631 10.1349C12.8993 9.17271 11.3327 9.14751 10.3636 10.115L11.4234 11.1766ZM10.6881 14.9255C10.3909 15.2222 10.1553 15.2655 9.97835 15.2459C9.76713 15.2226 9.49422 15.0912 9.19643 14.7939L8.13664 15.8555C8.57981 16.2979 9.15055 16.6637 9.81378 16.7369C10.5113 16.8139 11.181 16.553 11.7479 15.987L10.6881 14.9255ZM10.5564 13.4397C10.9524 13.835 11.0529 14.0944 11.0635 14.2462C11.0719 14.3673 11.0336 14.5806 10.6881 14.9255L11.7479 15.987C12.2667 15.4691 12.6097 14.8552 12.5598 14.1416C12.5121 13.4587 12.1146 12.8757 11.6162 12.3782L10.5564 13.4397ZM13.8631 11.1965L14.5299 10.5308L13.4701 9.46923L12.8033 10.1349L13.8631 11.1965ZM8.13664 14.7939L7.47011 15.4594L8.52989 16.5209L9.19643 15.8555L8.13664 14.7939ZM12.8033 11.1965C13.0689 11.4616 13.1535 11.8318 13.0644 12.1568L14.511 12.5536C14.7394 11.7208 14.5196 10.7903 13.8631 10.1349L12.8033 11.1965ZM9.19643 14.7939C8.91097 14.509 8.79655 14.149 8.82372 13.8411L7.32953 13.7092C7.26204 14.4739 7.54709 15.2669 8.13664 15.8555L9.19643 14.7939Z" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.almacen.promociones') }}" titulo="Promociones">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full">
                <path
                    d="M5.5 10C5.62215 10 5.73571 9.94347 5.96282 9.83041L7.78832 8.92162C8.59611 8.51948 9 8.31841 9 8V4M5.5 10C5.37785 10 5.26429 9.94347 5.03718 9.83041L3.21168 8.92162C2.4039 8.51948 2 8.31841 2 8V4M5.5 10V6M9 4C9 3.68159 8.59611 3.48052 7.78832 3.07838L5.96282 2.16959C5.73571 2.05653 5.62215 2 5.5 2C5.37785 2 5.26429 2.05653 5.03718 2.16959L3.21168 3.07838C2.40389 3.48052 2 3.68159 2 4M9 4C9 4.31841 8.59611 4.51948 7.78832 4.92162L5.96282 5.83041C5.73571 5.94347 5.62215 6 5.5 6M2 4C2 4.31841 2.40389 4.51948 3.21168 4.92162L5.03718 5.83041C5.26429 5.94347 5.37785 6 5.5 6" />
                <path
                    d="M18.5 10C18.6222 10 18.7357 9.94347 18.9628 9.83041L20.7883 8.92162C21.5961 8.51948 22 8.31841 22 8V4M18.5 10C18.3778 10 18.2643 9.94347 18.0372 9.83041L16.2117 8.92162C15.4039 8.51948 15 8.31841 15 8V4M18.5 10V6M22 4C22 3.68159 21.5961 3.48052 20.7883 3.07838L18.9628 2.16959C18.7357 2.05653 18.6222 2 18.5 2C18.3778 2 18.2643 2.05653 18.0372 2.16959L16.2117 3.07838C15.4039 3.48052 15 3.68159 15 4M22 4C22 4.31841 21.5961 4.51948 20.7883 4.92162L18.9628 5.83041C18.7357 5.94347 18.6222 6 18.5 6M15 4C15 4.31841 15.4039 4.51948 16.2117 4.92162L18.0372 5.83041C18.2643 5.94347 18.3778 6 18.5 6" />
                <path d="M11.5 6H12.5" />
                <path
                    d="M2 12V15.5C2 16.9045 2 17.6067 2.33706 18.1111C2.48298 18.3295 2.67048 18.517 2.88886 18.6629C3.39331 19 4.09554 19 5.5 19" />
                <path
                    d="M22 12V15.5C22 16.9045 22 17.6067 21.6629 18.1111C21.517 18.3295 21.3295 18.517 21.1111 18.6629C20.6067 19 19.9045 19 18.5 19" />
                <path
                    d="M12 22C12.1222 22 12.2357 21.9435 12.4628 21.8304L14.2883 20.9216C15.0961 20.5195 15.5 20.3184 15.5 20V16M12 22C11.8778 22 11.7643 21.9435 11.5372 21.8304L9.71168 20.9216C8.9039 20.5195 8.5 20.3184 8.5 20V16M12 22V18M15.5 16C15.5 15.6816 15.0961 15.4805 14.2883 15.0784L12.4628 14.1696C12.2357 14.0565 12.1222 14 12 14C11.8778 14 11.7643 14.0565 11.5372 14.1696L9.71168 15.0784C8.90389 15.4805 8.5 15.6816 8.5 16M15.5 16C15.5 16.3184 15.0961 16.5195 14.2883 16.9216L12.4628 17.8304C12.2357 17.9435 12.1222 18 12 18M8.5 16C8.5 16.3184 8.90389 16.5195 9.71168 16.9216L11.5372 17.8304C11.7643 17.9435 11.8778 18 12 18" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.almacen.ofertas') }}" titulo="Ofertas">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full">
                <path d="M5.8 11.3 2 22l10.7-3.79" />
                <path d="M4 3h.01" />
                <path d="M22 8h.01" />
                <path d="M15 2h.01" />
                <path d="M22 20h.01" />
                <path
                    d="m22 2-2.24.75a2.9 2.9 0 0 0-1.96 3.12v0c.1.86-.57 1.63-1.45 1.63h-.38c-.86 0-1.6.6-1.76 1.44L14 10" />
                <path d="m22 13-.82-.33c-.86-.34-1.82.2-1.98 1.11v0c-.11.7-.72 1.22-1.43 1.22H17" />
                <path d="m11 2 .33.82c.34.86-.2 1.82-1.11 1.98v0C9.52 4.9 9 5.52 9 6.23V7" />
                <path
                    d="M11 13c1.93 1.93 2.83 4.17 2 5-.83.83-3.07-.07-5-2-1.93-1.93-2.83-4.17-2-5 .83-.83 3.07.07 5 2Z" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.almacen.marcas') }}" titulo="Marcas">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M10.6119 5.00008L10.0851 7M12.2988 2.76313C12.713 3.49288 12.4672 4.42601 11.7499 4.84733C11.0326 5.26865 10.1153 5.01862 9.70118 4.28887C9.28703 3.55912 9.53281 2.62599 10.2501 2.20467C10.9674 1.78334 11.8847 2.03337 12.2988 2.76313Z" />
                <path
                    d="M13 21.998C12.031 20.8176 10.5 18 8.5 18C7.20975 18.1059 6.53573 19.3611 5.84827 20.3287M5.84827 20.3287C5.45174 19.961 5.30251 19.4126 5.00406 18.3158L3.26022 11.9074C2.5584 9.32827 2.20749 8.0387 2.80316 7.02278C3.39882 6.00686 4.70843 5.66132 7.32766 4.97025L9.5 4.39708M5.84827 20.3287C6.2448 20.6965 6.80966 20.8103 7.9394 21.0379L12.0813 21.8725C12.9642 22.0504 12.9721 22.0502 13.8426 21.8205L16.6723 21.0739C19.2916 20.3828 20.6012 20.0373 21.1968 19.0214C21.7925 18.0055 21.4416 16.7159 20.7398 14.1368L19.0029 7.75375C18.301 5.17462 17.9501 3.88506 16.9184 3.29851C16.0196 2.78752 14.9098 2.98396 12.907 3.5" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.almacen.categorias') }}" titulo="categorías">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 5H2v7l6.29 6.29c.94.94 2.48.94 3.42 0l3.58-3.58c.94-.94.94-2.48 0-3.42L9 5Z" />
                <path d="M6 9.01V9" />
                <path d="m15 5 6.3 6.3a2.4 2.4 0 0 1 0 3.4L17 19" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.almacen.typegarantias') }}" titulo="Tipo garantía">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                <path d="m9 12 2 2 4-4" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Listado partes">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 17 2 2 4-4" />
                <path d="m3 7 2 2 4-4" />
                <path d="M13 6h8" />
                <path d="M13 12h8" />
                <path d="M13 18h8" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Inventario herramientas">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                <path d="M12 11h4" />
                <path d="M12 16h4" />
                <path d="M8 11h.01" />
                <path d="M8 16h.01" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.almacen.kardex') }}" titulo="Kardex">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d=" m3 16 4 4 4-4" />
                <path d="M7 20V4" />
                <path d="m21 8-4-4-4 4" />
                <path d="M17 4v16" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.almacen.almacenareas') }}" titulo="Áreas & estantes">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m4 6 3-3 3 3" />
                <path d="M7 17V3" />
                <path d="m14 6 3-3 3 3" />
                <path d="M17 17V3" />
                <path d="M4 21h16" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.units') }}" titulo="Unidades Medida">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M21.3 8.7 8.7 21.3c-1 1-2.5 1-3.4 0l-2.6-2.6c-1-1-1-2.5 0-3.4L15.3 2.7c1-1 2.5-1 3.4 0l2.6 2.6c1 1 1 2.5 0 3.4Z" />
                <path d="m7.5 10.5 2 2" />
                <path d="m10.5 7.5 2 2" />
                <path d="m13.5 4.5 2 2" />
                <path d="m4.5 13.5 2 2" />
            </svg>
        </x-link-next>

        <x-link-next href="{{ route('admin.almacen.especificaciones') }}" titulo="Especificaciones">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-list-todo">
                <rect x="3" y="5" width="6" height="6" rx="1" />
                <path d="m3 17 2 2 4-4" />
                <path d="M13 6h8" />
                <path d="M13 12h8" />
                <path d="M13 18h8" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Historial precios">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-candlestick-chart">
                <path d="M9 5v4" />
                <rect width="4" height="6" x="7" y="9" rx="1" />
                <path d="M9 15v2" />
                <path d="M17 3v2" />
                <rect width="4" height="8" x="15" y="5" rx="1" />
                <path d="M17 13v3" />
                <path d="M3 3v18h18" />
            </svg>
        </x-link-next>

        {{-- <x-link-next href="#" titulo="Estantes almmacén">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 2h10" />
                <path d="M5 6h14" />
                <rect width="18" height="12" x="3" y="10" rx="2" />
            </svg>
        </x-link-next> --}}
    </div>

    <div class="mt-3">
        @livewire('modules.almacen.productos.show-productos')
    </div>

</x-app-layout>

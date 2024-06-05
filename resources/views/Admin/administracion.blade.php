<x-admin-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="ADMINISTRACIÓN" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M10 13.3333C10 13.0233 10 12.8683 10.0341 12.7412C10.1265 12.3961 10.3961 12.1265 10.7412 12.0341C10.8683 12 11.0233 12 11.3333 12H12.6667C12.9767 12 13.1317 12 13.2588 12.0341C13.6039 12.1265 13.8735 12.3961 13.9659 12.7412C14 12.8683 14 13.0233 14 13.3333V14C14 15.1046 13.1046 16 12 16C10.8954 16 10 15.1046 10 14V13.3333Z" />
                    <path
                        d="M13.9 13.5H15.0826C16.3668 13.5 17.0089 13.5 17.5556 13.3842C19.138 13.049 20.429 12.0207 20.9939 10.6455C21.1891 10.1704 21.2687 9.59552 21.428 8.4457C21.4878 8.01405 21.5177 7.79823 21.489 7.62169C21.4052 7.10754 20.9932 6.68638 20.4381 6.54764C20.2475 6.5 20.0065 6.5 19.5244 6.5H4.47562C3.99351 6.5 3.75245 6.5 3.56187 6.54764C3.00682 6.68638 2.59477 7.10754 2.51104 7.62169C2.48229 7.79823 2.51219 8.01405 2.57198 8.4457C2.73128 9.59552 2.81092 10.1704 3.00609 10.6455C3.571 12.0207 4.86198 13.049 6.44436 13.3842C6.99105 13.5 7.63318 13.5 8.91743 13.5H10.1" />
                    <path
                        d="M3.5 11.5V13.5C3.5 17.2712 3.5 19.1569 4.60649 20.3284C5.71297 21.5 7.49383 21.5 11.0556 21.5H12.9444C16.5062 21.5 18.287 21.5 19.3935 20.3284C20.5 19.1569 20.5 17.2712 20.5 13.5V11.5">
                    </path>
                    <path
                        d="M15.5 6.5L15.4227 6.14679C15.0377 4.38673 14.8452 3.50671 14.3869 3.00335C13.9286 2.5 13.3199 2.5 12.1023 2.5H11.8977C10.6801 2.5 10.0714 2.5 9.61309 3.00335C9.15478 3.50671 8.96228 4.38673 8.57727 6.14679L8.5 6.5" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="flex flex-wrap gap-2 mt-3">
        @canany(['admin.administracion.empresa.create', 'admin.administracion.empresa.edit'])
            <x-link-next href="{{ route('admin.administracion.empresa.create') }}" titulo="Perfil Empresa">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M2 8.56907C2 7.37289 2.48238 6.63982 3.48063 6.08428L7.58987 3.79744C9.7431 2.59915 10.8197 2 12 2C13.1803 2 14.2569 2.59915 16.4101 3.79744L20.5194 6.08428C21.5176 6.63982 22 7.3729 22 8.56907C22 8.89343 22 9.05561 21.9646 9.18894C21.7785 9.88945 21.1437 10 20.5307 10H3.46928C2.85627 10 2.22152 9.88944 2.03542 9.18894C2 9.05561 2 8.89343 2 8.56907Z" />
                    <path d="M11.9959 7H12.0049" />
                    <path d="M4 10V18.5M8 10V18.5" />
                    <path d="M16 10V18.5M20 10V18.5" />
                    <path
                        d="M19 18.5H5C3.34315 18.5 2 19.8431 2 21.5C2 21.7761 2.22386 22 2.5 22H21.5C21.7761 22 22 21.7761 22 21.5C22 19.8431 20.6569 18.5 19 18.5Z" />
                </svg>
            </x-link-next>
        @endcanany

        @can('admin.administracion.sucursales')
            <x-link-next href="{{ route('admin.administracion.sucursales') }}" titulo="Sucursales Empresa">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke-width="0.2" stroke="currentColor" fill="currentColor"
                        d="M4 2L4 1.25L3.44607 1.25L3.28317 1.77944L4 2ZM2 8.50006L1.28317 8.2795C1.214 8.5043 1.25429 8.74852 1.392 8.93919L2 8.50006ZM20 2.00008L20.7169 1.77968L20.5541 1.25008L20 1.25008L20 2.00008ZM21.9983 8.50006L22.6063 8.93919C22.744 8.74857 22.7843 8.50442 22.7152 8.27966L21.9983 8.50006ZM10.871 8.18824L11.0592 7.46224L9.60719 7.08587L9.419 7.81188L10.871 8.18824ZM16.625 8.18824L16.8132 7.46224L15.3612 7.08587L15.173 7.81188L16.625 8.18824ZM3.28317 1.77944L1.28317 8.2795L2.71683 8.72062L4.71683 2.22056L3.28317 1.77944ZM19.2831 2.22047L21.2814 8.72046L22.7152 8.27966L20.7169 1.77968L19.2831 2.22047ZM4 2.75L20 2.75008L20 1.25008L4 1.25L4 2.75ZM11.9977 10.2501C11.0787 10.2501 10.2501 9.87627 9.65468 9.27322L8.58728 10.3271C9.45407 11.205 10.663 11.7501 11.9977 11.7501V10.2501ZM17.753 10.2501C16.8338 10.2501 16.0049 9.87604 15.4095 9.27267L14.3419 10.3263C15.2087 11.2047 16.4179 11.7501 17.753 11.7501V10.2501ZM6.24363 10.2501C4.4299 10.2501 3.40735 9.16768 2.608 8.06093L1.392 8.93919C2.26375 10.1462 3.67691 11.7501 6.24363 11.7501V10.2501ZM9.419 7.81188C9.05629 9.21122 7.77386 10.2501 6.24363 10.2501V11.7501C8.46744 11.7501 10.3394 10.2394 10.871 8.18824L9.419 7.81188ZM15.173 7.81188C14.8103 9.21122 13.5279 10.2501 11.9977 10.2501V11.7501C14.2215 11.7501 16.0934 10.2394 16.625 8.18824L15.173 7.81188ZM21.3903 8.06093C20.5912 9.16735 19.567 10.2501 17.753 10.2501V11.7501C20.3195 11.7501 21.7343 10.1465 22.6063 8.93919L21.3903 8.06093Z" />
                    <path d="M6 17H11" />
                    <path
                        d="M18.5 13.5C20.433 13.5 22 15.0376 22 16.9343C22 19.0798 20 20.5 18.5 21.9999C17 20.5 15 19.0351 15 16.9343C15 15.0376 16.567 13.5 18.5 13.5Z" />
                    <path d="M18.5 17L18.509 17" />
                </svg>
            </x-link-next>
        @endcan

        @if (Module::isEnabled('Employer'))
            @can('admin.administracion.employers')
                <x-link-next href="{{ route('admin.administracion.employers') }}" titulo="Personal trabajo">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 9H18" />
                        <path d="M14 12.5H17" />
                        <rect x="2" y="3" width="20" height="18" rx="5" />
                        <path d="M5 16C6.20831 13.4189 10.7122 13.2491 12 16" />
                        <path
                            d="M10.5 9C10.5 10.1046 9.60457 11 8.5 11C7.39543 11 6.5 10.1046 6.5 9C6.5 7.89543 7.39543 7 8.5 7C9.60457 7 10.5 7.89543 10.5 9Z" />
                    </svg>
                </x-link-next>
            @endcan
        @endif

        @can('admin.users')
            <x-link-next href="{{ route('admin.users') }}" titulo="Usuarios">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M18.5045 19.0002H18.9457C19.9805 19.0002 20.8036 18.5287 21.5427 17.8694C23.4202 16.1944 19.0067 14.5 17.5 14.5" />
                    <path
                        d="M15 5.06877C15.2271 5.02373 15.4629 5 15.7048 5C17.5247 5 19 6.34315 19 8C19 9.65685 17.5247 11 15.7048 11C15.4629 11 15.2271 10.9763 15 10.9312" />
                    <path
                        d="M4.78256 15.1112C3.68218 15.743 0.797061 17.0331 2.55429 18.6474C3.41269 19.436 4.36872 20 5.57068 20H12.4293C13.6313 20 14.5873 19.436 15.4457 18.6474C17.2029 17.0331 14.3178 15.743 13.2174 15.1112C10.6371 13.6296 7.36292 13.6296 4.78256 15.1112Z" />
                    <path
                        d="M13 7C13 9.20914 11.2091 11 9 11C6.79086 11 5 9.20914 5 7C5 4.79086 6.79086 3 9 3C11.2091 3 13 4.79086 13 7Z" />
                </svg>
            </x-link-next>
        @endcan

        @if (Module::isEnabled('Ventas'))
            <x-link-next href="{{ route('admin.administracion.pricetypes') }}" titulo="Lista y rangos de precios">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M14.2618 3.59937C13.1956 2.53312 12.6625 2 12 2C11.3375 2 10.8044 2.53312 9.73815 3.59937C9.09832 4.2392 8.46427 4.53626 7.55208 4.53626C6.7556 4.53626 5.62243 4.38178 5 5.00944C4.38249 5.63214 4.53628 6.76065 4.53628 7.55206C4.53628 8.46428 4.2392 9.09832 3.59935 9.73817C2.53312 10.8044 2.00001 11.3375 2 12C2.00002 12.6624 2.53314 13.1956 3.59938 14.2618C4.31616 14.9786 4.53628 15.4414 4.53628 16.4479C4.53628 17.2444 4.38181 18.3776 5.00949 19C5.63218 19.6175 6.76068 19.4637 7.55206 19.4637C8.52349 19.4637 8.99128 19.6537 9.68457 20.347C10.2749 20.9374 11.0663 22 12 22C12.9337 22 13.7251 20.9374 14.3154 20.347C15.0087 19.6537 15.4765 19.4637 16.4479 19.4637C17.2393 19.4637 18.3678 19.6175 18.9905 19M20.4006 9.73817C21.4669 10.8044 22 11.3375 22 12C22 12.6624 21.4669 13.1956 20.4006 14.2618C19.6838 14.9786 19.4637 15.4414 19.4637 16.4479C19.4637 17.2444 19.6182 18.3776 18.9905 19M18.9905 19H19" />
                    <path d="M8 10.3077C8 10.3077 10.25 10 12 14C12 14 17.0588 4 22 2" />
                </svg>
            </x-link-next>
        @endif

        @can('admin.administracion.typecomprobantes')
            <x-link-next href="{{ route('admin.administracion.typecomprobantes') }}" titulo="Tipos de comprobantes">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M12 2H12.7727C16.0339 2 17.6645 2 18.7969 2.79784C19.1214 3.02643 19.4094 3.29752 19.6523 3.60289C20.5 4.66867 20.5 6.20336 20.5 9.27273V11.8182C20.5 14.7814 20.5 16.2629 20.0311 17.4462C19.2772 19.3486 17.6829 20.8491 15.6616 21.5586C14.4044 22 12.8302 22 9.68182 22C7.88275 22 6.98322 22 6.26478 21.7478C5.10979 21.3424 4.19875 20.4849 3.76796 19.3979C3.5 18.7217 3.5 17.8751 3.5 16.1818V11.5" />
                    <path
                        d="M20.5 12C20.5 13.8409 19.0076 15.3333 17.1667 15.3333C16.5009 15.3333 15.716 15.2167 15.0686 15.3901C14.4935 15.5442 14.0442 15.9935 13.8901 16.5686C13.7167 17.216 13.8333 18.0009 13.8333 18.6667C13.8333 20.5076 12.3409 22 10.5 22" />
                    <path
                        d="M7.70569 9.44141C8.45931 10.1862 9.68117 10.1862 10.4348 9.44141C11.1884 8.69662 11.1884 7.48908 10.4348 6.74429L8.7291 5.05859C8.06295 4.40025 7.03095 4.32384 6.27987 4.82935M6.29431 2.55859C5.54069 1.8138 4.31883 1.8138 3.56521 2.55859C2.8116 3.30338 2.8116 4.51092 3.56521 5.25571L5.2709 6.94141C5.94932 7.61187 7.00718 7.67878 7.76133 7.14213" />
                </svg>
            </x-link-next>
        @endcan

        @can('admin.administracion.units')
            <x-link-next href="{{ route('admin.administracion.units') }}" titulo="Unidades medida">
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
        @endcan

        <x-link-next href="{{ route('admin.administracion.areas') }}" titulo="Areas empresa">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8.3 10a.7.7 0 0 1-.626-1.079L11.4 3a.7.7 0 0 1 1.198-.043L16.3 8.9a.7.7 0 0 1-.572 1.1Z" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <circle cx="17.5" cy="17.5" r="3.5" />
            </svg>
        </x-link-next>

        <x-link-next href="#" titulo="Inventario patrimonio">
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

    </div>
</x-admin-layout>

<x-app-layout>
    <div class="pt-4">
        <div class="min-h-screen flex flex-col items-center py-8 sm:pt-0">
            <div
                class="w-full text-sm text-colorsubtitleform sm:max-w-4xl p-0 sm:p-6 bg-fondominicard shadow-md overflow-hidden sm:rounded-xl prose">
                <h1 class="text-colortitle">Nuestras tiendas</h1>

                {{-- <h3 class="text-next-800">Nuestras tiendas</h3> --}}

                @if (count($sucursals) > 0)
                    <div class="w-full flex flex-col gap-2">
                        @foreach ($sucursals as $item)
                            <x-simple-card class="p-3 not-prose">
                                <div class="w-full align-middle flex items-center gap-2 text-colorlabel">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33" fill-rule="evenodd"
                                        clip-rule="evenodd" fill="currentColor"
                                        class="block text-colorsubtitleform flex-shrink-0 w-8 h-8 sm:w-12 sm:h-12">
                                        <path
                                            d="M18.4449 14.2024C19.4296 12.8623 20 11.5761 20 10.5C20 8.29086 18.2091 6.5 16 6.5C13.7909 6.5 12 8.29086 12 10.5C12 11.5761 12.5704 12.8623 13.5551 14.2024C14.3393 15.2698 15.2651 16.2081 16 16.8815C16.7349 16.2081 17.6607 15.2698 18.4449 14.2024ZM16.8669 18.7881C18.5289 17.3455 22 13.9227 22 10.5C22 7.18629 19.3137 4.5 16 4.5C12.6863 4.5 10 7.18629 10 10.5C10 13.9227 13.4712 17.3455 15.1331 18.7881C15.6365 19.2251 16.3635 19.2251 16.8669 18.7881ZM5 11.5H8.27078C8.45724 12.202 8.72804 12.8724 9.04509 13.5H5V26.5H10.5V22C10.5 21.4477 10.9477 21 11.5 21H20.5C21.0523 21 21.5 21.4477 21.5 22V26.5H27V13.5H22.9549C23.272 12.8724 23.5428 12.202 23.7292 11.5H27C28.1046 11.5 29 12.3954 29 13.5V26.5C29.5523 26.5 30 26.9477 30 27.5C30 28.0523 29.5523 28.5 29 28.5H3C2.44772 28.5 2 28.0523 2 27.5C2 26.9477 2.44772 26.5 3 26.5V13.5C3 12.3954 3.89543 11.5 5 11.5ZM19.5 23V26.5H12.5V23H19.5ZM17 10.5C17 11.0523 16.5523 11.5 16 11.5C15.4477 11.5 15 11.0523 15 10.5C15 9.94772 15.4477 9.5 16 9.5C16.5523 9.5 17 9.94772 17 10.5ZM19 10.5C19 12.1569 17.6569 13.5 16 13.5C14.3431 13.5 13 12.1569 13 10.5C13 8.84315 14.3431 7.5 16 7.5C17.6569 7.5 19 8.84315 19 10.5Z" />
                                    </svg>

                                    <div class="flex-1 w-full text-colorlabel">
                                        <h1 class="block w-full text-sm font-semibold {{ $item->isDefault() ? 'text-colortitleform' : 'text-colorlabel' }}">{{ $item->name }}</h1>
                                        <p class="text-[10px] leading-3">
                                            {{ $item->direccion }}
                                             - 
                                            {{ $item->ubigeo->distrito }} /
                                            {{ $item->ubigeo->provincia }} /
                                            {{ $item->ubigeo->region }}
                                        </p>
                                    </div>
                                </div>
                            </x-simple-card>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

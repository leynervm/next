<footer class="w-full footer-marketplace">
    <div
        class="contenedor grid grid-cols-2 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-1 sm:gap-3 mx-auto py-3 md:py-8">
        <div class="w-full max-w-sm h-16 mx-auto col-span-2 md:col-span-1">
            @if ($empresa->image || $empresa->logofooter)
                @if ($empresa->logofooter)
                    <img class="w-full h-full object-scale-down" src="{{ $empresa->getLogoFooterURL() }}"
                        alt="">
                @else
                    <img class="w-full h-full object-scale-down" src="{{ $empresa->image->getLogoEmpresa() }}"
                        alt="">
                @endif
            @else
                <h1 class="text-center p-3 font-bold tracking-widest text-xl leading-5 truncate max-w-xs">
                    {{ $empresa->name }}</h1>
            @endif
        </div>
        <div>
            <ul class="">
                <li>
                    <a class="item-footer text-[10px] !flex flex-col gap-1 items-center justify-center group"
                        href="{{ route('claimbook.create') }}">
                        <div class="block w-10 h-10 text-white group-hover:text-hoverlinkfooter">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="currentColor"
                                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                class="w-full h-full">
                                <path class="cls-1"
                                    d="m22.83,46.25c-1.27-1.37-2.77-1.79-4.49-1.78-5.71.03-11.43.01-17.14.01-.99,0-1.2-.21-1.2-1.21,0-9.46,0-22.25,0-31.71,0-1.06.21-1.27,1.26-1.27.73,0,1.46,0,2.28,0-.13-.25-.21-.41-.3-.57-.93-1.62-1.87-3.24-2.8-4.86C-.4,3.41-.02,1.97,1.42,1.12c.44-.26.89-.52,1.34-.77,1.31-.71,2.76-.33,3.51.96,1.58,2.71,3.16,5.42,4.7,8.16.33.59.66.85,1.38.83,2.16-.06,4.32-.02,6.49-.02,1.65,0,3.14.48,4.43,1.53.06.05.13.09.21.15.39-.51.74-1.02,1.14-1.49,1.96-2.3,4.46-3.84,7.24-4.94,3.12-1.25,6.38-1.85,9.72-2.08,1.07-.07,1.29.15,1.29,1.2,0,1.7.02,3.4,0,5.1,0,.44.14.55.56.54,1.18-.02,2.37-.01,3.55,0,.78,0,1.05.27,1.05,1.06,0,9.59,0,22.53,0,32.12,0,.75-.26,1.01-1.03,1.01-5.77,0-11.53.02-17.3-.01-1.72,0-3.22.41-4.46,1.74.16.01.3.04.44.04,7.05,0,14.11,0,21.16,0,.09,0,.17,0,.26,0,.57.02.92.33.93.81.01.53-.33.86-.93.87-1.18.01-2.37,0-3.55,0-10.45,0-20.9,0-31.35,0-3.64,0-7.28,0-10.91,0-.15,0-.31,0-.46-.01-.5-.06-.79-.39-.78-.86.01-.46.33-.78.83-.81.14,0,.27,0,.41,0,6.95,0,13.9,0,20.85,0h.73ZM1.66,11.98c0,.27,0,.46,0,.64,0,8.73,0,20.81,0,29.54q0,.66.67.66c5.59,0,11.19,0,16.78,0,1.32,0,2.53.4,3.63,1.13.12.08.24.14.41.23.01-.18.02-.28.02-.38,0-8.58,0-20.5,0-29.08,0-.54.1-1.11-.63-1.29-.06-.01-.1-.09-.15-.14-.97-.87-2.11-1.32-3.42-1.32-2.02,0-4.05,0-6.07,0-.12,0-.25.03-.42.05.1.19.16.32.24.45,1.21,2.09,2.42,4.19,3.62,6.28.13.22.27.46.31.7.4,2.3.77,4.6,1.16,6.9.07.44.09.87-.37,1.13-.45.25-.81.05-1.16-.24-1.72-1.42-3.45-2.83-5.16-4.26-.26-.21-.49-.48-.66-.77-1.91-3.28-3.79-6.56-5.7-9.84-.09-.16-.27-.37-.41-.38-.87-.04-1.74-.02-2.69-.02Zm23.17,30.7c.17-.16.25-.22.32-.29,1.15-1.19,2.47-2.18,3.91-2.99,3.61-2.04,7.54-2.96,11.62-3.33.38-.03.52-.12.52-.54-.01-8.77,0-20.87,0-29.64,0-.22,0-.44,0-.67-.17,0-.27-.03-.36-.02-3.54.34-6.98,1.08-10.17,2.72-2.16,1.11-4.09,2.53-5.46,4.58-.22.33-.36.79-.36,1.18-.02,8.34-.01,20.02-.01,28.35,0,.17,0,.34,0,.64Zm18.02-30.67c0,.25,0,.47,0,.69,0,6.81,0,16.97,0,23.78,0,.9-.21,1.08-1.09,1.19-1.92.25-3.85.45-5.74.86-3.2.7-6.16,1.98-8.69,4.13-.15.13-.29.26-.34.32,1.2-.05,2.44-.14,3.68-.14,5.01-.02,10.02-.01,15.03,0,.51,0,.66-.11.66-.65-.02-8.73-.01-20.81-.01-29.54v-.64h-3.49Zm-28.81,6.14c-.1-.19-.2-.36-.3-.54-1.8-3.12-3.6-6.24-5.4-9.35-1.15-1.99-2.3-3.98-3.45-5.97-.39-.66-.79-.77-1.45-.4-.31.18-.62.36-.94.54-.96.55-1.05.85-.5,1.8,2.92,5.06,5.85,10.12,8.77,15.19.08.14.17.28.27.43q.94-1.2,2.99-1.7Zm1.68,6.45c.03-.11.05-.16.05-.2-.22-1.33-.43-2.67-.66-4-.02-.11-.13-.3-.21-.3-.57-.04-1.17-.16-1.7-.01-.63.18-.84.85-.89,1.45-.02.23.35.51.58.74.25.25.55.47.82.7.66.54,1.32,1.07,2.01,1.64Z" />
                            </svg>
                        </div>
                        LIBRO DE RECLAMACIONES
                    </a>
                </li>
                <li>
                    <a class="item-footer text-[10px]" href="{{ route('nosotros') }}">NOSOTROS</a>
                </li>
                <li>
                    <a class="item-footer text-[10px]" href="{{ route('centroautorizado') }}">CENTRO AUTORIZADO</a>
                </li>
                <li>
                    <a class="item-footer text-[10px]" href="{{ route('contactanos') }}">CONTÁCTANOS</a>
                </li>
                <li>
                    <a class="item-footer text-[10px]" href="{{ route('ubicanos') }}">UBÍCANOS</a>
                </li>
            </ul>
        </div>
        <div>
            <ul>
                <li>
                    <a class="item-footer text-[10px]" href="{{ route('trabaja') }}">TRABAJA CON NOSOTROS</a>
                </li>
                <li>
                    <a class="item-footer text-[10px]" href="{{ route('terms.show') }}">TÉRMINOS Y CONDICIONES</a>
                </li>
                <li>
                    <a class="item-footer text-[10px]" href="{{ route('policy.show') }}">POLÍTICAS DE PRIVACIDAD</a>
                </li>
            </ul>
        </div>
        <div class="col-span-2 md:col-span-3 lg:col-span-2">
            <div class="w-full h-48 xs:h-64 lg:h-52 px-1">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1985.024222362466!2d-78.80980694270073!3d-5.706141436028993!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91b4fbc87b6535f7%3A0x7b9aa3d2c1e84bd1!2sNEXT%20TECHNOLOGIES!5e0!3m2!1ses-419!2spe!4v1725493222836!5m2!1ses-419!2spe"
                    class="w-full h-full rounded-sm" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <div class="w-full flex gap-2 mt-2">
                <ul class="flex-1 !p-0 !flex flex-col gap-1">
                    @if ($empresa->email)
                        <li>
                            <p class="text-left">{{ $empresa->email }}</p>
                        </li>
                    @endif
                    @if ($empresa->direccion)
                        <li>
                            <p class="leading-3 text-xs text-left">{{ $empresa->direccion }}</p>
                        </li>
                    @endif
                    @if (count($empresa->telephones) > 0)
                        <li>
                            <div class="w-full text-left mt-2 flex flex-wrap gap-y-1 gap-x-4 justify-start">
                                @foreach ($empresa->telephones as $item)
                                    <small class="tracking-wide leading-3 text-xs">
                                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="inline-block animate-bounce w-3 h-3 text-white">
                                            <path
                                                d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                        </svg>
                                        {{ formatTelefono($item->phone) }}
                                    </small>
                                @endforeach
                            </div>
                        </li>
                    @endif
                </ul>
                <div class="flex-shrink-0 w-auto max-w-full h-20 mx-auto">
                    <img class="w-full h-full object-scale-down object-center"
                        src="{{ asset('images/niubiz_footer.png') }}" alt="paga-con-niubiz" title="paga-con-niubiz">
                </div>
            </div>


            <div class="footer-social w-full flex flex-wrap justify-center items-center gap-3">
                @if ($empresa->whatsapp)
                    <a class="social-footer whatsapp" target="_blank" href="{{ $empresa->whatsapp }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" color="currentColor"
                            fill="currentColor" stroke="currentColor" stroke-width="0.7" stroke-linejoin="round"
                            class="block w-full h-full">
                            <path
                                d=" M19.11 17.205c-.372 0-1.088 1.39-1.518 1.39a.63.63 0 0 1-.315-.1c-.802-.402-1.504-.817-2.163-1.447-.545-.516-1.146-1.29-1.46-1.963a.426.426 0 0 1-.073-.215c0-.33.99-.945.99-1.49 0-.143-.73-2.09-.832-2.335-.143-.372-.214-.487-.6-.487-.187 0-.36-.043-.53-.043-.302 0-.53.115-.746.315-.688.645-1.032 1.318-1.06 2.264v.114c-.015.99.472 1.977 1.017 2.78 1.23 1.82 2.506 3.41 4.554 4.34.616.287 2.035.888 2.722.888.817 0 2.15-.515 2.478-1.318.13-.33.244-.73.244-1.088 0-.058 0-.144-.03-.215-.1-.172-2.434-1.39-2.678-1.39zm-2.908 7.593c-1.747 0-3.48-.53-4.942-1.49L7.793 24.41l1.132-3.337a8.955 8.955 0 0 1-1.72-5.272c0-4.955 4.04-8.995 8.997-8.995S25.2 10.845 25.2 15.8c0 4.958-4.04 8.998-8.998 8.998zm0-19.798c-5.96 0-10.8 4.842-10.8 10.8 0 1.964.53 3.898 1.546 5.574L5 27.176l5.974-1.92a10.807 10.807 0 0 0 16.03-9.455c0-5.958-4.842-10.8-10.802-10.8z" />
                        </svg>
                    </a>
                @endif

                @if ($empresa->facebook)
                    <a href="{{ $empresa->facebook }}" target="_blank" class="social-footer facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                            fill="currentColor" stroke="currentColor" stroke-width="0" stroke-linejoin="round"
                            class="block w-full h-full">
                            <path
                                d="M6.18182 10.3333C5.20406 10.3333 5 10.5252 5 11.4444V13.1111C5 14.0304 5.20406 14.2222 6.18182 14.2222H8.54545V20.8889C8.54545 21.8081 8.74951 22 9.72727 22H12.0909C13.0687 22 13.2727 21.8081 13.2727 20.8889V14.2222H15.9267C16.6683 14.2222 16.8594 14.0867 17.0631 13.4164L17.5696 11.7497C17.9185 10.6014 17.7035 10.3333 16.4332 10.3333H13.2727V7.55556C13.2727 6.94191 13.8018 6.44444 14.4545 6.44444H17.8182C18.7959 6.44444 19 6.25259 19 5.33333V3.11111C19 2.19185 18.7959 2 17.8182 2H14.4545C11.191 2 8.54545 4.48731 8.54545 7.55556V10.3333H6.18182Z" />
                        </svg>
                    </a>
                @endif
                @if ($empresa->youtube)
                    <a href="{{ $empresa->youtube }}" target="_blank" class="social-footer youtube">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 310 310" color="currentColor"
                            fill="currentColor" stroke="currentColor" stroke-width="0" stroke-linejoin="round"
                            class="block w-full h-full p-1">
                            <path
                                d="M297.917,64.645c-11.19-13.302-31.85-18.728-71.306-18.728H83.386c-40.359,0-61.369,5.776-72.517,19.938   C0,79.663,0,100.008,0,128.166v53.669c0,54.551,12.896,82.248,83.386,82.248h143.226c34.216,0,53.176-4.788,65.442-16.527   C304.633,235.518,310,215.863,310,181.835v-53.669C310,98.471,309.159,78.006,297.917,64.645z M199.021,162.41l-65.038,33.991   c-1.454,0.76-3.044,1.137-4.632,1.137c-1.798,0-3.592-0.484-5.181-1.446c-2.992-1.813-4.819-5.056-4.819-8.554v-67.764   c0-3.492,1.822-6.732,4.808-8.546c2.987-1.814,6.702-1.938,9.801-0.328l65.038,33.772c3.309,1.718,5.387,5.134,5.392,8.861   C204.394,157.263,202.325,160.684,199.021,162.41z" />
                        </svg>
                    </a>
                @endif

                @if ($empresa->instagram)
                    <a href="{{ $empresa->instagram }}" target="_blank" class="social-footer instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"
                            class="block w-full h-full">
                            <path
                                d="M2.5 12C2.5 7.52166 2.5 5.28249 3.89124 3.89124C5.28249 2.5 7.52166 2.5 12 2.5C16.4783 2.5 18.7175 2.5 20.1088 3.89124C21.5 5.28249 21.5 7.52166 21.5 12C21.5 16.4783 21.5 18.7175 20.1088 20.1088C18.7175 21.5 16.4783 21.5 12 21.5C7.52166 21.5 5.28249 21.5 3.89124 20.1088C2.5 18.7175 2.5 16.4783 2.5 12Z" />
                            <path
                                d="M16.5 12C16.5 14.4853 14.4853 16.5 12 16.5C9.51472 16.5 7.5 14.4853 7.5 12C7.5 9.51472 9.51472 7.5 12 7.5C14.4853 7.5 16.5 9.51472 16.5 12Z" />
                            <path d="M17.5078 6.5L17.4988 6.5" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                @endif

                @if ($empresa->tiktok)
                    <a href="{{ $empresa->tiktok }}" target="_blank" class="social-footer tiktok">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"
                            class="block w-full h-full">
                            <path
                                d="M2.5 12C2.5 7.52166 2.5 5.28249 3.89124 3.89124C5.28249 2.5 7.52166 2.5 12 2.5C16.4783 2.5 18.7175 2.5 20.1088 3.89124C21.5 5.28249 21.5 7.52166 21.5 12C21.5 16.4783 21.5 18.7175 20.1088 20.1088C18.7175 21.5 16.4783 21.5 12 21.5C7.52166 21.5 5.28249 21.5 3.89124 20.1088C2.5 18.7175 2.5 16.4783 2.5 12Z" />
                            <path
                                d="M10.5359 11.0075C9.71585 10.8916 7.84666 11.0834 6.93011 12.7782C6.01355 14.4729 6.9373 16.2368 7.51374 16.9069C8.08298 17.5338 9.89226 18.721 11.8114 17.5619C12.2871 17.2746 12.8797 17.0603 13.552 14.8153L13.4738 5.98145C13.3441 6.95419 14.4186 9.23575 17.478 9.5057" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</footer>

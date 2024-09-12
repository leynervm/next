<footer class="w-full footer-marketplace">
    <div
        class="contenedor grid grid-cols-2 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-1 sm:gap-3 mx-auto py-3 md:py-10">
        <div class="w-full max-w-sm h-16 mx-auto col-span-2 md:col-span-1">
            @if ($empresa->image)
                <img class="w-full h-full object-scale-down" src="{{ $empresa->image->getLogoEmpresa() }}" alt="">
            @else
                <h1 class="text-center p-3 font-bold tracking-widest text-xl leading-5 truncate max-w-xs">
                    {{ $empresa->name }}</h1>
            @endif
        </div>
        <div>
            <ul class="">
                <li>
                    <a class="item-footer !flex flex-col gap-1 items-center justify-center"
                        href="{{ route('claimbook.create') }}">
                        <div class="inline-block w-10 h-8 bg-white p-0.5 rounded">
                            <img class="w-full h-full object-scale-down overflow-hidden"
                                src="{{ asset('images/libro-negro.png') }}" alt="">
                        </div>
                        LIBRO DE RECLAMACIONES
                    </a>
                </li>
                <li>
                    <a class="item-footer" href="{{ route('nosotros') }}">NOSOTROS</a>
                </li>
                <li>
                    <a class="item-footer" href="{{ route('centroautorizado') }}">CENTRO AUTORIZADO</a>
                </li>
                <li>
                    <a class="item-footer" href="{{ route('contactanos') }}">CONTÁCTANOS</a>
                </li>
                <li>
                    <a class="item-footer" href="{{ route('ubicanos') }}">UBÍCANOS</a>
                </li>
            </ul>
        </div>
        <div>
            <ul>
                <li>
                    <a class="item-footer" href="{{ route('trabaja') }}">TRABAJA CON NOSOTROS</a>
                </li>
                <li>
                    <a class="item-footer" href="{{ route('terms.show') }}">TÉRMINOS Y CONDICIONES</a>
                </li>
                <li>
                    <a class="item-footer" href="{{ route('policy.show') }}">POLÍTICAS DE PRIVACIDAD</a>
                </li>
            </ul>
        </div>
        <div class="col-span-2 md:col-span-3 lg:col-span-1 xl:col-span-2">
            <div class="w-full h-48 xs:h-64 lg:h-48 xl:h-64 px-1">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1985.024222362466!2d-78.80980694270073!3d-5.706141436028993!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91b4fbc87b6535f7%3A0x7b9aa3d2c1e84bd1!2sNEXT%20TECHNOLOGIES!5e0!3m2!1ses-419!2spe!4v1725493222836!5m2!1ses-419!2spe"
                    class="w-full h-full" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <ul>
                @if ($empresa->email)
                    <li>
                        <a href="">{{ $empresa->email }}</a>
                    </li>
                @endif
                @if ($empresa->direccion)
                    <li>
                        <p class="leading-3 text-xs text-center">{{ $empresa->direccion }}</p>
                    </li>
                @endif
                @if (count($empresa->telephones) > 0)
                    <li>
                        <div class="w-full mt-2 flex flex-wrap gap-2 justify-around">
                            @foreach ($empresa->telephones as $item)
                                <small class="tracking-wide leading-3 text-xs">
                                    {{ formatTelefono($item->phone) }}
                                </small>
                            @endforeach
                        </div>
                    </li>
                @endif
            </ul>
            <div class="footer-social w-full flex flex-wrap justify-center items-center gap-3">
                @if ($empresa->whatsapp)
                    <a class="social-footer whatsapp" target="_blank" href="https://wa.link/0fnbp6">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" color="currentColor"
                            fill="currentColor" stroke="currentColor" stroke-width="0" stroke-linejoin="round"
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

                @if ($empresa->instagram)
                    <a href="{{ $empresa->instagram }}" target="_blank" class="social-footer instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linejoin="round" class="block w-full h-full">
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linejoin="round" class="block w-full h-full">
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

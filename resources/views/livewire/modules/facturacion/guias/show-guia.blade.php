<div>
    {{-- {{ $guia }} --}}
    <div class="w-full flex flex-col gap-8" x-data="loader" x-init="init">
        <div class="w-full flex flex-col gap-8">
            <x-form-card titulo="RESUMEN GUÍA REMISIÓN">
                <p class="text-colorminicard text-xl font-semibold">
                    {{ $guia->seriecomprobante->typecomprobante->name }} - {{ $guia->seriecompleta }}
                </p>

                <div class="w-full flex flex-col gap-2 bg-body p-3 rounded-md">

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        MOTIVO TRASLADO :
                        <span class="font-medium inline-block">{{ $guia->motivotraslado->name }}</span>
                    </h1>

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        MODALIDAD TRANSPORTE :
                        <span class="font-medium inline-block">{{ $guia->modalidadtransporte->name }}</span>
                    </h1>

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        FECHA TRASLADO :
                        <span class="font-medium inline-block">{{ formatDate($guia->datetraslado, 'DD MMMM Y') }}</span>
                    </h1>

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        PESO BRUTO TOTAL :
                        <span class="font-medium inline-block">{{ $guia->peso }} {{ $guia->unit }}</span>
                    </h1>

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        DESCRIPCIÓN :
                        <span class="font-medium inline-block">{{ $guia->note }}</span>
                    </h1>

                    @if ($guia->indicadorvehiculosml)
                        <h1 class="text-colorminicard text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            PLACA VEHÍCULO :
                            <span class="font-medium inline-block">{{ $guia->placavehiculo }}</span>
                        </h1>
                    @endif

                    @if ($guia->comprobante)
                        <h1 class="text-colorminicard text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            COMPROBANTE REFERENCIA EMITIDO :
                            <span class="font-medium inline-block">{{ $guia->comprobante->seriecompleta }}</span>
                        </h1>
                    @endif

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        TRASLADO EN VEHÍCULOS DE CATEGORÍA M1 O L :
                        <span class="font-medium inline-block">
                            {{ $guia->indicadorvehiculosml == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        RETORNO DE VEHÍCULO VACÍO :
                        <span class="font-medium inline-block">
                            {{ $guia->indicadorvehretorvacio == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        RETORNO DE VEHÍCULO CON ENVASES O EMBALAJES VACÍOS :
                        <span class="font-medium inline-block">
                            {{ $guia->indicadorvehretorenvacios == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        TRANSBORDO PROGRAMADO :
                        <span class="font-medium inline-block">
                            {{ $guia->indicadortransbordo == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        TRASLADO TOTAL DE LA DAM O LA DS :
                        <span class="font-medium inline-block">
                            {{ $guia->indicadordamds == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        REGISTRO DE VEHÍCULOS Y CONDUCTORES DEL TRANSPORTISTA :
                        <span class="font-medium inline-block">
                            {{ $guia->indicadorconductor == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    @if ($guia->motivotraslado->code == '03' || $guia->motivotraslado->code == '13')
                        <h1 class="text-colorminicard text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            COMPRADOR :
                            <span class="font-medium inline-block">
                                @if ($guia->motivotraslado->code == '03' || $guia->motivotraslado->code == '13')
                                    {{ $guia->client->document }},
                                    {{ $guia->client->name }}
                                @endif
                            </span>
                        </h1>
                    @endif

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        LUGAR DE EMISIÓN :
                        <span class="font-medium inline-block">
                            {{ $guia->ubigeoorigen->region }},
                            {{ $guia->ubigeoorigen->provincia }},
                            {{ $guia->ubigeoorigen->distrito }},
                            {{ $guia->ubigeoorigen->ubigeo_inei }}
                            @if ($guia->motivotraslado->code == '04')
                                (ANEXO : {{ $guia->anexoorigen }})
                            @endif
                        </span>
                    </h1>

                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        LUGAR DE DESTINO :
                        <span class="font-medium inline-block">
                            {{ $guia->ubigeodestino->region }},
                            {{ $guia->ubigeodestino->provincia }},
                            {{ $guia->ubigeodestino->distrito }},
                            {{ $guia->ubigeodestino->ubigeo_inei }}
                            @if ($guia->motivotraslado->code == '04')
                                (ANEXO : {{ $guia->anexodestino }})
                            @endif
                        </span>
                    </h1>
                </div>
            </x-form-card>

            <x-form-card titulo="DESTINATARIO">
                <div class="w-full bg-body p-3 rounded-md">
                    <h1 class="text-colorminicard text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        DESTINATARIO :
                        <span class="font-medium inline-block">
                            {{ $guia->documentdestinatario }},
                            {{ $guia->namedestinatario }}</span>
                    </h1>
                </div>
            </x-form-card>

            @if ($guia->modalidadtransporte->code == '01' && $guia->indicadorvehiculosml == '0')
                <x-form-card titulo="TRANSPORTISTA">
                    <div class="w-full bg-body p-3 rounded-md">
                        <h1 class="text-colorminicard text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            TRANSPORTISTA :
                            <span class="font-medium inline-block">
                                {{ $guia->ructransport }},
                                {{ $guia->nametransport }}</span>
                        </h1>
                    </div>
                </x-form-card>
            @endif

            @if ($guia->motivotraslado->code == '02' || $guia->motivotraslado->code == '07' || $guia->motivotraslado->code == '13')
                <x-form-card titulo="PROVEEDOR" class="animate__animated animate__fadeInDown">
                    <div class="w-full bg-body p-3 rounded-md">
                        <h1 class="text-colorminicard text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            PROVEEDOR :
                            <span class="font-medium inline-block">
                                {{ $guia->rucproveedor }},
                                {{ $guia->nameproveedor }}</span>
                        </h1>
                    </div>
                </x-form-card>
            @endif

            {{-- @if ($guia->codesunat != '0')
                <div class="w-full flex justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">{{ __('ACTUALIZAR') }}</x-button>
                </div>
            @endif --}}

            {{-- <div>
                <x-jet-input-error for="guia.documentdestinatario" />
                <x-jet-input-error for="guia.namedestinatario" />
                {{ print_r($errors->all()) }}
            </div> --}}
        </div>

        @if ($guia->modalidadtransporte->code == '02' && $guia->indicadorvehiculosml == '0')
            <x-form-card titulo="CONDUCTORES VEHÍCULO">
                <div class="w-full relative rounded flex flex-wrap lg:flex-nowrap gap-3">
                    @if ($guia->codesunat != '0')
                        <div class="w-full lg:w-96 lg:flex-shrink-0 bg-body p-3 rounded relative"
                            x-data="{ loading: false }">
                            <form wire:submit.prevent="savedriver" class="w-full flex flex-col gap-2">
                                <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-1">
                                    <div class="w-full">
                                        <x-label value="Documento :" />
                                        <div class="w-full inline-flex">
                                            <x-input class="block w-full prevent numeric"
                                                wire:model.defer="documentdriver" wire:keydown.enter="getDriver"
                                                minlength="0" maxlength="11" />
                                            <x-button-add class="px-2" wire:click="getDriver"
                                                wire:loading.attr="disable">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="11" cy="11" r="8" />
                                                    <path d="m21 21-4.3-4.3" />
                                                </svg>
                                            </x-button-add>
                                        </div>
                                        <x-jet-input-error for="documentdriver" />
                                    </div>

                                    <div class="w-full">
                                        <x-label value="Nombres :" />
                                        <x-input class="block w-full" wire:model.defer="namedriver"
                                            placeholder="Nombres del conductor del vehículo..." />
                                        <x-jet-input-error for="namedriver" />
                                    </div>

                                    <div class="w-full">
                                        <x-label value="Apellidos :" />
                                        <x-input class="block w-full" wire:model.defer="lastname"
                                            placeholder="Nombres del conductor del vehículo..." />
                                        <x-jet-input-error for="lastname" />
                                    </div>

                                    <div class="w-full">
                                        <x-label value="Licencia conducir:" />
                                        <x-input class="block w-full" wire:model.defer="licencia"
                                            placeholder="Licencia del conductor del vehículo..." />
                                        <x-jet-input-error for="licencia" />
                                    </div>
                                </div>
                                <div class="w-full flex justify-end">
                                    <x-button type="submit">{{ __('REGISTRAR') }}</x-button>
                                </div>
                            </form>
                        </div>
                    @endif
                    <div class="w-full relative rounded">
                        @if (count($guia->transportdrivers))
                            <div class="w-full">
                                <x-table>
                                    <x-slot name="header">
                                        <tr>
                                            <th class="p-2 text-left">DOCUMENTO</th>
                                            <th class="p-2 text-left">NOMBRES</th>
                                            <th class="p-2 text-center">LICENCIA</th>
                                            @if ($guia->codesunat != '0')
                                                <th class="p-2">OPCIONES</th>
                                            @endif
                                        </tr>
                                    </x-slot>
                                    <x-slot name="body">
                                        @foreach ($guia->transportdrivers as $item)
                                            <tr class="text-[10px]">
                                                <td class="p-2">
                                                    {{ $item->document }}
                                                    @if ($item->principal)
                                                        <x-span-text text="PRINCIPAL"
                                                            class="!tracking-normal leading-3 ml-1" type="green" />
                                                    @else
                                                        <x-span-text text="SECUNDARIO"
                                                            class="!tracking-normal leading-3 ml-1" />
                                                    @endif
                                                </td>
                                                <td class="p-2">{{ $item->name }} {{ $item->lastname }}</td>
                                                <td class="p-2 text-center">{{ $item->licencia }}</td>
                                                @if ($guia->codesunat != '0')
                                                    <td class="text-center align-middle">
                                                        <x-button-delete
                                                            wire:click="$emit('guia.confirmDeletedriver',{{ $item }})"
                                                            wire:loading.attr="disabled" />
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </x-slot>
                                </x-table>
                            </div>
                        @endif
                    </div>
                </div>
            </x-form-card>

            <x-form-card titulo="VEHÍCULOS TRANSPORTE">
                <div class="w-full relative rounded flex flex-wrap md:flex-nowrap gap-3">
                    @if ($guia->codesunat != '0')
                        <div class="w-full md:w-96 md:flex-shrink-0 bg-body p-3 rounded relative"
                            x-data="{ loading: false }">
                            <form wire:submit.prevent="savevehiculo" class="w-full flex flex-col gap-2">
                                <div class="w-full">
                                    <x-label value="Placa vehículo :" />
                                    <x-input class="block w-full" wire:model.defer="placa"
                                        placeholder="placa del del vehículo transporte..." />
                                    <x-jet-input-error for="placa" />
                                </div>

                                <div class="w-full mt-3 flex justify-end">
                                    <x-button type="submit">{{ __('AGREGAR') }}</x-button>
                                </div>
                            </form>

                            <div x-show="loading" wire:loading.flex wire:target="savevehiculo"
                                class="loading-overlay rounded">
                                <x-loading-next />
                            </div>
                        </div>
                    @endif
                    <div class="w-full relative rounded">
                        @if (count($guia->transportvehiculos))
                            <div class="w-full flex flex-wrap items-start gap-2">
                                @foreach ($guia->transportvehiculos as $item)
                                    <x-minicard :title="'PLACA: ' . $item->placa" size="md">

                                        <div class="text-center">
                                            <x-span-text :text="$item->principal == 1 ? 'PRINCIPAL' : 'SECUNDARIO'" class="leading-3 !tracking-normal"
                                                :type="$item->principal == 1 ? 'green' : ''" />
                                        </div>

                                        @if ($guia->codesunat != '0')
                                            <x-slot name="buttons">
                                                <x-button-delete
                                                    wire:click="$emit('guia.confirmDeletevehiculo',{{ $item }})"
                                                    wire:loading.attr="disabled" />
                                            </x-slot>
                                        @endif
                                    </x-minicard>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </x-form-card>
        @endif

        <x-form-card titulo="RESUMEN PRODUCTOS">
            <div class="w-full">
                @if (count($guia->tvitems))
                    <div class="flex gap-2 flex-wrap justify-start">
                        @foreach ($guia->tvitems as $item)
                            @php
                                $image = null;
                                if (count($item->producto->images)) {
                                    if (count($item->producto->defaultImage)) {
                                        $image = asset('storage/productos/' . $item->producto->defaultImage->first()->url);
                                    } else {
                                        $image = asset('storage/productos/' . $item->producto->images->first()->url);
                                    }
                                }
                            @endphp

                            <x-card-producto :name="$item->producto->name" :image="$image" :almacen="$item->almacen->name">
                                <div class="w-full mt-1 flex flex-wrap gap-1 items-start">
                                    <x-span-text :text="formatDecimalOrInteger($item->cantidad) .
                                        ' ' .
                                        $item->producto->unit->name" class="leading-3 !tracking-normal" />

                                    <x-span-text :text="$item->alterstock == '0' ? 'NO ALTERARÓ STOCK' : 'STOCK ALTERADO'" class="leading-3 !tracking-normal" />

                                    @if (count($item->itemseries) == 1)
                                        <x-span-text :text="'SERIE :' . $item->itemseries()->first()->serie->serie" class="leading-3 !tracking-normal" />
                                    @endif
                                </div>

                                @if (count($item->itemseries) > 1)
                                    <div x-data="{ showForm: false }" class="mt-1">
                                        <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                            {{ __('VER SERIES') }}
                                        </x-button>
                                        <div x-show="showForm"
                                            x-transition:enter="transition ease-out duration-300 transform"
                                            x-transition:enter-start="opacity-0 translate-y-[-10%]"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-300 transform"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 translate-y-[-10%]"
                                            class="block w-full rounded mt-1">
                                            <div class="w-full flex flex-wrap gap-1">
                                                @foreach ($item->itemseries as $serie)
                                                    <x-span-text :text="$serie->serie->serie" class="leading-3" />
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </x-card-producto>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-form-card>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loader', () => ({
                vehiculosml: false,
                loadingprivate: false,
                loadingpublic: false,
                loadingdestinatario: false,
                loadingcomprador: false,
                loadingproveedor: false,
                codemotivotraslado: '',
                codemodalidad: '',

                init() {
                    // const selectmotivo = this.$refs.selectmotivo;
                    // const selectmodalidad = this.$refs.selectmodalidad;
                    // this.vehiculosml = this.$refs.checkvehiculosml.checked;

                    // this.getCodeMotivo(selectmotivo);
                    // this.getCodeModalidad(selectmodalidad);
                },

                toggle() {
                    this.vehiculosml = !this.vehiculosml;
                    if (this.vehiculosml) {
                        this.loadingpublic = false;
                        this.loadingprivate = false;
                    } else {
                        this.selectedModalidadtransporte(this.codemodalidad);
                    }
                },
                toggleComprador() {
                    this.loadingcomprador = false;
                },
                getCodeMotivo(target) {
                    this.codemotivotraslado = target.options[target.selectedIndex].getAttribute(
                        'data-code');
                    this.selectedMotivotraslado(this.codemotivotraslado);
                },
                getCodeModalidad(target) {
                    this.codemodalidad = target.options[target.selectedIndex].getAttribute(
                        'data-code');
                    if (!this.vehiculosml) {
                        this.selectedModalidadtransporte(this.codemodalidad);
                    }
                },
                selectedModalidadtransporte(value) {
                    switch (value) {
                        case '01':
                            this.loadingpublic = true;
                            this.loadingprivate = false;
                            break;
                        case '02':
                            this.loadingprivate = true;
                            this.loadingpublic = false;
                            break;
                        default:
                            this.loadingprivate = false;
                            this.loadingpublic = false;
                    }
                },
                selectedMotivotraslado(value) {
                    switch (value) {
                        case '01': //VENTA
                            this.loadingdestinatario = true;
                            this.loadingcomprador = false;
                            this.loadingproveedor = false;
                            this.loadingpackages = false;
                            break;
                        case '02': //COMPRA
                            this.loadingdestinatario = false;
                            this.loadingcomprador = false;
                            this.loadingproveedor = true;
                            this.loadingpackages = false;
                            break;
                        case '03': //VENTA TERCEROS
                            this.loadingdestinatario = true;
                            this.loadingcomprador = true;
                            this.loadingproveedor = false;
                            this.loadingpackages = false;
                            break;
                        case '04': //TRASLADO ESTABLECIMIENTOS
                            this.loadingdestinatario = false;
                            this.loadingcomprador = false;
                            this.loadingproveedor = false;
                            this.loadingpackages = false;
                            break;
                        case '05': //CONSIGNACION
                            this.loadingdestinatario = true;
                            this.loadingproveedor = false;
                            this.loadingcomprador = false;
                            this.loadingpackages = false;
                            break;
                        case '06': //DEVOLUCION
                            this.loadingdestinatario = true;
                            this.loadingproveedor = false;
                            this.loadingcomprador = false;
                            this.loadingpackages = false;
                            break;
                        case '13': //OTROS
                            this.loadingdestinatario = true;
                            this.loadingproveedor = true;
                            this.loadingcomprador = true;
                            this.loadingpackages = false;
                            break;
                        case '14': //VENTA SUJETA CONFIRMACION
                            this.loadingdestinatario = true;
                            this.loadingproveedor = false;
                            this.loadingcomprador = false;
                            this.loadingpackages = false;
                            break;
                        default:
                            this.loadingdestinatario = false;
                            // this.loadingprivate = false;
                            // this.loadingpublic = false;
                            this.loadingdestinatario = false;
                            this.loadingcomprador = false;
                            this.loadingproveedor = false;
                            this.loadingpackages = false;
                    }

                    if (this.codemodalidad == '' || this.vehiculosml) {
                        this.loadingprivate = false;
                        this.loadingpublic = false;
                    }
                },
                resetMotivotraslado() {
                    this.codemotivotraslado = '';
                    this.loadingdestinatario = false;
                    this.loadingprivate = false;
                    this.loadingpublic = false;
                    this.loadingdestinatario = false;
                    this.loadingcomprador = false;
                    this.loadingproveedor = false;
                }
            }))
        })

        document.addEventListener("livewire:load", () => {
            Livewire.on('guia.confirmDeletevehiculo', data => {
                swal.fire({
                    title: 'Eliminar vehículo con placa, ' + data.placa,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deletevehiculo(data.id);
                    }
                })
            });

            Livewire.on('guia.confirmDeletedriver', data => {
                swal.fire({
                    title: 'Eliminar conductor, ' + data.name + ' ' + data.lastname,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deletedriver(data.id);
                    }
                })
            });
        })
    </script>
</div>

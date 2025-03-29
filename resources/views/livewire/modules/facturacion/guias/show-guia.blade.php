<div>
    <div wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    <div class="w-full flex flex-col gap-8">
        <div class="w-full flex flex-col gap-8">
            <x-form-card titulo="RESUMEN GUÍA REMISIÓN">
                <p class="text-colorlabel text-xl font-semibold">
                    {{ $guia->seriecompleta }}
                    <small class="text-sm">{{ $guia->seriecomprobante->typecomprobante->name }}</small>
                </p>

                <div class="w-full flex flex-col gap-2 mt-3">
                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        MOTIVO TRASLADO :
                        <span
                            class="font-medium inline-block text-colorsubtitleform">{{ $guia->motivotraslado->name }}</span>
                    </h1>

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        MODALIDAD TRANSPORTE :
                        <span
                            class="font-medium inline-block text-colorsubtitleform">{{ $guia->modalidadtransporte->name }}</span>
                    </h1>

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        FECHA TRASLADO :
                        <span
                            class="font-medium inline-block text-colorsubtitleform">{{ formatDate($guia->datetraslado, 'DD MMMM Y') }}</span>
                    </h1>

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        PESO BRUTO TOTAL :
                        <span class="font-medium inline-block text-colorsubtitleform">{{ $guia->peso }}
                            {{ $guia->unit }}</span>
                    </h1>

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        DESCRIPCIÓN :
                        <span class="font-medium inline-block text-colorsubtitleform">{{ $guia->note }}</span>
                    </h1>

                    @if ($guia->indicadorvehiculosml)
                        <h1 class="text-colorlabel text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            PLACA VEHÍCULO :
                            <span
                                class="font-medium inline-block text-colorsubtitleform">{{ $guia->placavehiculo }}</span>
                        </h1>
                    @endif

                    @if ($guia->comprobante)
                        <h1 class="text-colorlabel text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            COMPROBANTE REFERENCIA EMITIDO :
                            <span
                                class="font-medium inline-block text-colorsubtitleform">{{ $guia->comprobante->seriecompleta }}</span>
                        </h1>
                    @endif

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        TRASLADO EN VEHÍCULOS DE CATEGORÍA M1 O L :
                        <span class="font-medium inline-block text-colorsubtitleform">
                            {{ $guia->indicadorvehiculosml == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        RETORNO DE VEHÍCULO VACÍO :
                        <span class="font-medium inline-block text-colorsubtitleform">
                            {{ $guia->indicadorvehretorvacio == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        RETORNO DE VEHÍCULO CON ENVASES O EMBALAJES VACÍOS :
                        <span class="font-medium inline-block text-colorsubtitleform">
                            {{ $guia->indicadorvehretorenvacios == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        TRANSBORDO PROGRAMADO :
                        <span class="font-medium inline-block text-colorsubtitleform">
                            {{ $guia->indicadortransbordo == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        TRASLADO TOTAL DE LA DAM O LA DS :
                        <span class="font-medium inline-block text-colorsubtitleform">
                            {{ $guia->indicadordamds == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        REGISTRO DE VEHÍCULOS Y CONDUCTORES DEL TRANSPORTISTA :
                        <span class="font-medium inline-block text-colorsubtitleform">
                            {{ $guia->indicadorconductor == 1 ? 'SI' : 'NO' }}</span>
                    </h1>

                    @if ($guia->motivotraslado->code == '03' || $guia->motivotraslado->code == '13')
                        <h1 class="text-colorlabel text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            COMPRADOR :
                            <span class="font-medium inline-block text-colorsubtitleform">
                                @if ($guia->motivotraslado->code == '03' || $guia->motivotraslado->code == '13')
                                    {{ $guia->client->document }},
                                    {{ $guia->client->name }}
                                @endif
                            </span>
                        </h1>
                    @endif

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        LUGAR DE EMISIÓN :
                        <span class="font-medium inline-block text-colorsubtitleform">
                            {{ $guia->ubigeoorigen->region }},
                            {{ $guia->ubigeoorigen->provincia }},
                            {{ $guia->ubigeoorigen->distrito }},
                            {{ $guia->ubigeoorigen->ubigeo_inei }}
                            @if ($guia->motivotraslado->code == '04')
                                (ANEXO : {{ $guia->anexoorigen }})
                            @endif
                        </span>
                    </h1>

                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        LUGAR DE DESTINO :
                        <span class="font-medium inline-block text-colorsubtitleform">
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

                <div class="w-full flex gap-2 items-start justify-end mt-2">
                    <x-link-button href="{{ route('admin.facturacion.guias.print', $guia) }}" target="_blank">
                        IMPRIMIR A4</x-link-button>

                    @can('admin.facturacion.guias.sunat')
                        @if ($guia->seriecomprobante->typecomprobante->isSunat())
                            @if (!$guia->isSendSunat())
                                <x-button wire:click="enviarsunat({{ $guia->id }})" wire:loading.attr="disabled"
                                    class="inline-block">
                                    ENVIAR SUNAT</x-button>
                            @endif
                        @endif
                    @endcan

                    {{-- @can('admin.facturacion.sunat')
                        @if ($guia->seriecomprobante->typecomprobante->isSunat())
                            <x-button>ENVIAR SUNAT</x-button>
                        @endif
                    @endcan --}}
                </div>
            </x-form-card>

            @if ($guia->guiable)
                <x-form-card titulo="COMPROBANTE RELACIONADO">
                    <div class="w-full flex flex-col gap-2">
                        <p class="text-colorlabel text-2xl font-semibold">
                            {{ $guia->guiable->seriecompleta }}
                            <small
                                class="text-sm">{{ $guia->guiable->seriecomprobante->typecomprobante->descripcion }}</small>
                        </p>

                        <h1 class="text-colorlabel text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            CLIENTE :
                            <span class="font-medium inline-block">
                                {{ $guia->guiable->client->document }},
                                {{ $guia->guiable->client->name }}</span>
                        </h1>

                        <div class="flex items-center justify-start gap-1">
                            <a href="{{ route('admin.facturacion.print.a4', $guia->guiable) }}" target="_blank"
                                class="p-1.5 bg-red-800 text-white block rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 block scale-110 " xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path
                                        d="M7 18V15.5M7 15.5V14C7 13.5286 7 13.2929 7.15377 13.1464C7.30754 13 7.55503 13 8.05 13H8.75C9.47487 13 10.0625 13.5596 10.0625 14.25C10.0625 14.9404 9.47487 15.5 8.75 15.5H7ZM21 13H19.6875C18.8625 13 18.4501 13 18.1938 13.2441C17.9375 13.4882 17.9375 13.881 17.9375 14.6667V15.5M17.9375 18V15.5M17.9375 15.5H20.125M15.75 15.5C15.75 16.8807 14.5747 18 13.125 18C12.7979 18 12.6343 18 12.5125 17.933C12.2208 17.7726 12.25 17.448 12.25 17.1667V13.8333C12.25 13.552 12.2208 13.2274 12.5125 13.067C12.6343 13 12.7979 13 13.125 13C14.5747 13 15.75 14.1193 15.75 15.5Z" />
                                    <path
                                        d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10" />
                                    <path
                                        d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2" />
                                </svg>
                            </a>

                            <x-button-print href="{{ route('admin.facturacion.print.ticket', $guia->guiable) }}"
                                target="_blank" />
                        </div>
                    </div>
                </x-form-card>
            @endif

            <x-form-card titulo="DESTINATARIO">
                <div class="w-full">
                    <h1 class="text-colorlabel text-xs font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                        </svg>
                        DESTINATARIO :
                        <span class="font-medium inline-block text-colorsubtitleform">
                            {{ $guia->documentdestinatario }},
                            {{ $guia->namedestinatario }}</span>
                    </h1>
                </div>
            </x-form-card>

            @if ($guia->modalidadtransporte->code == '01' && $guia->indicadorvehiculosml == '0')
                <x-form-card titulo="TRANSPORTISTA">
                    <div class="w-full bg-body p-3 rounded-md">
                        <h1 class="text-colorlabel text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            TRANSPORTISTA :
                            <span class="font-medium inline-block text-colorsubtitleform">
                                {{ $guia->ructransport }},
                                {{ $guia->nametransport }}</span>
                        </h1>
                    </div>
                </x-form-card>
            @endif

            @if ($guia->motivotraslado->code == '02' || $guia->motivotraslado->code == '07' || $guia->motivotraslado->code == '13')
                <x-form-card titulo="PROVEEDOR" class="animate__animated animate__fadeInDown">
                    <div class="w-full bg-body p-3 rounded-md">
                        <h1 class="text-colorlabel text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            PROVEEDOR :
                            <span class="font-medium inline-block text-colorsubtitleform">
                                {{ $guia->rucproveedor }},
                                {{ $guia->nameproveedor }}</span>
                        </h1>
                    </div>
                </x-form-card>
            @endif
        </div>

        @if ($guia->modalidadtransporte->code == '02' && $guia->indicadorvehiculosml == '0')
            <x-form-card titulo="CONDUCTORES VEHÍCULO">
                <div class="w-full relative rounded flex flex-wrap lg:flex-nowrap gap-3">
                    @if ($guia->codesunat != '0')
                        <div class="w-full lg:w-96 relative" x-data="{ loading: false }">
                            <form wire:submit.prevent="savedriver" class="w-full flex flex-col gap-2">
                                <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-1">
                                    <div class="w-full">
                                        <x-label value="Documento :" />
                                        <div class="w-full inline-flex gap-1">
                                            <x-input class="block w-full flex-1 input-number-none"
                                                wire:model.defer="documentdriver"
                                                wire:keydown.enter.prevent="searchclient"
                                                onkeypress="return validarNumero(event, 11)" />
                                            <x-button-add class="px-2" wire:click="searchclient"
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
                    <div class="w-full flex-1 relative rounded">
                        @if (count($guia->transportdrivers))
                            <x-table class="w-full">
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
                                                        onclick="confirmDeletedriver({{ $item }})"
                                                        wire:loading.attr="disabled" />
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </x-slot>
                            </x-table>
                        @endif
                    </div>
                </div>
            </x-form-card>

            <x-form-card titulo="VEHÍCULOS TRANSPORTE">
                <div class="w-full relative rounded flex flex-wrap md:flex-nowrap gap-3">
                    @if ($guia->codesunat != '0')
                        <div class="w-full md:w-96 relative" x-data="{ loading: false }">
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
                        </div>
                    @endif
                    <div class="w-full flex-1 relative rounded">
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
                                                <x-button-delete onclick="confirmDeletevehiculo({{ $item }})"
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
                @if (count($guia->tvitems) > 0)
                    <div
                        class="w-full grid grid-cols-[repeat(auto-fill,minmax(160px,1fr))] xs:grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1">
                        @foreach ($guia->tvitems as $item)
                            @php
                                $class = '';
                                if ($item->trashed()) {
                                    $class = 'bg-opacity-20 opacity-80 !shadow-red-400 bg-red-500 !cursor-default';
                                }
                                if ($item->cantidad == 0) {
                                    $class = 'bg-opacity-20 bg-neutral-400 !cursor-default';
                                }
                            @endphp

                            <x-card-producto :name="$item->producto->name" :image="!empty($item->producto->imagen)
                                ? pathURLProductImage($item->producto->imagen->url)
                                : null" :almacen="$item->producto->marca->name" :class="$class">

                                <h1 class="text-xl !leading-none font-semibold mt-1 text-center text-colorlabel">
                                    {{ decimalOrInteger($item->cantidad) }}
                                    <small class="text-[10px] font-medium">
                                        {{ $item->producto->unit->name }}
                                        <small class="text-colorerror">
                                            /
                                            @if ($item->isNoAlterStock())
                                                NO ALTERA STOCK
                                            @elseif ($item->isReservedStock())
                                                STOCK RESERVADO
                                            @elseif ($item->isIncrementStock())
                                                INCREMENTA STOCK
                                            @elseif($item->isDiscountStock())
                                                DISMINUYE STOCK
                                            @endif
                                        </small>
                                    </small>
                                </h1>

                                @if (count($item->kardexes) > 0  && count($item->itemseries) == 0)
                                    <div
                                        class="w-full grid {{ count($item->kardexes) > 1 ? 'grid-cols-2' : 'grid-cols-1' }} gap-1 mt-2 divide-x divide-borderminicard">
                                        @foreach ($item->kardexes as $kardex)
                                            <div class="w-full p-1.5 flex flex-col items-center justify-start">
                                                <h1 class="text-colorsubtitleform text-sm font-semibold !leading-none">
                                                    {{ decimalOrInteger($kardex->cantidad) }}
                                                    <small class="text-[10px] font-normal">
                                                        {{ $item->producto->unit->name }}</small>
                                                </h1>

                                                <h1 class="text-colortitleform text-[10px] font-semibold">
                                                    {{ $kardex->almacen->name }}</h1>

                                                @if (!$item->producto->isRequiredserie())
                                                    <x-button-delete wire:click="deletekardex({{ $item->id }},{{ $kardex->id }})"
                                                        wire:loading.attr="disabled" class="inline-flex" />
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="w-full flex flex-col gap-1 justify-center items-center mt-1"
                                    x-data="{ showForm: '{{ count($item->itemseries) == 1 }}' }">
                                    @if (count($item->itemseries) > 0)
                                        @if (count($item->itemseries) > 1)
                                            <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                                <span x-text="showForm ? 'OCULTAR SERIES' : 'MOSTRAR SERIES'"></span>
                                            </x-button>
                                        @endif

                                        <div class="w-full" x-show="showForm" x-transition>
                                            <x-table class="w-full block">
                                                <x-slot name="body">
                                                    @foreach ($item->itemseries as $itemserie)
                                                        <tr>
                                                            <td class="p-1 align-middle font-medium">
                                                                {{ $itemserie->serie->serie }}
                                                                [{{ $itemserie->serie->almacen->name }}]
                                                            </td>
                                                            <td class="align-middle text-center" width="40px">
                                                                <x-button-delete
                                                                    wire:click="deleteitemserie({{ $itemserie->id }})"
                                                                    wire:loading.attr="disabled" />
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </x-slot>
                                            </x-table>
                                        </div>
                                    @endif
                                </div>


                                @if (!$item->trashed())
                                    @if ($item->cantidad > 0)
                                        @if ($item->isReservedStock())
                                            <x-button class="block w-full mt-1"
                                                onclick="confirmarSalida({{ $item->id }})"
                                                wire:loading.attr="disabled">CONFIRMAR SALIDA</x-button>
                                            <x-button class="block w-full mt-1"
                                                onclick="confirmarDevolucion({{ $item->id }})"
                                                wire:loading.attr="disabled">REPONER ALMACÉN</x-button>
                                        @endif

                                        @if ($item->isIncrementStock())
                                        @endif
                                    @endif
                                @endif

                                <x-slot name="footer">
                                    @if ($item->trashed())
                                        <x-span-text text="ELIMINADO" class="leading-3 !tracking-normal"
                                            type="red" />
                                    @else
                                        @if (!$guia->isSendSunat())
                                            <x-button-delete onclick="confirmDeleteitem({{ $item->id }})"
                                                wire:loading.attr="disabled" />
                                        @endif
                                    @endif
                                </x-slot>
                            </x-card-producto>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-form-card>
    </div>

    <script>
        function confirmarSalida(tvitem_id) {
            swal.fire({
                title: 'Confirmar salida del producto seleccionado ?',
                text: "Se actualizará el stock del producto con la cantidad reservada en el item.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.confirmarsalida(tvitem_id);
                }
            })
        }

        function confirmarDevolucion(tvitem_id) {
            swal.fire({
                title: 'Confirmar devolución del producto seleccionado ?',
                text: "Se actualizará el stock del producto con la cantidad reservada en el item.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.confirmardevolucion(tvitem_id);
                }
            })
        }

        function confirmDeleteitem(tvitem_id) {
            swal.fire({
                title: 'Desea eliminar el producto seleccionado ?',
                text: "Se actualizará el stock del producto con la cantidad registrada en el item.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteitem(tvitem_id);
                }
            })
        }

        function confirmDeletevehiculo(vehiculo) {
            swal.fire({
                title: 'Eliminar vehículo de placa ' + vehiculo.placa,
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletevehiculo(vehiculo.id);
                }
            })
        }

        function confirmDeletedriver(driver) {
            swal.fire({
                title: 'Eliminar conductor con nombres ' + driver.name + ' ' + driver.lastname,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletedriver(driver.id);
                }
            })
        }
    </script>
</div>

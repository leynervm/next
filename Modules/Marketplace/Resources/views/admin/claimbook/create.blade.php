<x-app-layout>
    <x-slot name="breadcrumb">
        {{-- <x-link-breadcrumb text="LIBRO DE RECLAMACIONES" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M16.6127 16.0846C13.9796 17.5677 12.4773 20.6409 12 21.5V8C12.4145 7.25396 13.602 5.11646 15.6317 3.66368C16.4868 3.05167 16.9143 2.74566 17.4572 3.02468C18 3.30371 18 3.91963 18 5.15146V13.9914C18 14.6568 18 14.9895 17.8634 15.2233C17.7267 15.4571 17.3554 15.6663 16.6127 16.0846L16.6127 16.0846Z" />
                    <path
                        d="M12 7.80556C11.3131 7.08403 9.32175 5.3704 5.98056 4.76958C4.2879 4.4652 3.44157 4.31301 2.72078 4.89633C2 5.47965 2 6.42688 2 8.32133V15.1297C2 16.8619 2 17.728 2.4626 18.2687C2.9252 18.8095 3.94365 18.9926 5.98056 19.3589C7.79633 19.6854 9.21344 20.2057 10.2392 20.7285C11.2484 21.2428 11.753 21.5 12 21.5C12.247 21.5 12.7516 21.2428 13.7608 20.7285C14.7866 20.2057 16.2037 19.6854 18.0194 19.3589C20.0564 18.9926 21.0748 18.8095 21.5374 18.2687C22 17.728 22 16.8619 22 15.1297V8.32133C22 6.42688 22 5.47965 21.2792 4.89633C20.5584 4.31301 19 4.76958 18 5.5" />
                </svg>
            </x-slot>
        </x-link-breadcrumb> --}}
        <x-link-breadcrumb text="REGISTRAR SOLICITUD DE RECLAMO" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11.0215 6.78662V19.7866" />
                    <path
                        d="M11 19.5C10.7777 19.5 10.3235 19.2579 9.41526 18.7738C8.4921 18.2818 7.2167 17.7922 5.5825 17.4849C3.74929 17.1401 2.83268 16.9678 2.41634 16.4588C2 15.9499 2 15.1347 2 13.5044V7.09655C2 5.31353 2 4.42202 2.6487 3.87302C3.29741 3.32401 4.05911 3.46725 5.5825 3.75372C8.58958 4.3192 10.3818 5.50205 11 6.18114C11.6182 5.50205 13.4104 4.3192 16.4175 3.75372C17.9409 3.46725 18.7026 3.32401 19.3513 3.87302C20 4.42202 20 5.31353 20 7.09655V10" />
                    <path
                        d="M20.8638 12.9393L21.5589 13.6317C22.147 14.2174 22.147 15.1672 21.5589 15.7529L17.9171 19.4485C17.6306 19.7338 17.2642 19.9262 16.8659 20.0003L14.6088 20.4883C14.2524 20.5653 13.9351 20.2502 14.0114 19.895L14.4919 17.6598C14.5663 17.2631 14.7594 16.8981 15.0459 16.6128L18.734 12.9393C19.3222 12.3536 20.2757 12.3536 20.8638 12.9393Z" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <form class="contenedor w-full flex flex-col gap-5" method="POST" action="{{ route('claimbook.store') }}"
        x-data="shipment" id="form_claimbook">
        @csrf
        <h1 class="font-semibold text-3xl text-colortitleform py-3 text-center">
            Libro de Reclamaciones</h1>

        <x-simple-card class="p-1 md:p-3">
            <h1 class="font-semibold text-lg text-colortitleform">
                Datos de la persona que presenta el reclamo</h1>

            <div class="w-full grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2 pt-3">
                <div class="">
                    <x-label value="DNI / RUC :" />
                    <x-input class="block w-full prevent" name="document" onkeypress="return validarNumero(event, 11)"
                        value="{{ old('document') }}" required />
                    <x-jet-input-error for="document" />
                </div>

                <div class="lg:col-span-2">
                    <x-label value="NOMBRES Y APELLIDOS :" />
                    <x-input class="block w-full" name="name" value="{{ old('name') }}" required />
                    <x-jet-input-error for="name" />
                </div>

                <div class="lg:col-span-2">
                    <x-label value="DIRECCIÓN :" />
                    <x-input class="block w-full" name="direccion" value="{{ old('direccion') }}" required />
                    <x-jet-input-error for="direccion" />
                </div>

                <div class="">
                    <x-label value="TELÉFONO :" />
                    <x-input class="block w-full" name="telefono" onkeypress="return validarNumero(event, 9)"
                        value="{{ old('telefono') }}" required />
                    <x-jet-input-error for="telefono" />
                </div>

                <div class="">
                    <x-label value="CORREO ELECTRÓNICO :" />
                    <x-input class="block w-full" type="email" name="email" value="{{ old('email') }}" required />
                    <x-jet-input-error for="email" />
                </div>

                <div class="">
                    <x-label value="FECHA :" />
                    <x-input class="block w-full" type="date" name="date"
                        value="{{ old('date') ?? now('America/Lima')->format('Y-m-d') }}" required />
                    <x-jet-input-error for="date" />
                </div>
            </div>

            <div class="w-full mt-5">
                <label for="menoredad"
                    class="text-colortitleform inline-flex items-center gap-2 text-lg font-semibold leading-3 cursor-pointer">
                    <input x-model="open" type="checkbox"
                        class="cursor-pointer !ring-next-500 focus:!ring-next-500 !rounded-none" type="checkbox"
                        id="menoredad" name="menor_edad" @if (old('menor_edad')) checked @endif />
                    Soy menor de edad
                </label>
                <x-jet-input-error for="menor_edad" />
            </div>
        </x-simple-card>

        <x-simple-card class="p-1 md:p-3" style="display: none;" x-cloak x-show="open">
            <h1 class="font-semibold text-lg text-colortitleform">
                Datos del apoderado</h1>

            <div class="w-full grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2 pt-3">
                <div class="">
                    <x-label value="N° DNI :" />
                    <x-input class="block w-full flex-1 prevent" name="document_apoderado" type="number"
                        onkeypress="return validarNumero(event, 8)" value="{{ old('document_apoderado') }}" />
                    <x-jet-input-error for="document_apoderado" />
                </div>

                <div class="lg:col-span-2">
                    <x-label value="NOMBRES Y APELLIDOS :" />
                    <x-input class="block w-full" name="name_apoderado" value="{{ old('name_apoderado') }}" />
                    <x-jet-input-error for="name_apoderado" />
                </div>

                <div class="lg:col-span-2">
                    <x-label value="DIRECCIÓN :" />
                    <x-input class="block w-full" name="direccion_apoderado" value="{{ old('direccion_apoderado') }}" />
                    <x-jet-input-error for="direccion_apoderado" />
                </div>

                <div class="">
                    <x-label value="TELÉFONO :" />
                    <x-input class="block w-full" name="telefono_apoderado" onkeypress="return validarNumero(event, 9)"
                        value="{{ old('telefono_apoderado') }}" />
                    <x-jet-input-error for="telefono_apoderado" />
                </div>

                {{-- <div class="col-span-3 lg:col-span-1">
                    <x-label value="CORREO ELECTRÓNICO :" />
                    <x-input class="block w-full" type="email" name="email_apoderado"
                        value="{{ old('email_apoderado') }}" />
                    <x-jet-input-error for="email_apoderado" />
                </div> --}}
            </div>
        </x-simple-card>

        <x-simple-card class="p-1 md:p-3" {{-- style="display: none;" x-cloak x-show="open" --}}>
            <h1 class="font-semibold text-lg text-colortitleform">
                Canal de venta</h1>

            <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 pt-3">
                <x-input-radio class="py-2" for="channel_web" text="TIENDA WEB">
                    <input x-model="channelsale" class="sr-only peer peer-disabled:opacity-25" type="radio"
                        id="channel_web" name="channelsale" value="{{ \App\Models\Claimbook::TIENDA_WEB }}" />
                </x-input-radio>
                <x-input-radio class="py-2" for="channel_fisico" text="TIENDA FISICA">
                    <input x-model="channelsale" class="sr-only peer peer-disabled:opacity-25" type="radio"
                        id="channel_fisico" name="channelsale" value="{{ \App\Models\Claimbook::TIENDA_FISICA }}" />
                </x-input-radio>
            </div>
            <x-jet-input-error for="channelsale" />
        </x-simple-card>

        <x-simple-card class="p-1 md:p-3">
            <h1 class="font-semibold text-lg text-colortitleform">
                Detalle del reclamo</h1>

            <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2 pt-3">
                <div class="">
                    <x-label value="N° ORDER / PEDIDO :" />
                    <x-input class="block w-full" name="pedido" value="{{ old('pedido') }}" />
                    <x-jet-input-error for="pedido" />
                </div>

                <div class="">
                    <x-label value="BIEN CONTRATADO :" />
                    <div class="relative" id="parentbiencontratado">
                        <x-select class="block w-full" name="biencontratado" id="biencontratado"
                            data-dropdown-parent="null" required>
                            <x-slot name="options">
                                <option value="PRODUCTO">PRODUCTO</option>
                                <option value="SERVICIO">SERVICIO</option>
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="biencontratado" />
                </div>

                <div class="sm:col-span-2" style="display: none;" x-cloak x-show="channelsale=='TIENDA FISICA'">
                    <x-label value="TIENDA DE COMPRA :" />
                    <div class="relative" id="parenttiendacompra">
                        <x-select class="block w-full" name="tienda_compra" id="tiendacompra"
                            data-dropdown-parent="null">
                            @if (count($sucursals) > 0)
                                <x-slot name="options">
                                    @foreach ($sucursals as $item)
                                        <option value="{{ $item->id }}"
                                            title="{{ $item->direccion }} - {{ $item->ubigeo->distrito }}, {{ $item->ubigeo->provincia }}, {{ $item->ubigeo->region }} ">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            @endif
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="tienda_compra" />
                </div>
            </div>

            <div class="mt-2">
                <x-label value="DESCRIPCIÓN DEL PRODUCTO / SERVICIO :" />
                <x-text-area class="w-full" rows="6" name="descripcion_producto_servicio" required>
                    {{ old('descripcion_producto_servicio') }}
                </x-text-area>
                <x-jet-input-error for="descripcion_producto_servicio" />
            </div>

            <div class="w-full grid grid-cols-1 xs:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2 pt-3">
                <div class="">
                    <x-label value="TIPO DE RECLAMACIÓN :" />
                    <div class="relative" id="parenttiporeclamo">
                        <x-select class="select2 block w-full" name="tipo_reclamo" id="tiporeclamo"
                            data-dropdown-parent="null" required>
                            <x-slot name="options">
                                <option value="QUEJA">QUEJA</option>
                                <option value="RECLAMO">RECLAMO</option>
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="tipo_reclamo" />
                </div>

                <div class="xs:col-span-2 md:col-span-3 lg:col-span-5">
                    <x-label value="DETALLE DEL RECLAMO :" />
                    <x-text-area class="w-full" rows="6" name="detalle_reclamo" required>
                        {{ old('detalle_reclamo') }}
                    </x-text-area>
                    <x-jet-input-error for="detalle_reclamo" />
                </div>
            </div>
        </x-simple-card>

        <x-jet-input-error for="g_recaptcha_response" />

        <div class="w-full p-5 flex items-center justify-center">
            <button class="btn-next w-full max-w-xs" type="submit">
                <span class="btn-effect"><span>ENVIAR</span></span>
            </button>
        </div>
    </form>


    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     console.log("{{ old('menor_edad') }}");
        // })

        document.addEventListener('alpine:init', () => {
            Alpine.data('shipment', () => ({
                open: false,
                channelsale: 'TIENDA WEB',
                init() {
                    let state = "{{ old('menor_edad') }}";
                    let tienda_compra = "{{ old('tienda_compra') }}";
                    let biencontratado = "{{ old('biencontratado') }}";
                    let tipo_reclamo = "{{ old('tipo_reclamo') }}";
                    let channelsale = "{{ old('channelsale') }}";

                    if (state) {
                        this.open = true;
                    }

                    this.$watch("open", (value) => {
                        if (tienda_compra) {
                            $('#tiendacompra').val(tienda_compra).select2().change();
                        }
                    });

                    if (tienda_compra) {
                        $('#tiendacompra').val(tienda_compra).select2().change();
                    }
                    if (biencontratado) {
                        $('#biencontratado').val(biencontratado).select2().change();
                    }
                    if (tipo_reclamo) {
                        $('#tiporeclamo').val(tipo_reclamo).select2().change();
                    }
                    if (channelsale) {
                        this.channelsale = channelsale;
                    }
                }
            }))
        })

        document.addEventListener('DOMContentLoaded', function() {
            $('#tiporeclamo, #biencontratado, #tiendacompra').select2();
            $('#tiendacompra').select2({
                templateResult: formatOption
            });
        })

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text + '</strong><p class="select2-subtitle-option text-[10px]">' + option.title +
                '</p>'
            );
            return $option;
        };
    </script>
</x-app-layout>

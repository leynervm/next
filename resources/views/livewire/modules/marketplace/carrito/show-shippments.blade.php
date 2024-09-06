<div x-data="shipment">
    <form @submit.prevent="save" class="w-full" id="register_order">
        <x-simple-card class="w-full flex flex-col rounded-xl shadow">
            <h1 class="text-[10px] font-semibold rounded-t-xl p-2 bg-fondospancardproduct text-colorlabel">
                TIPO DE ENTREGA</h1>
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 gap-2 xs:gap-5 p-1 xs:p-3">
                @if (count($shipmenttypes) > 0)
                    @foreach ($shipmenttypes as $item)
                        <div class="relative w-full">
                            <input x-model="shipmenttype_id" class="sr-only peer peer-disabled:opacity-25" type="radio"
                                id="shipmenttpe_{{ $item->id }}" name="shipmenttypes" value="{{ $item->id }}"
                                @change="seleccionarenvio({{ $item }})" />
                            <label for="shipmenttpe_{{ $item->id }}"
                                class ="text-xs relative flex justify-center items-center border border-ringbutton gap-1 text-center font-medium ring-ringbutton text-colorlabel p-2.5 px-3 rounded-lg cursor-pointer hover:bg-fondohoverbutton hover:ring-fondohoverbutton hover:border-fondobutton hover:text-colorhoverbutton peer-checked:bg-fondohoverbutton peer-checked:ring-2 peer-checked:ring-ringbutton peer-checked:text-colorhoverbutton peer-focus:text-colorhoverbutton checked:bg-fondohoverbutton peer-disabled:opacity-25 transition ease-in-out duration-150">
                                <span class="block w-6 sm:w-10 h-6 sm:h-10">
                                    @if ($item->isEnviodomicilio())
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33" fill-rule="evenodd"
                                            clip-rule="evenodd" fill="currentColor" class="w-full h-full">
                                            <path
                                                d="M3 7.5C3 6.39543 3.89543 5.5 5 5.5H17C18.1046 5.5 19 6.39543 19 7.5V10.5H24.4338C25.1363 10.5 25.7873 10.8686 26.1488 11.471L28.715 15.748C28.9015 16.0588 29 16.4145 29 16.777V22.5C29 23.6046 28.1046 24.5 27 24.5H25.874C25.4299 26.2252 23.8638 27.5 22 27.5C20.0283 27.5 18.3898 26.0734 18.0604 24.1961C17.753 24.3887 17.3895 24.5 17 24.5H12.874C12.4299 26.2252 10.8638 27.5 9 27.5C7.12577 27.5 5.55261 26.211 5.1187 24.4711C3.91896 24.2875 3 23.2511 3 22V21.5C3 20.9477 3.44772 20.5 4 20.5C4.55228 20.5 5 20.9477 5 21.5V22C5 22.1459 5.06252 22.2773 5.16224 22.3687C5.65028 20.7105 7.18378 19.5 9 19.5C10.8638 19.5 12.4299 20.7748 12.874 22.5H17V16.5V7.5H5V8.5C5 9.05228 4.55228 9.5 4 9.5C3.44772 9.5 3 9.05228 3 8.5V7.5ZM19 15.5V12.5H24.4338L26.2338 15.5H19ZM19 17.5H27V22.5H25.874C25.4299 20.7748 23.8638 19.5 22 19.5C20.8053 19.5 19.7329 20.0238 19 20.8542V17.5ZM22 21.5C23.1046 21.5 24 22.3954 24 23.5C24 24.6046 23.1046 25.5 22 25.5C20.8954 25.5 20 24.6046 20 23.5C20 22.3954 20.8954 21.5 22 21.5ZM7 23.5C7 24.6046 7.89543 25.5 9 25.5C10.1046 25.5 11 24.6046 11 23.5C11 22.3954 10.1046 21.5 9 21.5C7.89543 21.5 7 22.3954 7 23.5ZM2 10.5C1.44772 10.5 1 10.9477 1 11.5C1 12.0523 1.44772 12.5 2 12.5H7C7.55228 12.5 8 12.0523 8 11.5C8 10.9477 7.55228 10.5 7 10.5H2ZM3 13.5C2.44772 13.5 2 13.9477 2 14.5C2 15.0523 2.44772 15.5 3 15.5H7C7.55228 15.5 8 15.0523 8 14.5C8 13.9477 7.55228 13.5 7 13.5H3ZM3 17.5C3 16.9477 3.44772 16.5 4 16.5H7C7.55229 16.5 8 16.9477 8 17.5C8 18.0523 7.55229 18.5 7 18.5H4C3.44772 18.5 3 18.0523 3 17.5Z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33" fill-rule="evenodd"
                                            clip-rule="evenodd" fill="currentColor" class="w-full h-full">
                                            <path
                                                d="M18.4449 14.2024C19.4296 12.8623 20 11.5761 20 10.5C20 8.29086 18.2091 6.5 16 6.5C13.7909 6.5 12 8.29086 12 10.5C12 11.5761 12.5704 12.8623 13.5551 14.2024C14.3393 15.2698 15.2651 16.2081 16 16.8815C16.7349 16.2081 17.6607 15.2698 18.4449 14.2024ZM16.8669 18.7881C18.5289 17.3455 22 13.9227 22 10.5C22 7.18629 19.3137 4.5 16 4.5C12.6863 4.5 10 7.18629 10 10.5C10 13.9227 13.4712 17.3455 15.1331 18.7881C15.6365 19.2251 16.3635 19.2251 16.8669 18.7881ZM5 11.5H8.27078C8.45724 12.202 8.72804 12.8724 9.04509 13.5H5V26.5H10.5V22C10.5 21.4477 10.9477 21 11.5 21H20.5C21.0523 21 21.5 21.4477 21.5 22V26.5H27V13.5H22.9549C23.272 12.8724 23.5428 12.202 23.7292 11.5H27C28.1046 11.5 29 12.3954 29 13.5V26.5C29.5523 26.5 30 26.9477 30 27.5C30 28.0523 29.5523 28.5 29 28.5H3C2.44772 28.5 2 28.0523 2 27.5C2 26.9477 2.44772 26.5 3 26.5V13.5C3 12.3954 3.89543 11.5 5 11.5ZM19.5 23V26.5H12.5V23H19.5ZM17 10.5C17 11.0523 16.5523 11.5 16 11.5C15.4477 11.5 15 11.0523 15 10.5C15 9.94772 15.4477 9.5 16 9.5C16.5523 9.5 17 9.94772 17 10.5ZM19 10.5C19 12.1569 17.6569 13.5 16 13.5C14.3431 13.5 13 12.1569 13 10.5C13 8.84315 14.3431 7.5 16 7.5C17.6569 7.5 19 8.84315 19 10.5Z" />
                                        </svg>
                                    @endif
                                </span>
                                {{ $item->name }}
                            </label>
                            @if ($item->descripcion)
                                <p
                                    class="text-[10px] mt-1 px-3 leading-3 text-justify [text-align-last:center] text-colorsubtitleform">
                                    {{ $item->descripcion }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <h1 class="text-[10px] p-3 text-red-600">NO EXISTES TIPOS DE ENVÍO</h1>
                @endif
            </div>
            <x-jet-input-error class="px-3 pb-1" for="shipmenttype_id" />
        </x-simple-card>

        <x-simple-card class="w-full rounded-xl mt-5 shadow" style="display:none;" x-show="showadress">
            <h1 class="text-[10px] font-semibold rounded-t-xl p-2 bg-fondospancardproduct text-colorlabel">
                DIRECCIONES DE ENVÍO REGISTRADOS</h1>

            @if (count($direccions) > 0)
                <div class="w-full flex flex-col gap-1 mb-3 p-1 xs:p-3">
                    @foreach ($direccions as $item)
                        <x-simple-card
                            class="w-full flex gap-3 justify-between shadow-md rounded-md p-3 border {{ $item->isDefault() ? 'border-next-500 shadow-next-300' : 'border-borderminicard' }} ">
                            <div class="w-full flex-1 flex gap-1 items-start">
                                @if ($item->isDefault())
                                    <x-icon-default class="!inline-block" />
                                @endif
                                <div class="w-full text-colorlabel">
                                    <p class="text-[10px]">
                                        {{ $item->ubigeo->region }}, {{ $item->ubigeo->provincia }},
                                        {{ $item->ubigeo->distrito }}
                                    </p>
                                    <p class="text-[10px]">{{ $item->name }}</p>
                                    @if ($item->referencia)
                                        <p class="text-[10px]">REFERENCIA : {{ $item->referencia }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex gap-1 items-end justify-end">
                                @if (!$item->isDefault())
                                    <button type="button" wire:click="savedefaultdireccion({{ $item->id }})"
                                        wire:loading.attr="disabled"
                                        class="inline-block group relative font-semibold text-sm bg-transparent text-yellow-500 p-1 rounded-md hover:bg-yellow-500 focus:bg-yellow-500 hover:ring-2 hover:ring-yellow-300 focus:ring-2 focus:ring-yellow-300 hover:text-white focus:text-white disabled:opacity-25 transition ease-in duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-auto"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M13.7276 3.44418L15.4874 6.99288C15.7274 7.48687 16.3673 7.9607 16.9073 8.05143L20.0969 8.58575C22.1367 8.92853 22.6167 10.4206 21.1468 11.8925L18.6671 14.3927C18.2471 14.8161 18.0172 15.6327 18.1471 16.2175L18.8571 19.3125C19.417 21.7623 18.1271 22.71 15.9774 21.4296L12.9877 19.6452C12.4478 19.3226 11.5579 19.3226 11.0079 19.6452L8.01827 21.4296C5.8785 22.71 4.57865 21.7522 5.13859 19.3125L5.84851 16.2175C5.97849 15.6327 5.74852 14.8161 5.32856 14.3927L2.84884 11.8925C1.389 10.4206 1.85895 8.92853 3.89872 8.58575L7.08837 8.05143C7.61831 7.9607 8.25824 7.48687 8.49821 6.99288L10.258 3.44418C11.2179 1.51861 12.7777 1.51861 13.7276 3.44418Z" />
                                        </svg>
                                    </button>
                                @endif
                                {{-- <x-button-edit wire:loading.attr="disabled" wire:click="" /> --}}
                                <x-button-delete wire:loading.attr="disabled"
                                    wire:click="deletedireccion({{ $item->id }})" />
                            </div>
                        </x-simple-card>
                    @endforeach
                </div>
            @else
                <h1 class="text-[10px] p-3 py-2 text-red-600">
                    NO EXISTEN DIRECCIONES REGISTRADAS</h1>
            @endif

            <template x-if="showaddadress == false">
                <div class="w-full p-3 pt-0 justify-end">
                    <x-button @click="showaddadress=!showaddadress" class="!rounded-lg"
                        wire:loading.attr="disabled">AGREGAR</x-button>
                </div>
            </template>

            <x-jet-input-error class="px-3 pb-1" for="direccionenvio_id" />


            <div class="flex flex-col gap-2 p-1 xs:p-3" style="display: none;" x-show="showaddadress">
                <div class="w-full">
                    {{-- <x-label value="Ubigeo :" /> --}}
                    <div class="relative" x-data="{ lugar_id: @entangle('lugar_id').defer }" x-init="select2Ubigeo" id="parentlugar_id">
                        <x-select class="block w-full" x-ref="select" id="lugar_id" data-minimum-results-for-search="3"
                            data-placeholder="SELECCIONAR LUGAR ENVÍO...">
                            <x-slot name="options">
                                @if (count($ubigeos))
                                    @foreach ($ubigeos as $item)
                                        <option value="{{ $item->id }}">{{ $item->region }} /
                                            {{ $item->provincia }}
                                            / {{ $item->distrito }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="lugar_id" />
                </div>
                <div class="w-full">
                    {{-- <x-label value="Dirección, calle, avenida :" /> --}}
                    <x-input class="block w-full" wire:model.defer="direccion"
                        placeholder="DIRECCIÓN, OFICINA, TERMINAL DE ENVÍO..." />
                    <x-jet-input-error for="direccion" />
                </div>
                <div class="w-full">
                    {{-- <x-label value="Referencia :" /> --}}
                    <x-input class="block w-full" wire:model.defer="referencia" placeholder="REFERENCIA DE ENVÍO..." />
                    <x-jet-input-error for="referencia" />
                </div>
                <div class="w-full flex justify-end flex-wrap gap-2">
                    <x-button-secondary class="!rounded-lg" wire:loading.attr="disabled"
                        @click="showaddadress=false">CANCELAR</x-button-secondary>
                    <x-button class="!rounded-lg" wire:loading.attr="disabled"
                        wire:click="savedireccion">REGISTRAR</x-button>
                </div>
            </div>
        </x-simple-card>


        <x-simple-card class="w-full rounded-xl mt-5 shadow" style="display:none;" x-show="showlocales">
            <h1 class="text-[10px] font-semibold rounded-t-xl p-2 bg-fondospancardproduct text-colorlabel">
                NUESTRAS TIENDAS DISPONIBLES</h1>

            <div class="flex flex-col gap-2 p-1 xs:p-3">
                <div class="w-full">
                    {{-- <x-label value="Locales :" /> --}}
                    <div class="relative" x-model="local_id" x-init="select2Local" id="parentlocal_id">
                        <x-select class="block w-full" x-ref="selectlocal" id="local_id"
                            data-minimum-results-for-search="3" data-placeholder="SELECCIONAR LOCAL DE ENTREGA...">
                            <x-slot name="options">
                                @if (count($locals) > 0)
                                    @foreach ($locals as $item)
                                        <option value="{{ $item->id }}"
                                            title="{{ $item->direccion }} - {{ $item->ubigeo->distrito }}, {{ $item->ubigeo->provincia }}, {{ $item->ubigeo->region }} ">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="local_id" />
                </div>
                <div class="w-full">
                    <x-label value="Fecha de recojo :" />
                    <x-input class="block w-full max-w-xs" x-model="daterecojo" type="date" />
                    <x-jet-input-error for="daterecojo" />
                </div>
            </div>
        </x-simple-card>

        <x-simple-card class="w-full rounded-xl mt-5 shadow">
            <h1 class="text-[10px] font-semibold rounded-t-xl p-2 bg-fondospancardproduct text-colorlabel">
                ¿QUIÉN RECIBE EL PEDIDO?</h1>
            <div class="flex flex-col gap-2 p-1 xs:p-3">
                <div class="w-full flex gap-2">
                    <x-input-radio class="py-2" for="{{ \Modules\Marketplace\Entities\Order::EQUAL_RECEIVER }}"
                        text="YO MISMO">
                        <input x-model="receiver" class="sr-only peer peer-disabled:opacity-25" type="radio"
                            id="{{ \Modules\Marketplace\Entities\Order::EQUAL_RECEIVER }}" name="receiver"
                            value="{{ \Modules\Marketplace\Entities\Order::EQUAL_RECEIVER }}" />
                    </x-input-radio>
                    <x-input-radio class="py-2" for="{{ \Modules\Marketplace\Entities\Order::OTHER_RECEIVER }}"
                        text="OTRA PERSONA">
                        <input x-model="receiver" class="sr-only peer peer-disabled:opacity-25" type="radio"
                            id="{{ \Modules\Marketplace\Entities\Order::OTHER_RECEIVER }}" name="receiver"
                            value="{{ \Modules\Marketplace\Entities\Order::OTHER_RECEIVER }}" />
                    </x-input-radio>
                </div>
                <x-jet-input-error for="receiver" />

                <div class="w-full flex flex-wrap gap-2">
                    <div class="w-full">
                        <x-label value="Documento :" />
                        <x-input class="block w-full" x-model="receiver_info.document" placeholder="DNI / RUC..."
                            onkeypress="return validarNumero(event, 11)" />
                        <x-jet-input-error for="receiver_info.document" />
                    </div>
                    <div class="w-full">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full" x-model="receiver_info.telefono"
                            placeholder="Télefono del receptor..." type="number"
                            onkeypress="return validarNumero(event, 9)" />
                        <x-jet-input-error for="receiver_info.telefono" />
                    </div>
                </div>
                <div class="w-full">
                    <x-label value="Nombre completo :" />
                    <x-input class="block w-full" x-model="receiver_info.name"
                        placeholder="Nombres del receptor..." />
                    <x-jet-input-error for="receiver_info.name" />
                </div>
            </div>
        </x-simple-card>

        @if (Cart::instance('shopping')->count() > 0)
            <div class="w-full flex flex-col gap-2 mt-2">
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="w-full mt-2">
                        <x-jet-label for="terms">
                            <div class="flex justify-start items-center">
                                <x-input x-model="terms" type="checkbox" name="terms" id="terms"
                                    class="!rounded-none" wire:model.defer="terms" />

                                <div class="flex-1 w-full ml-2 text-colorsubtitleform leading-3">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' =>
                                            '<a target="_blank" href="' .
                                            route('terms.show') .
                                            '" class="underline text-sm text-orange-600 hover:text-orange-900">' .
                                            __('Terms of Service') .
                                            '</a>',
                                        'privacy_policy' =>
                                            '<a target="_blank" href="' .
                                            route('policy.show') .
                                            '" class="underline text-sm text-orange-600 hover:text-orange-900">' .
                                            __('Privacy Policy') .
                                            '</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-jet-label>
                        <x-jet-input-error for="terms" />
                    </div>
                @endif
                <x-jet-input-error for="g_recaptcha_response" />


                <div class="w-full flex justify-center items-center px-3 text-center">
                    <button type="submit" :disabled="!terms" wire:loading.attr="disabled"
                        class="btn-next disabled:opacity-50">
                        <span class="btn-effect"><span>REGISTRAR PEDIDO</span></span>
                    </button>
                </div>
            </div>
        @endif
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('shipment', () => ({
                showadress: false,
                showlocales: false,
                terms: false,
                showaddadress: @entangle('showaddadress').defer,
                receiver: @entangle('receiver').defer,
                receiver_info: @entangle('receiver_info').defer,
                shipmenttype_id: @entangle('shipmenttype_id').defer,
                local_id: @entangle('local_id').defer,
                daterecojo: @entangle('daterecojo').defer,
                recaptcha: null,

                seleccionarenvio(shipmenttype) {
                    if (shipmenttype.isenvio == '1') {
                        this.showadress = true;
                        this.showlocales = false;
                    } else {
                        this.showlocales = true;
                        this.showadress = false;
                    }
                },
                init() {

                    this.$watch("receiver", (value) => {
                        if (value == '0') {
                            this.receiver_info.document = '{{ auth()->user()->document }}';
                            this.receiver_info.name = '{{ auth()->user()->name }}';
                            this.receiver_info.telefono = '{{ $phoneuser->phone ?? null }}';
                        } else {
                            this.receiver_info.document = '';
                            this.receiver_info.name = '';
                            this.receiver_info.telefono = '';
                        }
                    });

                    this.$watch("shipmenttype_id", (value) => {
                        // if (value) {
                        this.local_id = null;
                        this.daterecojo = null;
                        // }
                    });
                },
                save() {
                    grecaptcha.ready(() => {
                        grecaptcha.execute(
                            '{{ config('services.recaptcha_v3.key_web') }}', {
                                action: 'submit'
                            }).then(function(token) {
                            @this.save(token);
                        })
                    })
                }
            }))
        })

        function select2Ubigeo() {
            this.selectU = $(this.$refs.select).select2();
            this.selectU.val(this.lugar_id).trigger("change");
            this.selectU.on("select2:select", (event) => {
                this.lugar_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("lugar_id", (value) => {
                this.selectU.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectU.select2().val(this.lugar_id).trigger('change');
            });
        }

        function select2Local() {
            this.selectSuc = $(this.$refs.selectlocal).select2({
                templateResult: formatOption
            });
            this.selectSuc.val(this.local_id).trigger("change");
            this.selectSuc.on("select2:select", (event) => {
                this.local_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("local_id", (value) => {
                this.selectSuc.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectSuc.select2({
                    templateResult: formatOption
                }).val(this.local_id).trigger('change');
            });
        }

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text + '</strong><p class="select2-subtitle-option text-[10px]">' + option.title +
                '</p>'
            );
            return $option;
        };
    </script>
</div>

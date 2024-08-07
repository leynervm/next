<div x-data="shipment">
    <form @submit.prevent="save" class="w-full" id="register_order">
        <x-simple-card class="w-full flex flex-col rounded-xl shadow">
            <h1 class="text-[10px] font-semibold rounded-t-xl p-2 bg-fondospancardproduct text-colorlabel">
                TIPO DE ENTREGA</h1>
            <div class="w-full flex flex-wrap justify-around gap-2 p-1 xs:p-3">
                @if (count($shipmenttypes) > 0)
                    @foreach ($shipmenttypes as $item)
                        <div class="relative w-full xs:max-w-[240px]">
                            <input x-model="shipmenttype_id" class="sr-only peer peer-disabled:opacity-25" type="radio"
                                id="shipmenttpe_{{ $item->id }}" name="shipmenttypes" value="{{ $item->id }}"
                                @change="seleccionarenvio({{ $item }})" />
                            <label for="shipmenttpe_{{ $item->id }}"
                                class ="text-xs relative flex flex-col justify-center items-center border border-ringbutton gap-1 text-center font-medium ring-ringbutton text-colorlabel p-2.5 px-3 rounded-lg cursor-pointer hover:bg-fondohoverbutton hover:ring-fondohoverbutton hover:border-fondobutton hover:text-colorhoverbutton peer-checked:bg-fondohoverbutton peer-checked:ring-2 peer-checked:ring-ringbutton peer-checked:text-colorhoverbutton peer-focus:text-colorhoverbutton checked:bg-fondohoverbutton peer-disabled:opacity-25 transition ease-in-out duration-150">
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

<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="TIENDA WEB" route="productos">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M3.06164 15.1933L3.42688 13.1219C3.85856 10.6736 4.0744 9.44952 4.92914 8.72476C5.78389 8 7.01171 8 9.46734 8H14.5327C16.9883 8 18.2161 8 19.0709 8.72476C19.9256 9.44952 20.1414 10.6736 20.5731 13.1219L20.9384 15.1933C21.5357 18.5811 21.8344 20.275 20.9147 21.3875C19.995 22.5 18.2959 22.5 14.8979 22.5H9.1021C5.70406 22.5 4.00504 22.5 3.08533 21.3875C2.16562 20.275 2.4643 18.5811 3.06164 15.1933Z" />
                    <path
                        d="M7.5 8L7.66782 5.98618C7.85558 3.73306 9.73907 2 12 2C14.2609 2 16.1444 3.73306 16.3322 5.98618L16.5 8" />
                    <path d="M15 11C14.87 12.4131 13.5657 13.5 12 13.5C10.4343 13.5 9.13002 12.4131 9 11" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
        <x-link-breadcrumb text="CARRITO DE COMPRAS" route="carshoop">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
        <x-link-breadcrumb text="REGISTRAR ORDEN" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M11.5 21.9999C8.29487 21.9939 5.09603 21.5203 3.78549 20.6104C3.06418 20.1097 2.51361 19.4143 2.20352 18.6124C1.69716 17.3029 2.18147 15.6144 3.1501 12.2373L3.87941 9.67787C4.24669 8.38895 5.42434 7.5 6.76456 7.5H16.2371C17.5765 7.5 18.7537 8.38793 19.1217 9.67584L19.5 11" />
                    <path d="M7 7.5V6.36364C7 3.95367 9.01472 2 11.5 2C13.9853 2 16 3.95367 16 6.36364V7.5" />
                    <path d="M10 11H13">
                    </path>
                    <path d="M14 18H22M18 22L18 14" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="contenedor flex flex-col lg:flex-row gap-10">
        <div class="w-full flex-1 xl:pr-16">
            <div class="w-full mb-6">
                <h1 class="text-lg font-semibold text-primary">RESUMEN DEL PEDIDO</h1>
            </div>

            @if (Cart::instance('shopping')->count() > 0)
                <div class="w-full overflow-x-auto {{-- rounded-xl border border-borderminicard --}}">
                    <table class="w-full min-w-full text-[10px] md:text-xs">
                        <tbody class="divide-y">
                            @foreach (Cart::instance('shopping')->content() as $item)
                                <tr class="text-colorlabel">
                                    <td class="text-left py-2 align-middle">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="flex-shrink-0 w-16 h-16 xl:w-24 xl:h-24 rounded overflow-hidden">
                                                @if ($item->model->getImageURL())
                                                    <img src="{{ $item->model->getImageURL() }}" alt=""
                                                        class="block w-full h-full object-scale-down aspect-square">
                                                @else
                                                    <x-icon-file-upload
                                                        class="!w-full !h-full !m-0 !border-0 text-colorsubtitleform"
                                                        type="unknown" />
                                                @endif
                                            </div>
                                            <div
                                                class="w-full flex-1 sm:flex justify-between gap-3 items-center text-colorsubtitleform">
                                                <div class="w-full text-xs sm:flex-1">
                                                    <p class="w-full">
                                                        {{ $item->model->name }}</p>

                                                    @if (count($item->options->carshoopitems) > 0)
                                                        <div class="w-full mb-2 mt-1">
                                                            @foreach ($item->options->carshoopitems as $itemcarshop)
                                                                <h1
                                                                    class="text-primary text-[10px] leading-3 text-left">
                                                                    <span
                                                                        class="w-1.5 h-1.5 bg-primary inline-block rounded-full"></span>
                                                                    {{ $itemcarshop->name }}
                                                                </h1>
                                                            @endforeach
                                                        </div>
                                                    @endif


                                                    @if (!is_null($item->options->promocion_id))
                                                        @php
                                                            $prm = \App\Models\Promocion::find(
                                                                $item->options->promocion_id,
                                                            );
                                                            $prm = verifyPromocion($prm);
                                                        @endphp
                                                        @if (is_null($prm))
                                                            <span
                                                                class="text-red-600 inline-block ring-1 ring-red-600 text-[10px] p-0.5 px-1 rounded-lg mt-1">
                                                                PROMOCIÓN AGOTADO</span>
                                                        @else
                                                            <span
                                                                class="text-green-600 inline-block ring-1 ring-green-600 text-[10px] p-0.5 px-1 rounded-lg mt-1">
                                                                PROMOCIÓN</span>
                                                        @endif
                                                    @endif
                                                </div>


                                                <div class="flex items-end sm:items-center sm:w-60 sm:flex-shrink-0 ">
                                                    <span
                                                        class="text-left p-2 text-xs sm:text-end font-semibold whitespace-nowrap">
                                                        x{{ decimalOrInteger($item->qty) }}
                                                        {{ $item->model->unit->name }}
                                                    </span>
                                                    <span
                                                        class="p-2 font-semibold text-lg flex-1 text-end text-colorlabel whitespace-nowrap">
                                                        {{ $item->options->simbolo }}
                                                        {{ number_format($item->price * $item->qty, 2, '.', ', ') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <h1 class="text-xs p-3 font-medium text-colorerror">
                    NO EXISTEN PRODUCTOS AGREGADOS EN EL CARRITO...</h1>
            @endif
        </div>

        @if (auth()->user())
            <div class="w-full lg:w-96 lg:flex-shrink-0">
                <livewire:modules.marketplace.carrito.show-shippments :empresa="$empresa" :moneda="$moneda"
                    :pricetype="$pricetype" />
            </div>
        @endif
    </div>
</x-app-layout>

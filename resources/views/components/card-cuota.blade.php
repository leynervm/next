<div {{ $attributes->merge(['class' => $clases]) }}>
    <div class="w-full">
        @if (isset($titulo))
            <div class="text-center">
                <x-span-text :text="'Cuota' . $titulo" />
            </div>
        @endif

        {{ $slot }}

        @if (isset($detallepago))

            <h1 class="text-colortitleform mt-2 mb-1 underline text-center text-[10px]">
                DETALLES DEL PAGO</h1>

            <div class="w-full flex flex-wrap gap-1 text-[10px]">
                <x-span-text :text="formatDate($detallepago->date, 'DD MMMM Y')" class="leading-3 inline-block !text-[9px]" />
                <x-span-text :text="$detallepago->methodpayment->name" class="leading-3 inline-block !text-[9px]" />
                    <x-span-text :text="$detallepago->opencaja->caja->name" class="leading-3 inline-block !text-[9px]" />
                <x-span-text :text="'USUARIO :' . $detallepago->user->name" class="leading-3 inline-block !text-[9px]" />

                @if ($detallepago->detalle)
                    <x-span-text :text="$detallepago->detalle" class="leading-3 inline-block !text-[9px]" />
                @endif
            </div>
        @endif
    </div>

    @if (isset($footer))
        <div class="w-full mt-2">
            {{ $footer }}
        </div>
    @endif

</div>

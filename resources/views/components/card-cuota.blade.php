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
                <x-span-text :text="formatDate($detallepago->date, 'DD MMMM Y')" class="leading-3 inline-block !tracking-normal" />
                <x-span-text :text="$detallepago->methodpayment->name" class="leading-3 inline-block !tracking-normal" />
                <x-span-text :text="$detallepago->openbox->box->name" class="leading-3 inline-block !tracking-normal" />
                <x-span-text :text="'USUARIO :' . $detallepago->user->name" class="leading-3 inline-block !tracking-normal" />

                @if ($detallepago->detalle)
                    <x-span-text :text="$detallepago->detalle" class="leading-3 inline-block !tracking-normal" />
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

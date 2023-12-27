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
                <p class="inline-block uppercase bg-fondospancardproduct text-textspancardproduct rounded-lg p-0.5 px-1">
                    {{ \Carbon\Carbon::parse($detallepago->date)->locale('es')->isoformat('DD MMMM Y') }}
                </p>
                <p class="inline-block bg-fondospancardproduct text-textspancardproduct rounded-lg p-0.5 px-1">
                    {{ $detallepago->methodpayment->name }} </p>
                <p class="inline-block bg-fondospancardproduct text-textspancardproduct rounded-lg p-0.5 px-1">
                    USUARIO : {{ $detallepago->user->name }} </p>

                @if ($detallepago->detalle)
                    <p class="inline-block bg-fondospancardproduct text-textspancardproduct rounded-lg p-0.5 px-1">
                        {{ $detallepago->detalle }} </p>
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

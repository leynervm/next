@props(['cajamovimiento', 'moneda' => null])
<div
    {{ $attributes->merge(['class' => 'p-1 relative flex flex-col justify-between text-[10px] rounded-lg border border-borderminicard']) }}>
    <div class="w-full font-medium text-colorsubtitleform text-center text-[9px]">
        {{-- <p class="font-light text-sm">TEXTO UNO 1, 238.80</p>
        <p class="font-normal text-sm">TEXTO UNO 1, 238.80</p>
        <p class="font-medium text-sm">TEXTO UNO 1, 238.80</p>
        <p class="font-semibold text-sm">TEXTO UNO 1, 238.80</p> --}}

        @if (!empty($moneda))
            @if ($moneda->id !== $cajamovimiento->moneda_id)
                <p class="font-semibold text-[10px] align-middle leading-3 {{-- line-through --}}">
                    {{ $moneda->simbolo }}
                    {{ decimalOrInteger($cajamovimiento->amount, 2, ', ') }}
                    <small class="font-normal">{{ $moneda->currency }}</small>
                </p>
            @endif
        @endif

        <p class="text-xl text-colortitleform">
            <small class="text-[10px] font-medium">{{ $cajamovimiento->moneda->simbolo }}</small>
            {{ decimalOrInteger($cajamovimiento->totalamount, 2, ', ') }}
            <small class="text-[10px] font-medium">{{ $cajamovimiento->moneda->currency }}</small>
        </p>

        @if ($cajamovimiento->tipocambio > 0)
            <p class="text-xs leading-3">
                <small class="text-[8px]">TIPO CAMBIO :</small>
                {{ number_format($cajamovimiento->tipocambio, 2, '.', ', ') }}
            </p>
        @endif

        <h1 class="text-colorsubtitleform font-semibold text-sm">
            {{ $cajamovimiento->openbox->box->name }}</h1>

        <h1 class="">
            {{ formatDate($cajamovimiento->date, 'DD MMMM Y') }} /
            {{ $cajamovimiento->methodpayment->name }}
        </h1>

        <p>USUARIO : {{ $cajamovimiento->user->name }}</p>

        @if (!empty($cajamovimiento->detalle))
            <p>DETALLE : {{ $cajamovimiento->detalle }}</p>
        @endif
    </div>

    @if (isset($footer))
        <div class="w-full flex items-end justify-end gap-2">
            {{ $footer }}
        </div>
    @endif
</div>

@props(['title' => 'SALDO', 'simbolo', 'saldo' => null, 'diferenciasbytype' => []])
<label
    {{ $attributes->merge([
        'class' =>
            'peer-checked:border peer-checked:border-next-500 w-32 h-32 text-[10px] cursor-pointer inline-flex flex-col items-center justify-center relative bg-fondominicard text-colorlinknav shadow shadow-shadowminicard p-1 rounded-xl hover:shadow-md hover:shadow-shadowminicard transition-shadow ease-out duration-150',
    ]) }}">

    <div class="text-xs font-medium text-center">
        <small class="leading-3">{{ $title }}</small>

        <h3 class="font-semibold text-xl mb-2">
            {{ $simbolo }}
            @if (!is_null($saldo))
                {{ formatDecimalOrInteger($saldo, 2, ', ') }}
            @endif
        </h3>

        @if (count($diferenciasbytype) > 0)
            @foreach ($diferenciasbytype as $diferencia)
                <p class="text-xs text-colorsubtitleform flex items-center">
                    <span class="block truncate w-[35px] text-[8px]">{{ $diferencia->type }} : </span>
                    {{ number_format($diferencia->diferencia, 2, '.', ', ') }}
                </p>
            @endforeach
        @endif
    </div>
</label>

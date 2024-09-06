@props(['openbox', 'monthbox'])

<p class="text-colorlabel text-lg sm:text-3xl font-semibold text-end mb-3">
    @if ($openbox)
        {{ $openbox->box->name }}
    @else
        <small class="text-colorerror w-full block font-medium text-[10px] leading-3">
            APERTURA DE CAJA DIARIA NO DISPONIBLE...
        </small>
    @endif

    @if ($monthbox)
        <small class="w-full block font-medium text-[10px] sm:text-xs leading-3">
            {{ formatDate($monthbox->month, 'MMMM Y') }}</small>
    @else
        <small class="text-colorerror w-full block font-medium text-[10px] leading-3">
            APERTURA DE CAJA MENSUAL NO DISPONIBLE...
        </small>
    @endif
</p>

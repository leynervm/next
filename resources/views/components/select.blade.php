<select {!! $attributes->merge([
    'class' =>
        'p-1.5 px-5 text-sm bg-transparent border text-next-500 border-next-300 rounded-lg outline-0 focus:border-next-500 focus:shadow focus:shadow-next-300 focus:outline-none focus:ring-0 disabled:border-gray-300 transition ease duration-150',
    'data-placeholder' => 'Seleccionar...',
    'data-minimum-results-for-search' => '20',
    'data-dropdown-parent' => "#parent$id",
]) !!} id="{{ $id }}">
    {{-- 'data-minimum-results-for-search' => 'Infinity', --}}
    <option selected value="">SELECCIONAR...</option>
    @if (isset($options))
        {{ $options }}
    @endif
</select>

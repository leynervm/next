@props(['text'])

<label {{ $attributes->merge(['class' => 'text-xs font-semibold leading-3 text-next-500']) }}>
    {{ $text ?? $slot }}
</label>

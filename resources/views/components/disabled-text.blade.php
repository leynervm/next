@props(['text'])

<p {{ $attributes->merge(['class' => 'p-1.5 text-sm text-next-500 border border-next-300 rounded-sm']) }}>
    {{ $text }}
</p>

@props(['text'])

<p {{ $attributes->merge(['class' => 'p-1.5 text-sm text-colorlabel border border-next-300 rounded-lg']) }}>
    {{ $text }}
</p>

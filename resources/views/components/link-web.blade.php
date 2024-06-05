@props(['text'])
<a {{ $attributes->merge(['class' => 'btn-next']) }}>
    <span class="btn-effect">
        <span>{{ $text }}</span>
    </span>
</a>

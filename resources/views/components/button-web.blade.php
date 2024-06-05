@props(['text'])
<button {{ $attributes->merge(['class' => 'btn-next', 'type' => 'button']) }}>
    <span class="btn-effect">
        <span>{{ $text }}</span>
    </span>
</button>

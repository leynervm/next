@props(['text'])

<small
    {{ $attributes->merge(['class' => 'text-[10px] inline-block p-1 rounded-md tracking-widest font-medium bg-fondospancardproduct text-textspancardproduct']) }}>
    {{ $text }}
</small>

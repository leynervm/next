@props(['text', 'type' => 'bg-fondospancardproduct'])

@php

    switch ($type) {
        case 'next':
            $fondo = 'bg-next-500';
            break;

        case 'red':
            $fondo = 'bg-red-500';
            break;

        case 'green':
            $fondo = 'bg-green-500';
            break;

        case 'blue':
            $fondo = 'bg-blue-500';
            break;

        case 'orange':
            $fondo = 'bg-orange-500';
            break;

        default:
            $fondo = 'bg-fondospancardproduct';
            break;
    }

@endphp

<small
    {{ $attributes->merge(['class' => 'text-[10px] inline-block p-1 rounded-md tracking-widest font-medium text-textspancardproduct ' . $fondo]) }}>
    {{ $text }}
</small>

@props(['text', 'type' => null])

@php

    switch ($type) {
        case 'next':
            $class = 'bg-next-500 text-white';
            break;

        case 'red':
            $class = 'bg-red-500 text-white';
            break;

        case 'green':
            $class = 'bg-green-500 text-white';
            break;

        case 'blue':
            $class = 'bg-blue-500 text-white';
            break;

        case 'orange':
            $class = 'bg-orange-500 text-white';
            break;

        case 'amber':
            $class = 'bg-amber-500 text-white';
            break;

        default:
            $class = 'bg-fondospancardproduct text-textspancardproduct';
            break;
    }
@endphp

<small
    {{ $attributes->merge(['class' => 'text-[10px] inline-block p-1 rounded-md tracking-normal font-medium ' . $class]) }}>
    {{ $text }}</small>

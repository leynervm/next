@props(['align' => 'top'])
@php
    $classAlign = '';
    $classArrowAlign = '';
    switch ($align) {
        case 'bottom':
            $classAlign = 'left-0 top-[95%]';
            $classArrowAlign = 'left-[50%] -translate-x-[50%]  rotate-180 -top-3';
            break;
        case 'left':
            $classAlign = 'right-[80%]';
            $classArrowAlign = 'top-[50%] -translate-y-[50%] -right-3 -rotate-90';
            break;
        case 'right':
            $classAlign = 'left-[80%]';
            $classArrowAlign = 'top-[50%] -translate-y-[50%] -left-3 rotate-90';
            break;
        default:
            $classAlign = 'left-0 bottom-[95%]';
            $classArrowAlign = 'left-[50%] -translate-x-[50%]';
    }
@endphp
<div class="w-full group relative">
    <div
        class="opacity-0 p-1 text-white absolute w-full {{ $classAlign }} z-10 group-hover:opacity-100 transition-opacity">
        <span class="block bg-next-700 text-xs leading-none shadow shadow-shadowminicard text-center p-0.5 rounded-lg">
            {{ $slot }}
        </span>
        {{-- mx-auto -mt-1.5 --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="6 11 13 15" fill="currentColor"
            class="size-4 absolute {{ $classArrowAlign }} text-next-700">
            <path
                d="M18 9c.852 0 1.297 .986 .783 1.623l-.076 .084l-6 6a1 1 0 0 1 -1.32 .083l-.094 -.083l-6 -6l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057v-.118l.005 -.058l.009 -.06l.01 -.052l.032 -.108l.027 -.067l.07 -.132l.065 -.09l.073 -.081l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01l.057 -.004l12.059 -.002z" />
        </svg>
    </div>

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
        stroke-linecap="round" stroke-linejoin="round" class="size-8 text-colorsubtitleform mx-auto">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
        <path d="M12 9h.01" />
        <path d="M11 12h1v4h1" />
    </svg>
</div>

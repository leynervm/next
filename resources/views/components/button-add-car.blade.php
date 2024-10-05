@props(['classIcon' => null])
<button
    {{ $attributes->merge(['type' => 'button', 'class' => ' block group relative font-medium tracking-widest bg-next-500 text-white p-2 rounded-lg disabled:opacity-25 hover:bg-next-700 focus:bg-next-700 hover:border-next-700 focus:border-next-700 hover:ring-2 hover:ring-next-300 focus:ring-2 focus:ring-next-300 transition ease-in duration-150']) }}>

    @if (isset($slot))
        <span>{{ $slot }}</span>
    @endif

    <span class="block h-5 w-5 {{ $classIcon }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" stroke-width="2"
            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
            <path d="M12.5 17h-6.5v-14h-2" />
            <path d="M6 5l14 1l-.86 6.017m-2.64 .983h-10.5" />
            <path d="M16 19h6" />
            <path d="M19 16v6" />
        </svg>
    </span>
</button>

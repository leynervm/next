<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'block border border-next-500 group relative font-semibold text-sm bg-next-500 text-white p-1.5 rounded-sm hover:bg-next-700 focus:bg-next-700 hover:ring-2 hover:ring-next-700 focus:ring-2 focus:ring-next-300 hover:border-next-700 focus:border-next-700 disabled:opacity-25 transition ease-in duration-150']) }}>
    {{-- @if (isset($icono)) --}}
    <span class="block h-4 w-4 group-hover:animate-pulse group-focus:animate-pulse duration-150">
        {{ $slot }}
    </span>
    {{-- @endif --}}

</button>

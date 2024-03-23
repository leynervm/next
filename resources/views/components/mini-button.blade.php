<button
    {{ $attributes->merge(['class' => 'inline-block group relative font-semibold text-[10px] text-next-500 p-1 rounded-sm hover:bg-next-500 focus:bg-next-500 hover:ring-2 hover:ring-next-300 focus:ring-2 focus:ring-next-300 hover:text-white focus:text-white disabled:opacity-25 transition ease-in duration-150', 'type' => 'button']) }}>
    {{ $slot }}
</button>

<div class="relative">
    {{ $slot }}
    <label
        {{ $attributes->merge(['class' => 'text-xs flex justify-center items-center border border-ringbutton gap-1 text-center font-medium ring-ringbutton text-colorbutton p-2.5 px-3 bg-fondominicard rounded-sm cursor-pointer hover:bg-fondohoverbutton hover:ring-fondohoverbutton hover:border-fondobutton hover:text-colorhoverbutton peer-checked:bg-fondohoverbutton peer-checked:ring-2 peer-checked:ring-ringbutton peer-checked:text-colorhoverbutton peer-focus:text-colorhoverbutton checked:bg-fondohoverbutton peer-disabled:opacity-25 transition ease-in-out duration-150']) }}>

        @if (isset($cantidad))
            <span
                class="bg-next-500 text-white p-0.5 px-1 rounded-full leading-3 text-[9px] ring-1 ring-white">{{ $cantidad }}</span>
        @endif
        {{ $text }}
    </label>
    {{-- <span
        class="bg-white rounded-full p-0.5 absolute opacity-0 w-3 h-3 peer-checked:opacity-100 peer-focus:opacity-100 top-1 right-1 text-next-900 duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12" />
        </svg>
    </span> --}}
</div>

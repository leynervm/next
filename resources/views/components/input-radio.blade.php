<div class="relative">
    {{ $slot }}
    <label
        {{ $attributes->merge(['class' => 'text-xs relative flex justify-center items-center border border-ringbutton gap-1 text-center font-medium ring-ringbutton text-colorlabel p-2.5 px-3 bg-fondominicard rounded-sm cursor-pointer hover:bg-fondohoverbutton hover:ring-fondohoverbutton hover:border-fondobutton hover:text-colorhoverbutton peer-checked:bg-fondohoverbutton peer-checked:ring-2 peer-checked:ring-ringbutton peer-checked:text-colorhoverbutton peer-focus:text-colorhoverbutton checked:bg-fondohoverbutton peer-disabled:opacity-25 transition ease-in-out duration-150']) }}>

        @if (isset($cantidad))
            <span
                class="bg-next-500 text-white p-0.5 px-1 rounded-full leading-3 text-[9px] ring-1 ring-white">{{ $cantidad }}</span>
        @endif
        {{ $text }}
    </label>
</div>

<div class="relative">
    {{ $slot }}
    <label
        {{ $attributes->merge(['class' => 'text-xs flex justify-center text-center font-medium ring-2 ring-transparent text-next-500 p-2 px-4 bg-fondominicard border border-next-500 rounded-sm cursor-pointer hover:ring-next-300 peer-hover:ring-next-300 peer-checked:bg-next-700 peer-checked:border-next-700 peer-checked:ring-next-300 peer-checked:text-white checked:bg-next-700 transition ease-in-out duration-150']) }}>
        {{ $text }} </label>
    {{-- <span
        class="bg-white rounded-full p-0.5 absolute opacity-0 w-3 h-3 peer-checked:opacity-100 top-1 right-1 text-next-900 duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12" />
        </svg>
    </span> --}}
</div>

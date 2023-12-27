@props(['custom' => false])

<label
    {{ $attributes->merge(['class' => 'p-1.5 px-2 text-colordropdown text-xs font-medium cursor-pointer whitespace-nowrap block w-full rounded hover:bg-fondohoverdropdown transition ease-in-out duration-150']) }}>


    @if ($custom)
        <svg class="w-4 h-4 text-gray-500 opacity-75 peer-checked:text-primary peer-checked:opacity-100 duration-150"
            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="4"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
        </svg>
    @endif

    {{ $value ?? $slot }}

</label>

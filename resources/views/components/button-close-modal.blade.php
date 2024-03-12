<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'block group font-semibold text-sm bg-fondobuttonclose text-colorbuttonclose p-1.5 rounded-sm hover:bg-fondohoverbuttonclose focus:bg-fondohoverbuttonclose hover:ring-2 hover:ring-ringbuttonclose focus:ring-2 focus:ring-ringbuttonclose disabled:opacity-25 transition ease-in duration-150']) }}>
    <span class="block h-4 w-4 group-hover:animate-pulse group-focus:animate-pulse duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        </svg>
    </span>
</button>

<div class="relative" x-data="{ open: false }">
    <button x-on:click="open = !open" :class="{ 'bg-next-50': open, 'bg-transparent': !open }"
        class="border bg-transparent rounde-sm w-full text-xs font-semibold border-next-300 text-next-500 p-2 px-3 focus:ring-0 focus:border-next-400 text-center inline-flex items-center"
        type="button">
        {{ $titulo }}
        <svg :class="{ 'rotate-180': open }" class="w-4 h-full ml-2 transform transition duration-150" aria-hidden="true"
            fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
            </path>
        </svg>
    </button>

    <div :class="{ 'block': open, 'hidden': !open }" x-show="open" x-on:click.away="open = !open"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute hidden z-10 w-auto max-w-xs bg-fondodropdown rounded-lg shadow-md shadow-shadowminicard">
        @if (isset($pages))
            {{ $pages }}
        @endif

        <ul aria-labelledby="dropdownCheckboxButton">
            {{ $items }}
        </ul>

        @if (isset($loading))
            {{ $loading }}
        @endif
    </div>
</div>

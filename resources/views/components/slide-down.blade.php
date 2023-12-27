<div x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-y-[-10%]" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-[-10%]" x-show="show"
    {{ $attributes->merge(['class' => 'w-full block']) }}>
    {{ $slot }}
</div>

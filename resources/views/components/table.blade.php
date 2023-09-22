<div {{ $attributes->merge(['class' => 'overflow-x-auto border border-dividetable mt-3 md:rounded-lg']) }}>
    <table class="min-w-full">
        {{ $slot }}
    </table>
</div>

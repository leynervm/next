<div {{ $attributes->merge(['class' => 'overflow-x-auto border border-gray-200 mt-3 md:rounded-lg']) }}>
    <table class="min-w-full divide-y divide-gray-200">
        {{ $slot }}
    </table>
</div>

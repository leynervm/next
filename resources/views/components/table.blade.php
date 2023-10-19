<div {{ $attributes->merge(['class' => 'overflow-x-auto border border-dividetable mt-3 md:rounded-lg relative']) }}>
    <table class="min-w-full">
        @if (isset($header))
            <thead class="bg-fondoheadertable text-textheadertable text-xs">
                {{ $header }}
            </thead>
        @endif

        @if (isset($body))
            <tbody class="bg-fondobodytable divide-y divide-dividetable text-textbodytable">
                {{ $body }}
            </tbody>
        @endif
    </table>

    @if (isset($loading))
        {{ $loading }}
    @endif
</div>

<div class="min-h-screen flex flex-col justify-center items-center bg-fondominicard">
    <div>
        {{ $logo }}
    </div>

    <div
        class="w-full sm:max-w-md px-6 py-4 bg-fondominicard shadow-md shadow-shadowminicard overflow-hidden rounded-lg">
        {{ $slot }}
    </div>
</div>

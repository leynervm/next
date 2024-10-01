<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="default-theme" content="{{ config('app.theme') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="{{ config('app.theme') }}">
    <div class="w-full relative flex items-center justify-center min-h-screen bg-body sm:items-center sm:pt-0">
        <div class="w-full max-w-3xl mx-auto px-3 sm:px-6 lg:px-8">
            @yield('image')

            <div class="flex flex-col items-center pt-8 justify-center sm:pt-0">
                <div class="px-4 text-6xl font-semibold italic text-primary md:border-b md:border-borderminicard tracking-wider">
                    @yield('code')
                </div>

                <div class="ml-4 text-xs md:text-lg text-colorsubtitleform uppercase tracking-wider">
                    @yield('message')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>

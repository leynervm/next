<x-app-layout>
    <div class="pt-4">
        <div class="min-h-screen flex flex-col items-center py-8 sm:pt-0">
            {{-- <div>
                <x-jet-authentication-card-logo />
            </div> --}}
            {{-- <div class="w-full max-w-sm h-32 xs:mx-auto xs:col-span-3 sm:col-span-3 md:col-span-1">
                @if ($empresa->image)
                    <img class="w-full h-full object-scale-down" src="{{ $empresa->image->getLogoEmpresa() }}"
                        alt="">
                @else
                    <h1 class="text-center p-3 font-bold tracking-widest text-xl leading-5 truncate max-w-xs">
                        {{ $empresa->name }}</h1>
                @endif
            </div> --}}

            <div class="w-full text-colorsubtitleform sm:max-w-4xl p-0 sm:p-6 bg-fondominicard shadow-md overflow-hidden sm:rounded-lg prose">
                {!! $terms !!}
            </div>
        </div>
    </div>
</x-app-layout>

<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            @if ($empresa)
                @if ($empresa->logo)
                    <img class="w-full max-w-60 h-auto max-h-40 object-scale-down"
                        src="{{ getLogoEmpresa($empresa->logo, request()->isSecure() ? true : false) }}"
                        alt="{{ $empresa->name }}">
                @endif
            @endif
        </x-slot>

        <div class="mb-4 text-sm text-colorsubtitleform">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            {{-- <div class="mb-4 inline-block mr-auto font-medium text-sm bg-green-100 text-green-600 rounded-lg p-2">
                {{ session('status') }}
            </div> --}}

            <div class="w-full mb-4 flex items-center gap-2 p-2 sm:p-4 border border-green-300 rounded-xl bg-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none"
                    stroke-width="0" stroke-linecap="round" stroke-linejoin="round"
                    class="shrink-0 block rounded-full bg-white size-5 fill-green-600">
                    <path
                        d="M12,0A12,12,0,1,0,24,12,12.014,12.014,0,0,0,12,0Zm6.927,8.2-6.845,9.289a1.011,1.011,0,0,1-1.43.188L5.764,13.769a1,1,0,1,1,1.25-1.562l4.076,3.261,6.227-8.451A1,1,0,1,1,18.927,8.2Z" />
                </svg>
                <p class="w-full flex-1 text-green-700 text-sm leading-tight">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" onsubmit="disabledform(this)">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" name="email" class="block w-full p-2.5" :value="old('email')"
                    required autofocus />
            </div>

            @if ($errors->all())
                {{-- <ul
                    class="w-full my-4 list-none list-inside font-medium text-xs bg-red-100 text-colorerror rounded-lg p-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul> --}}

                <div
                    class="w-full mt-4 flex gap-2 items-center p-2 sm:p-4 border border-rose-300 rounded-xl bg-rose-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none"
                        stroke-width="0" stroke-linecap="round" stroke-linejoin="round"
                        class="shrink-0 fill-rose-600 rounded-full block size-5 bg-white">
                        <path
                            d="M11.983,0a12.206,12.206,0,0,0-8.51,3.653A11.8,11.8,0,0,0,0,12.207,11.779,11.779,0,0,0,11.8,24h.214A12.111,12.111,0,0,0,24,11.791h0A11.766,11.766,0,0,0,11.983,0ZM10.5,16.542a1.476,1.476,0,0,1,1.449-1.53h.027a1.527,1.527,0,0,1,1.523,1.47,1.475,1.475,0,0,1-1.449,1.53h-.027A1.529,1.529,0,0,1,10.5,16.542ZM11,12.5v-6a1,1,0,0,1,2,0v6a1,1,0,1,1-2,0Z" />
                    </svg>
                    <ul class="w-full flex-1 text-rose-800 text-sm leading-tight">
                        @foreach ($errors->all() as $error)
                            <li class="leading-none">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex items-center justify-center mt-4">
                <x-button-web type="submit" :text="__('Email Password Reset Link')" />
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>

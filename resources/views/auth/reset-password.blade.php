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

        <form method="POST" action="{{ route('password.update') }}"
            onsubmit="disabledform(this);localStorage.setItem('activeForm', 'login')" class="w-full flex flex-col gap-2"
            x-on:submit.prevent="localStorage.setItem('activeForm', 'login')">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                @if (old('email', $request->email))
                    <x-input id="email" type="hidden" name="email" :value="old('email', $request->email)" required />
                    <x-disabled-text :text="old('email', $request->email)" class="block w-full" />
                @else
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)"
                        required autofocus />
                @endif
            </div>

            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input class="block w-full" id="password" type="password" name="password" required
                    autocomplete="new-password" />
            </div>

            <div>
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input class="block w-full" id="password_confirmation" type="password" name="password_confirmation"
                    required autocomplete="new-password" />
            </div>

            @if ($errors->all())
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

            <div class="w-full flex items-center justify-center mt-4">
                <x-button-web type="submit" :text="__('Reset Password')" />
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>

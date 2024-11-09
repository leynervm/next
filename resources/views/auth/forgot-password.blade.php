<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            @if ($empresa)
                @if ($empresa->logo)
                    <img class="w-full max-w-60 h-auto max-h-40 object-scale-down"
                        src="{{ getLogoEmpresa($empresa->logo, false) }}" alt="{{ $empresa->name }}">
                @endif
            @endif
        </x-slot>

        <div class="mb-4 text-sm text-colorsubtitleform">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 inline-block mr-auto font-medium text-sm bg-green-100 text-green-600 rounded-lg p-2">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" name="email" class="block w-full p-2.5" :value="old('email')"
                    required autofocus />
            </div>

            @if ($errors->all())
                <ul
                    class="w-full my-4 list-disc list-inside font-medium text-xs bg-red-100 text-colorerror rounded-lg p-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="flex items-center justify-center mt-4">
                <x-button-web type="submit" :text="__('Email Password Reset Link')" />
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>

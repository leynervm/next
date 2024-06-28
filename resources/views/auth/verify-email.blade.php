<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            @php
                $empresa = mi_empresa();
            @endphp
            @if ($empresa)
                @if ($empresa->image)
                    <img class="w-48 h-32 object-scale-down" src="{{ $empresa->image->getLogoEmpresa() }}" alt="">
                @endif
            @endif
        </x-slot>

        <div class="mb-4 text-sm text-colorlabel">
            {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
            </div>
        @endif

        <div class="mt-4 flex flex-col gap-3 items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button-web type="submit" :text="__('Resend Verification Email')" />
                </div>
            </form>

            <div class="flex items-end gap-3">
                <a href="{{ route('profile') }}"
                    class="underline tracking-wide uppercase text-xs text-colorsubtitleform hover:text-hoverlinktable">
                    {{ __('Edit Profile') }}</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit"
                        class="underline tracking-wide uppercase text-xs text-colorsubtitleform hover:text-hoverlinktable ml-2">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>

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

        <div class="mb-4 text-sm text-colorlabel">
            {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="w-full mb-4 flex items-center gap-2 p-2 sm:p-4 border border-green-300 rounded-xl bg-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none"
                    stroke-width="0" stroke-linecap="round" stroke-linejoin="round"
                    class="shrink-0 block rounded-full bg-white size-5 fill-green-600">
                    <path
                        d="M12,0A12,12,0,1,0,24,12,12.014,12.014,0,0,0,12,0Zm6.927,8.2-6.845,9.289a1.011,1.011,0,0,1-1.43.188L5.764,13.769a1,1,0,1,1,1.25-1.562l4.076,3.261,6.227-8.451A1,1,0,1,1,18.927,8.2Z" />
                </svg>
                <p class="w-full flex-1 text-green-700 text-sm leading-tight">
                    {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
                </p>
            </div>

            {{-- <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
            </div> --}}
        @endif

        <div class="mt-4 flex flex-col gap-3 items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}" onsubmit="disabledform(this)">
                @csrf

                <div>
                    <x-button-web type="submit" :text="__('Resend Verification Email')" />
                </div>
            </form>

            <div class="w-full flex items-end justify-center gap-3 mt-4">
                <x-link-button href="{{ route('profile') }}" class="uppercase">
                    {{ __('Edit Profile') }}</x-link-button>

                <form method="POST" action="{{ route('logout') }}" class="inline" onsubmit="disabledform(this)">
                    @csrf

                    <x-button-secondary type="submit" x-on:click="localStorage.setItem('activeForm', 'login')">
                        {{ __('Log Out') }}
                    </x-button-secondary>
                </form>
            </div>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>

<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            @if ($empresa)
                @if ($empresa->logo)
                    <img class="w-full max-w-60 h-auto max-h-40 object-scale-down"
                        src="{{ getLogoEmpresa($empresa->logo,  request()->isSecure() ? true : false) }}" alt="">
                @endif
            @endif
        </x-slot>

        <div class="mb-4 text-sm text-colorlabel text-center">
            {{ __('Antes de continuar, complete los datos de su perfil.') }}
        </div>

        <form id="form_complete_profile" action="{{ route('profile.complete.save') }}" method="post"
            class="w-full flex flex-col gap-5">
            @csrf
            <x-form-card :titulo="__('Profile Information')" :subtitulo="__('Update your account\'s profile information and email address.')" classtitulo="!text-lg">
                <div class="w-full grid grid-cols-1 gap-2">
                    <div class="w-full">
                        <x-label for="name" value="{{ __('Document') }}" />
                        @if (empty(auth()->user()->document))
                            <x-input id="document" type="text" class="first-letter:block w-full" name="document"
                                value="{{ old('document') ?? auth()->user()->document }}" autocomplete="document" />
                        @else
                            <x-disabled-text :text="auth()->user()->document" />
                        @endif
                        <x-jet-input-error for="document" />
                    </div>

                    <div class="w-full">
                        <x-label for="name" value="{{ __('Name') }}" />
                        <x-input id="name" type="text" name="name" class="first-letter:block w-full"
                            value="{{ old('name') ?? auth()->user()->name }}" autocomplete="name" />
                        <x-jet-input-error for="name" />
                    </div>

                    <div class="w-full">
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-disabled-text :text="auth()->user()->email" />
                        <x-jet-input-error for="email" />
                    </div>

                    @if (auth()->user()->password)
                        <div class="w-full">
                            <x-label for="current_password" value="{{ __('Current Password') }}" />
                            <x-input id="current_password" type="password" class="block w-full" name="current_password"
                                autocomplete="current-password" />
                            <x-jet-input-error for="current_password" />
                        </div>
                    @endif
                </div>
            </x-form-card>

            @if (empty(auth()->user()->password))
                <x-form-card :titulo="__('UPDATE PASSWORD')" :subtitulo="__('Ensure your account is using a long, random password to stay secure.')" class="">
                    <div class="w-full grid grid-cols-1 gap-2">
                        <div class="w-full">
                            <x-label for="password" value="{{ __('New Password') }}" />
                            <x-input id="password" name="password" type="password" class="block w-full"
                                autocomplete="new-password" />
                            <x-jet-input-error for="password" />
                        </div>

                        <div class="w-full">
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                            <x-input id="password_confirmation" type="password" class="block w-full"
                                name="password_confirmation" autocomplete="new-password" />
                            <x-jet-input-error for="password_confirmation" />
                        </div>
                    </div>
                    <x-jet-input-error for="g_recaptcha_response" />
                </x-form-card>
            @endif

            <div class="w-full flex justify-center items-center">
                <x-button-web type="submit" :text="__('Save changes')" />
            </div>
        </form>


        <form method="POST" action="{{ route('logout') }}" class="w-full mt-5 flex justify-center items-end gap-3">
            @csrf
            <button class="text-colorsubtitleform underline hover:text-colorlabel transition ease-in-out duration-150"
                type="submit">{{ __('Log Out') }}</button>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>

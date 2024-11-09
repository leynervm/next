<x-guest-layout>
    {{-- <div class="social-icons">
        <a href="#" class="icons">
            <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full block">
                <path
                    d="M30.0014 16.3109C30.0014 15.1598 29.9061 14.3198 29.6998 13.4487H16.2871V18.6442H24.1601C24.0014 19.9354 23.1442 21.8798 21.2394 23.1864L21.2127 23.3604L25.4536 26.58L25.7474 26.6087C28.4458 24.1665 30.0014 20.5731 30.0014 16.3109Z"
                    fill="#4285F4" />
                <path
                    d="M16.2863 29.9998C20.1434 29.9998 23.3814 28.7553 25.7466 26.6086L21.2386 23.1863C20.0323 24.0108 18.4132 24.5863 16.2863 24.5863C12.5086 24.5863 9.30225 22.1441 8.15929 18.7686L7.99176 18.7825L3.58208 22.127L3.52441 22.2841C5.87359 26.8574 10.699 29.9998 16.2863 29.9998Z"
                    fill="#34A853" />
                <path
                    d="M8.15964 18.769C7.85806 17.8979 7.68352 16.9645 7.68352 16.0001C7.68352 15.0356 7.85806 14.1023 8.14377 13.2312L8.13578 13.0456L3.67083 9.64746L3.52475 9.71556C2.55654 11.6134 2.00098 13.7445 2.00098 16.0001C2.00098 18.2556 2.55654 20.3867 3.52475 22.2845L8.15964 18.769Z"
                    fill="#FBBC05" />
                <path
                    d="M16.2864 7.4133C18.9689 7.4133 20.7784 8.54885 21.8102 9.4978L25.8419 5.64C23.3658 3.38445 20.1435 2 16.2864 2C10.699 2 5.8736 5.1422 3.52441 9.71549L8.14345 13.2311C9.30229 9.85555 12.5086 7.4133 16.2864 7.4133Z"
                    fill="#EB4335" />
            </svg>
        </a>
        <a href="#" class="icons">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor"
                stroke-width="0.1" stroke-linecap="round" stroke-linejoin="round"
                class="w-full h-full block text-blue-700">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
            </svg>
        </a>
    </div>
    <span>or use your email to registration</span> --}}

    @if (session('activeForm'))
        <script>
            localStorage.setItem('activeForm', '{{ session('activeForm') }}');
        </script>
    @endif

    @if (session('mensaje'))
        <div
            class="absolute box-border top-3 left-[50%] -translate-x-[50%] w-full max-w-[420px] md:max-w-[768px] rounded-xl mx-auto overflow-hidden">
            <div
                class="w-full flex items-start gap-4 rounded border border-amber-100 bg-amber-50 px-4 py-3 text-sm text-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="h-5 w-5 block flex-shrink-0">
                    <path
                        d="M5.63604 18.364C4.00736 16.7353 3 14.4853 3 12C3 7.02944 7.02944 3 12 3C14.4853 3 16.7353 4.00736 18.364 5.63604M20.2941 8.5C20.7487 9.57589 21 10.7586 21 12C21 16.9706 16.9706 21 12 21C10.7586 21 9.57589 20.7487 8.5 20.2941" />
                    <path
                        d="M15.8292 3.82166C18.5323 2.13953 20.7205 1.51952 21.6005 2.39804C23.1408 3.93558 20.0911 9.48095 14.7889 14.784C9.48663 20.087 3.93971 23.1395 2.39946 21.602C1.52414 20.7282 2.13121 18.56 3.79165 15.8775" />
                </svg>
                <div class="flex-1  text-ellipsis">
                    <h3 class="w-full mb-2 font-semibold">ERROR DE LOGIN</h3>
                    <p class="flex-1 text-ellipsis overflow-hidden w-full text-xs">
                        {{ session('mensaje') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="login" x-data="authForm">
        <div class="container-login" id="container" x-bind:class="{ 'active': activeForm === 'register' }">
            @if ($empresa && Module::isEnabled('Marketplace'))
                <div class="form-container sign-up" style="display: none" x-show="activeForm === 'register'" x-cloak>
                    <form @submit.prevent="submitPrevent" method="POST" action="{{ route('register') }}"
                        id="register_form">
                        @csrf
                        <h1 class="title-login">{{ __('Create Account') }}</h1>

                        <input type="number" class="block w-full input-number-none" id="document" name="document"
                            placeholder="DNI / RUC" value="{{ old('document') }}" required
                            onkeypress="return validarNumero(event, 11)"
                            onpaste="return validarPasteNumero(event, 11)" />
                        <x-jet-input-error for="document" class="w-full" />

                        <input type="text" id="name" name="name" placeholder="Nombres"
                            value="{{ old('name') }}" autocomplete="name" required />
                        <x-jet-input-error for="name" class="w-full" />

                        <input type="email" id="emailregister" name="email" placeholder="Correo electrónico"
                            value="{{ old('email') }}" required />
                        <x-jet-input-error for="email" class="w-full" />

                        <input type="password" id="passwordregister" name="password" placeholder="Contraseña"
                            autocomplete="current-password" required />
                        <x-jet-input-error for="password" class="w-full" />

                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="Confirmar contraseña" autocomplete="current-password" required />
                        <x-jet-input-error for="password_confirmation" class="w-full" />

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="w-full mt-4">
                                <x-jet-label for="terms">
                                    <div class="flex justify-center items-center">
                                        <x-input type="checkbox" name="terms" id="terms" class="!rounded-none" />

                                        <div class="flex-1 w-full ml-2 text-colorsubtitleform text-xs leading-3">
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' =>
                                                    '<a target="_blank" href="' .
                                                    route('terms.show') .
                                                    '" class="underline text-xs text-orange-600 hover:text-orange-900">' .
                                                    __('Terms of Service') .
                                                    '</a>',
                                                'privacy_policy' =>
                                                    '<a target="_blank" href="' .
                                                    route('policy.show') .
                                                    '" class="underline text-xs text-orange-600 hover:text-orange-900">' .
                                                    __('Privacy Policy') .
                                                    '</a>',
                                            ]) !!}
                                        </div>
                                    </div>
                                </x-jet-label>
                                <x-jet-input-error for="terms" class="w-full" />
                            </div>
                        @endif

                        <div class="g-recaptcha py-3" data-sitekey="{{ config('services.recaptcha_v2.key_web') }}">
                        </div>
                        <x-jet-input-error for="g-recaptcha-response" class="w-full" />

                        <x-button-web type="submit" :text="__('Register')" />

                        <h3>Registrarse con</h3>

                        <div class="sign-up__social">
                            <a class="sign-up__social-button facebook" href="{{ route('auth.redirect', 'facebook') }}">
                                <svg viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="block w-6 h-6">
                                    <path
                                        d="M16.5 8C16.5 3.6 12.9 0 8.5 0C4.1 0 0.5 3.6 0.5 8C0.5 12 3.4 15.3 7.2 15.9V10.3H5.2V8H7.2V6.2C7.2 4.2 8.4 3.1 10.2 3.1C11.1 3.1 12 3.3 12 3.3V5.3H11C10 5.3 9.7 5.9 9.7 6.5V8H11.9L11.5 10.3H9.6V16C13.6 15.4 16.5 12 16.5 8Z"
                                        fill="currentColor" />
                                </svg>
                                <span>
                                    Iniciar sesión con Facebook
                                </span>
                            </a>
                            <a class="sign-up__social-button google" href="{{ route('auth.redirect', 'google') }}">
                                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 block">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M15.8299 8.18184C15.8299 7.61456 15.779 7.06911 15.6845 6.54547H8.1499V9.64002H12.4554C12.2699 10.64 11.7063 11.4873 10.859 12.0546V14.0619H13.4445C14.9572 12.6691 15.8299 10.6182 15.8299 8.18184Z"
                                        fill="#4285F4" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.14959 16C10.3096 16 12.1205 15.2836 13.4442 14.0618L10.8587 12.0545C10.1423 12.5345 9.22596 12.8181 8.14959 12.8181C6.06595 12.8181 4.30231 11.4109 3.67322 9.51996H1.00049V11.5927C2.31685 14.2072 5.02232 16 8.14959 16Z"
                                        fill="#34A853" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.67355 9.51996C3.51355 9.03996 3.42264 8.52724 3.42264 7.99996C3.42264 7.47269 3.51355 6.95996 3.67355 6.47996V4.40723H1.00081C0.458994 5.48723 0.149902 6.70905 0.149902 7.99996C0.149902 9.29087 0.458994 10.5127 1.00081 11.5927L3.67355 9.51996Z"
                                        fill="#FBBC05" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.14959 3.18183C9.32414 3.18183 10.3787 3.58546 11.2078 4.37819L13.5023 2.08364C12.1169 0.792729 10.306 0 8.14959 0C5.02232 0 2.31685 1.79273 1.00049 4.40728L3.67322 6.48001C4.30231 4.5891 6.06595 3.18183 8.14959 3.18183Z"
                                        fill="#EA4335" />
                                </svg>

                                <span>
                                    Iniciar sesión con Google
                                </span>
                            </a>
                        </div>

                        <div class="w-full pt-3 text-center md:hidden">
                            <span class="tittle-footer-login">
                                {{ __("I'm already registered") }}
                                <button class="link-footer-login" type="button" @click="setActiveForm('login')"
                                    id="minilogin">{{ __('Log in') }}</button>
                            </span>
                        </div>
                    </form>
                </div>
            @endif

            <div class="form-container sign-in" style="display: none" x-show="activeForm === 'login'" x-cloak>
                <form @submit.prevent="submitPrevent" method="POST" action="{{ route('login') }}" id="login_form">
                    @csrf

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h1 class="title-login">{{ __('Log in') }}</h1>

                    <input type="email" placeholder="{{ __('Email') }}" id="email" name="email" autofocus
                        value="{{ old('email') }}" required />
                    <x-jet-input-error for="email" />


                    <input type="password" placeholder="Contraseña" id="password" name="password" required
                        autocomplete="current-password" />

                    <div class="w-full">
                        <x-label-check for="remember_me" class="uppercase bg-transparent !text-colorsubtitleform">
                            <x-input name="remember" type="checkbox" id="remember_me" />
                            {{ __('Remember me') }}</x-label-check>
                    </div>

                    <x-jet-input-error for="g_recaptcha_response" />

                    <a href="{{ route('password.request') }}" class="forget">{{ __('Forgot your password?') }}</a>

                    <x-button-web type="submit" :text="__('Log in')" />

                    @if ($empresa && Module::isEnabled('Marketplace'))
                        <div class="w-full pt-3 text-center md:hidden">
                            <span class="tittle-footer-login">
                                {{ __("I'm not registered yet?") }}
                                <button class="link-footer-login" type="button" id="miniregister"
                                    @click="setActiveForm('register')">{{ __('Create Account') }}</button>
                            </span>
                        </div>

                        <div class="sign-up__social mt-5">
                            <a class="sign-up__social-button facebook"
                                href="{{ route('auth.redirect', 'facebook') }}">
                                <svg viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="block w-6 h-6">
                                    <path
                                        d="M16.5 8C16.5 3.6 12.9 0 8.5 0C4.1 0 0.5 3.6 0.5 8C0.5 12 3.4 15.3 7.2 15.9V10.3H5.2V8H7.2V6.2C7.2 4.2 8.4 3.1 10.2 3.1C11.1 3.1 12 3.3 12 3.3V5.3H11C10 5.3 9.7 5.9 9.7 6.5V8H11.9L11.5 10.3H9.6V16C13.6 15.4 16.5 12 16.5 8Z"
                                        fill="currentColor" />
                                </svg>
                                <span>
                                    Iniciar sesión con Facebook
                                </span>
                            </a>
                            <a class="sign-up__social-button google" href="{{ route('auth.redirect', 'google') }}">
                                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 block">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M15.8299 8.18184C15.8299 7.61456 15.779 7.06911 15.6845 6.54547H8.1499V9.64002H12.4554C12.2699 10.64 11.7063 11.4873 10.859 12.0546V14.0619H13.4445C14.9572 12.6691 15.8299 10.6182 15.8299 8.18184Z"
                                        fill="#4285F4" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.14959 16C10.3096 16 12.1205 15.2836 13.4442 14.0618L10.8587 12.0545C10.1423 12.5345 9.22596 12.8181 8.14959 12.8181C6.06595 12.8181 4.30231 11.4109 3.67322 9.51996H1.00049V11.5927C2.31685 14.2072 5.02232 16 8.14959 16Z"
                                        fill="#34A853" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.67355 9.51996C3.51355 9.03996 3.42264 8.52724 3.42264 7.99996C3.42264 7.47269 3.51355 6.95996 3.67355 6.47996V4.40723H1.00081C0.458994 5.48723 0.149902 6.70905 0.149902 7.99996C0.149902 9.29087 0.458994 10.5127 1.00081 11.5927L3.67355 9.51996Z"
                                        fill="#FBBC05" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.14959 3.18183C9.32414 3.18183 10.3787 3.58546 11.2078 4.37819L13.5023 2.08364C12.1169 0.792729 10.306 0 8.14959 0C5.02232 0 2.31685 1.79273 1.00049 4.40728L3.67322 6.48001C4.30231 4.5891 6.06595 3.18183 8.14959 3.18183Z"
                                        fill="#EA4335" />
                                </svg>

                                <span>
                                    Iniciar sesión con Google
                                </span>
                            </a>

                            <a href="{{ in_array(url()->previous(), [route('login'), route('register')]) ? '/' : url()->previous() }}"
                                class="text-xs mx-auto inline-block text-center p-2.5 hover:text-texthovertable text-colorsubtitleform transition ease-in-out duration-150">
                                VOLVER</a>
                        </div>
                    @endif
                </form>
            </div>
            <div class="toggle-container">
                <div class="toggle">
                    @if ($empresa && Module::isEnabled('Marketplace'))
                        <div class="toggle-panel toggle-left">
                            <h1>{{ __('Welcome') }}!</h1>
                            <h5>{{ __('Enter your Personal details to use all of site features') }}</h5>
                            <x-button-web class="btn-white" :text="__('Log in')" id="login"
                                @click="setActiveForm('login')" />
                        </div>
                    @endif
                    <div class="toggle-panel toggle-right">
                        <h1>{{ __('Hola, Somos NEXT!') }}</h1>

                        @if ($empresa && Module::isEnabled('Marketplace'))
                            <h5>{{ __('Register with your Personal details to use all of site features') }}</h5>
                            <x-button-web class="btn-white" :text="__('Register')" id="register"
                                @click="setActiveForm('register')" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script async src="https://www.google.com/recaptcha/api.js"></script> --}}

    <script>
        function authForm() {
            return {
                activeForm: 'login',
                init() {
                    const storedForm = localStorage.getItem('activeForm');
                    if (storedForm) {
                        this.activeForm = storedForm;
                    }
                },
                setActiveForm(form) {
                    this.activeForm = form;
                    localStorage.setItem('activeForm', form);
                },
                submitPrevent(e) {
                    let form = e.target;
                    let submitButton = form.querySelector('[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('container');
            const registerBtn = document.getElementById('register');
            const loginBtn = document.getElementById('login')
            const registerSM = document.getElementById('miniregister');
            const loginSM = document.getElementById('minilogin')

            if (registerBtn) {
                registerBtn.addEventListener('click', () => {
                    container.classList.add('active');
                });
            }

            if (registerSM) {
                registerSM.addEventListener('click', () => {
                    container.classList.add('active');
                });
            }

            if (loginBtn) {
                loginBtn.addEventListener('click', () => {
                    container.classList.remove('active');
                });
            }

            if (loginBtn) {
                loginSM.addEventListener('click', () => {
                    container.classList.remove('active');
                });
            }
        })
    </script>
</x-guest-layout>

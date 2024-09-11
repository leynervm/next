<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="PERFIL" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M7.78256 17.1112C6.68218 17.743 3.79706 19.0331 5.55429 20.6474C6.41269 21.436 7.36872 22 8.57068 22H15.4293C16.6313 22 17.5873 21.436 18.4457 20.6474C20.2029 19.0331 17.3178 17.743 16.2174 17.1112C13.6371 15.6296 10.3629 15.6296 7.78256 17.1112Z" />
                    <path
                        d="M15.5 10C15.5 11.933 13.933 13.5 12 13.5C10.067 13.5 8.5 11.933 8.5 10C8.5 8.067 10.067 6.5 12 6.5C13.933 6.5 15.5 8.067 15.5 10Z" />
                    <path
                        d="M2.854 16C2.30501 14.7664 2 13.401 2 11.9646C2 6.46129 6.47715 2 12 2C17.5228 2 22 6.46129 22 11.9646C22 13.401 21.695 14.7664 21.146 16" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="w-full contenedor flex flex-col gap-3 xl:gap-8 mx-auto p-1 sm:p-3">
        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            @livewire('profile.update-profile-information-form')
        @endif

        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <div class="w-full">
                @livewire('profile.update-password-form')
            </div>
        @endif

        <div class="w-full">
            @livewire('profile.logout-other-browser-sessions-form')
        </div>

        @if (auth()->user())
            @if (!auth()->user()->isAdmin())
                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="">
                        @livewire('profile.delete-user-form')
                    </div>
                @endif
            @endif
        @endif
    </div>
</x-app-layout>

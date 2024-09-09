<x-jet-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">

            <!-- Profile Photo -->
            {{-- @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div x-data="{ photoName: null, photoPreview: null }" class="w-full">
                    <!-- Profile Photo File Input -->
                    <input type="file" class="hidden" wire:model="photo" x-ref="photo"
                        x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                    <x-label for="photo" value="{{ __('Photo') }}" />

                    <!-- Current Profile Photo -->
                    <div class="mt-2" x-show="! photoPreview">
                        <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}"
                            class="rounded-full h-20 w-20 object-cover border border-borderminicard">
                    </div>

                    <!-- New Profile Photo Preview -->
                    <div class="mt-2" x-show="photoPreview" style="display: none;">
                        <span
                            class="overflow-hidden block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center border border-borderminicard"
                            x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>

                    <div class="w-full flex flex-wrap gap-2 mt-2">
                        @if ($this->user->profile_photo_path)
                            <x-button-secondary type="button" wire:click="deleteProfilePhoto">
                                {{ __('Remove Photo') }}
                            </x-button-secondary>
                        @endif
                        <x-button type="button" x-on:click.prevent="$refs.photo.click()">
                            {{ __('Select A New Photo') }}
                        </x-button>
                    </div>
                    <x-jet-input-error for="photo" class="mt-2" />
                </div>
            @endif --}}

            <!-- Document -->
            <div class="w-full">
                <x-label for="name" value="{{ __('Document') }}" />
                <x-input id="document" type="text" class="mt-1 block w-full" wire:model.defer="state.document"
                    autocomplete="document" name="document" />
                <x-jet-input-error for="document" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="w-full">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name"
                    autocomplete="name" name="name" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="w-full">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
                <x-jet-input-error for="email" class="mt-2" />

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) &&
                        !$this->user->hasVerifiedEmail())
                    <p class="text-sm mt-2 text-colorsubtitleform">
                        {{ __('Your email address is unverified.') }}

                        <button type="button" class="underline text-sm text-linktable hover:text-hoverlinktable"
                            wire:click.prevent="sendEmailVerification">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if ($this->verificationLinkSent)
                        <p v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                @endif
            </div>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-button type="submit" wire:loading.attr="disabled">
            {{ __('Save') }}</x-button>
    </x-slot>
</x-jet-form-section>

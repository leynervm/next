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

            <!-- Email -->
            <div class="w-full">
                <x-label for="email" value="{{ __('Email') }}" />
                @if (empty($state['email']))
                    <x-input id="email" type="email" class="block w-full" wire:model.defer="state.email" />
                @else
                    <x-disabled-text :text="$state['email']" />
                @endif
                <x-jet-input-error for="email" class="mt-2" />

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) &&
                        !$this->user->hasVerifiedEmail())
                    <p class="text-xs mt-2 text-colorsubtitleform">
                        {{ __('Your email address is unverified.') }}</p>
                    <div>
                        <x-button type="button" class="inline-flex" wire:loading.attr="disabled"
                            wire:click.prevent="sendEmailVerification">
                            {{ __('Verify email') }}</x-button>
                    </div>

                    @if ($this->verificationLinkSent)
                        <p v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                @endif
            </div>

            <!-- Document -->
            <div class="w-full">
                <x-label for="name" value="{{ __('Document') }}" />
                @if (empty($state['document']))
                    <x-input id="document" type="text" class="block w-full" wire:model.defer="state.document"
                        autocomplete="document" name="document" />
                @else
                    <x-disabled-text :text="$state['document']" />
                @endif
                <x-jet-input-error for="document" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="w-full">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" type="text" class="block w-full" wire:model.defer="state.name"
                    autocomplete="name" name="name" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}</x-jet-action-message>

        <x-button type="submit" wire:loading.attr="disabled">
            {{ __('Save') }}</x-button>
    </x-slot>
</x-jet-form-section>

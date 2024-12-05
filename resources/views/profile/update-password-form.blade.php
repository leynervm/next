<x-jet-form-section submit="updatePassword">
    <x-slot name="title">{{ __('Update Password') }}</x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-2">
            <div class="w-full">
                <x-label for="current_password" value="{{ __('Current Password') }}" />
                <x-input id="current_password" type="password" class="block w-full"
                    wire:model.defer="state.current_password" autocomplete="current-password" />
                <x-jet-input-error for="current_password" />
            </div>

            <div class="w-full">
                <x-label for="password" value="{{ __('New Password') }}" />
                <x-input id="password" type="password" class="block w-full" wire:model.defer="state.password"
                    autocomplete="new-password" />
                <x-jet-input-error for="password" />
            </div>

            <div class="w-full">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" type="password" class="block w-full"
                    wire:model.defer="state.password_confirmation" autocomplete="new-password" />
                <x-jet-input-error for="password_confirmation" />
            </div>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message on="saved">{{ __('Saved.') }}</x-jet-action-message>

        <x-button type="submit">{{ __('Save') }}</x-button>
    </x-slot>
</x-jet-form-section>

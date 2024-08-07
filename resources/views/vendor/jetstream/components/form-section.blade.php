@props(['submit'])

<div {{ $attributes->merge(['class' => 'w-full md:grid md:grid-cols-3 md:gap-6']) }}>
    <x-jet-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-jet-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit.prevent="{{ $submit }}" class="rounded-md bg-fondominicard sm:rounded-xl">
            <div class="p-3">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end p-3 text-right">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>

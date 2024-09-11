@props(['submit'])

<x-form-card :titulo="$title" :subtitulo="$description" classtitulo="!text-lg">
    <form wire:submit.prevent="{{ $submit }}" class="w-full flex flex-col gap-2 rounded-md sm:rounded-xl">

        {{ $form }}

        @if (isset($actions))
            <div class="w-full flex items-center justify-end gap-2">
                {{ $actions }}
            </div>
        @endif
    </form>
</x-form-card>

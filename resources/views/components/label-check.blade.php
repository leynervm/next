@props(['textSize' => '[10px]'])

<x-label textSize="{{ $textSize }}"
    {{ $attributes->merge(['class' => 'label-checkbox inline-flex leading-3 gap-2 items-center tracking-widest font-semibold gap-1 cursor-pointer text-textspancardproduct bg-fondospancardproduct rounded-md p-1']) }}>
    {{ $slot }}
</x-label>

@props(['textSize' => '[10px]'])

<x-label textSize="{{ $textSize }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center tracking-widest font-semibold gap-1 cursor-pointer text-textspancardproduct bg-fondospancardproduct rounded-md p-1']) }}>
    {{ $slot }}
</x-label>

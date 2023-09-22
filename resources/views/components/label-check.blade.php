<x-label textSize="[10px]"
    {{ $attributes->merge(['class' => 'inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer text-textspancardproduct bg-fondospancardproduct rounded-lg p-1']) }}>
    {{ $slot }}
</x-label>

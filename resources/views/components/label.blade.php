<label {{ $attributes->merge(['class' => $textSize . ' tracking-wider block text-next-500']) }}>
    {{ $value ?? $slot }}
</label>

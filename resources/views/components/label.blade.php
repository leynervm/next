<label {{ $attributes->merge(['class' => $textSize . ' tracking-wider block text-colorlabel']) }}>
    {{ $value ?? $slot }}
</label>

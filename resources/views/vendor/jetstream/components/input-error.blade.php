@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-[10px] md:text-xs font-semibold text-red-600']) }}>{{ $message }}</p>
@enderror

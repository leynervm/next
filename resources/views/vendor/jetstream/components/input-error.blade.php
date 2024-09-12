@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-[10px] md:text-xs font-semibold text-colorerror']) }}>{{ $message }}</p>
@enderror

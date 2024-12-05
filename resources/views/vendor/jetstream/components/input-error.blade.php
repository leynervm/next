@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-[10px] md:text-[11px] font-semibold text-colorerror leading-tight']) }}>{{ $message }}</p>
@enderror

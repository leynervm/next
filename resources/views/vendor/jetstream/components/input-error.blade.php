@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-[10px] md:text-xs font-medium text-colorerror']) }}>{{ $message }}</p>
@enderror

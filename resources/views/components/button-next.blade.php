<button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
    <span class="block mx-auto {{ $classesIcon }}">
        {{ $slot }}
    </span>

    @if (isset($titulo))
        <p class="leading-3 text-center mt-1 {{ $classTitulo }}">
            {{ $titulo }}
        </p>
    @endif
</button>

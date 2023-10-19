<x-app-layout>
    <div
        class="mx-auto flex flex-col gap-8 lg:max-w-4xl xl:max-w-7xl py-10 lg:px-10 animate__animated animate__fadeIn animate__faster">

        <div>
            <livewire:almacen::compras.show-compra :compra="$compra" :empresa="$empresa" :opencaja="$opencaja"/>
        </div>

        <div>
            <livewire:almacen::compras.show-resumen-compra :compra="$compra" :empresa="$empresa"
                wire:key="show-resumen-compra{{ $compra->id }}" />
        </div>
    </div>
</x-app-layout>

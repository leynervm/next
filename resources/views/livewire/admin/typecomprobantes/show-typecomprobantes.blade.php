<div>
    @if (count($typecomprobantes))
        <div class="pb-2">
            {{ $typecomprobantes->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <x-table class="mt-1">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium text-left">
                    DESCRIPCIÓN</th>
                <th scope="col" class="p-2 font-medium">
                    CÓDIGO DOCUMENTO</th>
                <th scope="col" class="p-2 font-medium text-center">
                    SERIES EMISIÓN</th>
                <th scope="col" class="p-2 font-medium">
                    EMITIBLE SUNAT</th>
            </tr>
        </x-slot>
        @if (count($typecomprobantes))
            <x-slot name="body">
                @foreach ($typecomprobantes as $item)
                    <tr>
                        <td class="p-2">
                            <p> {{ $item->name }}</p>
                            <p class="text-[10px] text-colorsubtitleform"> {{ $item->descripcion }}</p>
                        </td>

                        <td class="p-2 text-center">
                            {{ $item->code }}
                        </td>

                        <td class="p-2 text-center">
                            @if (count($item->seriecomprobantes))
                                <div class="flex flex-wrap justify-center items-center gap-2">
                                    @foreach ($item->seriecomprobantes as $ser)
                                        <div class="inline-flex items-center justify-center gap-1">
                                            <x-span-text :text="$ser->serie" class="leading-3 !tracking-normal" />
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td class="p-2 text-center">
                            @if ($item->sendsunat)
                                <x-span-text text="EMITIBLE SUNAT" class="leading-3 !tracking-normal" type="green" />
                            @else
                                <x-span-text text="LOCAL" class="leading-3 !tracking-normal" />
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>
</div>

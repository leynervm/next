<div>
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo almacén') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-1">
                <div class="w-full">
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre almacén..." />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full">
                    <x-label value="Sucursal :" />
                    <x-select class="block w-full" wire:model.defer="sucursal_id" id="sucursalalmacen_id"
                        data-dropdown-parent="null">
                        <x-slot name="options">
                            @if (count($sucursales))
                                @foreach ($sucursales as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }} - {{ $item->direccion }}

                                        @if ($item->default)
                                            (PRINCIPAL)
                                        @endif
                                    </option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="sucursal_id" />
                </div>

                <div class="block">
                    <x-label-check for="default">
                        <x-input wire:model.defer="default" value="1" type="checkbox" id="default" />
                        DEFINIR COMO PREDETERMINADO
                    </x-label-check>
                    <x-jet-input-error for="default" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    @section('scripts')
        <script>
            $('#sucursalalmacen_id').select2().on("change", function(e) {
                $('#sucursalalmacen_id').attr("disabled", true);
                @this.sucursal_id = e.target.value;
            });

            document.addEventListener('render-create-almacen', () => {
                $('#sucursalalmacen_id').select2();
            });
        </script>
    @endsection
</div>

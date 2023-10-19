<div>
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Ofertar producto') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="block w-full" id="form_create_oferta">
                @if ($producto)
                    <div class="w-60 mx-auto">
                        @if (count($producto->images))
                            <div class="w-full h-32 rounded shadow border">
                                @if ($producto->defaultImage)
                                    <img src="{{ asset('storage/productos/' . $producto->defaultImage->first()->url) }}"
                                        alt="" class="w-full h-full object-scale-down">
                                @else
                                    <img src="{{ asset('storage/productos/' . $producto->images->first()->url) }}"
                                        alt="" class="w-full h-full object-scale-down">
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <x-label value="Almacén :" />
                <x-select wire:model.defer="almacen_id" class="block w-full" id="almacen_id" data-dropdown-parent="">
                    @if (count($almacens))
                        <x-slot name="options">
                            @foreach ($almacens as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    @endif
                </x-select>
                <x-jet-input-error for="almacen_id" />

                <x-label value="Producto :" class="mt-2" />
                <x-select wire:model.defer="producto_id" class="block w-full" id="producto_id" data-dropdown-parent=""
                    data-minimum-results-for-search="3">
                    @if (count($productos))
                        <x-slot name="options">
                            @foreach ($productos as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    @endif
                </x-select>
                <x-jet-input-error for="producto_id" />

                <div class="flex flex-wrap md:flex-nowrap gap-2 mt-2">
                    <div class="w-full md:w-1/2">
                        <x-label value="Fecha inicio :" />
                        <x-input class="block w-full" wire:model.defer="datestart" type="date" />
                        <x-jet-input-error for="datestart" />
                    </div>
                    <div class="w-full md:w-1/2">
                        <x-label value="Fecha finalización :" />
                        <x-input class="block w-full" wire:model.defer="dateexpire" type="date" />
                        <x-jet-input-error for="dateexpire" />
                    </div>
                </div>

                <div class="flex flex-wrap md:flex-nowrap gap-2 mt-2">
                    <div class="w-full md:w-1/2">
                        <x-label value="Máximo stock :" class="mt-2" />
                        <x-input class="block w-full" wire:model.defer="limit" type="number" min="0"
                            step="1" :disabled="$max == 1 ? true : false" />
                        <x-jet-input-error for="limit" />
                    </div>
                    <div class="w-full md:w-1/2">
                        <x-label value="Descuento (%) :" class="mt-2" />
                        <x-input class="block w-full" wire:model.defer="descuento" type="number" min="0"
                            step="0.1" />
                        <x-jet-input-error for="descuento" />
                    </div>
                </div>

                <div class="mt-3 mb-1">
                    <x-label textSize="[10px]"
                        class="inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                        for="max">
                        <x-input wire:model="max" name="max" type="checkbox" id="max" />
                        SELECCIONAR MÁXIMO DISPONIBLE
                    </x-label>
                </div>
                <x-jet-input-error for="max" />


                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="save">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener("livewire:load", () => {

            renderSelect2();

            $("#almacen_id").on("change", (e) => {
                deshabilitarSelects();
                @this.almacen_id = e.target.value;
            });

            $("#producto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.producto_id = e.target.value;
            });

            window.addEventListener('render-oferta-select2', () => {
                renderSelect2();
            });

            function renderSelect2() {
                var formulario = document.getElementById("form_create_oferta");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    if (selects[i].id !== "") {
                        $("#" + selects[i].id).select2({
                            placeholder: "Seleccionar...",
                        });
                    }
                }
            }

            function deshabilitarSelects() {
                var formulario = document.getElementById("form_create_oferta");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    selects[i].disabled = true;
                }
            }


            window.addEventListener("producto.confirmDelete", data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.detail.name,
                    text: "Se eliminará un registro de la base de datos, incluyendo todos los datos relacionados.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(data.detail);
                        Livewire.emitTo('almacen::productos.view-producto', 'delete', data
                            .detail.id);
                    }
                })
            });

        })
    </script>

</div>

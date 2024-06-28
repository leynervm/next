<div>
    <div class="loading-overlay fixed hidden" wire:loading.flex>
        <x-loading-next />
    </div>

    <x-form-card titulo="ALMACENES">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3">
            @can('admin.administracion.sucursales.almacenes.edit')
                @if (Module::isEnabled('Almacen') || Module::isEnabled('Ventas'))
                    @if (count($almacens) > 0)
                        <div class="w-full lg:w-80 xl:w-96 lg:flex-shrink-0 relative" x-data="{ savingalmacen: false }">
                            <form wire:submit.prevent="save" class="flex flex-col gap-2">
                                <div class="w-full">
                                    <x-label value="Almacén :" />
                                    <div id="parentalmacen_id" class="relative" x-data="{ almacen_id: @entangle('almacen_id').defer }"
                                        x-init="select2Almacen">
                                        <x-select class="block w-full" id="almacen_id" x-ref="searcha"
                                            wire:model.defer="almacen_id">
                                            <x-slot name="options">

                                                @foreach ($almacens as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach

                                            </x-slot>
                                        </x-select>
                                        <x-icon-select />
                                    </div>
                                    <x-jet-input-error for="almacen_id" />
                                </div>

                                <div class="w-full flex pt-4 justify-end">
                                    <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                                        {{ __('REGISTRAR') }}
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endif
            @endcan

            <div class="w-full flex-1">
                @if (count($sucursal->almacens) > 0)
                    <div class="w-full flex flex-wrap gap-2">
                        @foreach ($sucursal->almacens as $item)
                            <x-minicard :title="null" size="lg"
                                alignFooter="{{ $item->default == '1' ? 'justify-between' : 'justify-end' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 inline-block mx-auto"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M13 22C12.1818 22 11.4002 21.6588 9.83691 20.9764C8.01233 20.18 6.61554 19.5703 5.64648 19H2M13 22C13.8182 22 14.5998 21.6588 16.1631 20.9764C20.0544 19.2779 22 18.4286 22 17V6.5M13 22L13 11M4 6.5L4 9.5" />
                                    <path
                                        d="M9.32592 9.69138L6.40472 8.27785C4.80157 7.5021 4 7.11423 4 6.5C4 5.88577 4.80157 5.4979 6.40472 4.72215L9.32592 3.30862C11.1288 2.43621 12.0303 2 13 2C13.9697 2 14.8712 2.4362 16.6741 3.30862L19.5953 4.72215C21.1984 5.4979 22 5.88577 22 6.5C22 7.11423 21.1984 7.5021 19.5953 8.27785L16.6741 9.69138C14.8712 10.5638 13.9697 11 13 11C12.0303 11 11.1288 10.5638 9.32592 9.69138Z" />
                                    <path d="M18.1366 4.01563L7.86719 8.98485" />
                                    <path d="M2 13H5" />
                                    <path d="M2 16H5" />
                                </svg>

                                <span class="text-[10px] text-center font-semibold">{{ $item->name }}</span>

                                @can('admin.administracion.sucursales.almacenes.edit')
                                    <x-slot name="buttons">
                                        @if ($item->default)
                                            <x-icon-default />
                                        @endif
                                        @if (Module::isEnabled('Almacen') || Module::isEnabled('Ventas'))
                                            <x-button-delete onclick="confirmDeleteAlmacen({{ $item }})"
                                                wire:loading.attr="disabled" wire:key="desvincular_{{ $item->id }}" />
                                        @endif
                                    </x-slot>
                                @endcan
                            </x-minicard>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-form-card>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar almacén') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Nombre almacén :" />
                    <x-input class="block w-full" wire:model.defer="almacen.name" placeholder="Nombre de almacén..." />
                    <x-jet-input-error for="almacen.name" />
                </div>
                <div class="w-full">
                    <x-label-check for="editdefaultalmacen">
                        <x-input wire:model.defer="almacen.default" value="1" type="checkbox"
                            id="editdefaultalmacen" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label-check>
                    <x-jet-input-error for="almacen.default" />
                    <x-jet-input-error for="sucursal.id" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function select2Almacen() {
            this.selectAL = $(this.$refs.searcha).select2();
            this.selectAL.val(this.almacen_id).trigger("change");
            this.selectAL.on("select2:select", (event) => {
                this.almacen_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("almacen_id", (value) => {
                this.selectAL.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectAL.select2().val(this.almacen_id).trigger('change');
            });
        }

        function confirmDeleteAlmacen(almacen) {
            swal.fire({
                title: 'Desvincular registro de ' + almacen.name + ' de la sucursal ?',
                text: "Almacén seleccionado dejará de estar disponible en sucursal, y el stock de los productos vinculados se eliminarán.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(almacen.id);
                }
            })
        }
    </script>
</div>

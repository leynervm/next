<div x-data="loaderpricetypes">
    @if ($pricetypes->hasPages())
        <div class="w-full py-2">
            {{ $pricetypes->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="grid grid-cols-[repeat(auto-fill,minmax(110px,1fr))] gap-1 self-start mt-2">
        @if (count($pricetypes) > 0)
            @foreach ($pricetypes as $item)
                <div
                    class="border border-borderminicard p-1 min-h-28 flex flex-col gap-2 justify-between rounded-lg sm:rounded-2xl">
                    <div class="w-full flex-1 h-full py-1 flex flex-col gap-1 justify-center items-center">
                        <h1 class="text-xs font-medium text-colorsubtitleform text-center leading-none">
                            {{ $item->name }}</h1>
                        @if ($item->rounded > 0)
                            @php
                                $stringrounded = $item->rounded == 1 ? '(+0.5)' : '(+1)';
                            @endphp
                            <x-span-text :text="'REDONDEAR ' . $stringrounded" type="green" class="leading-none inline-block" />
                        @endif

                        <x-span-text :text="$item->decimals . ' DECIMALES'" class="leading-3 inline-block" />
                    </div>

                    <div class="w-full flex gap-1 justify-between items-end">
                        <div class="flex items-end">
                            @if ($item->isDefault())
                                <span class="p-1">
                                    <x-icon-default class="flex-shrink-0" />
                                </span>
                            @endif
                            @if ($item->isDefaultLogin())
                                <span class="text-next-500 block p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 scale-110"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="2" x2="22" y1="12" y2="12" />
                                        <path
                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                    </svg>
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-1 gap-1 items-end justify-end">
                            @can('admin.administracion.pricetypes.edit')
                                @if ($item->isActivo())
                                    <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})"
                                        @click="temporal = false" />
                                @endif
                            @endcan

                            @can('admin.administracion.pricetypes.delete')
                                <x-button-toggle :checked="$item->isActivo()" wire:loading.attr="disabled"
                                    wire:key="restorepricetype_{{ $item->id }}"
                                    x-on:click="confirmDisable({{ $item }})" />
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="2xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar lista precio') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Lista precio :" />
                    <x-input class="block w-full" wire:model.defer="pricetype.name"
                        placeholder="Nombre de lista precio..." />
                    <x-jet-input-error for="pricetype.name" />
                </div>

                {{-- <div class="w-full sm:w-1/2">
                        <x-label value="Porcentaje ganancia (%) :" />
                        <x-input class="block w-full" wire:model.defer="pricetype.ganancia" type="number"
                            step="0.1" min="0" />
                        <x-jet-input-error for="pricetype.ganancia" />
                    </div> --}}

                <div class="w-full grid xs:grid-cols-2 gap-2">
                    <div class="w-full">
                        <x-label value="Cantidad decimales :" />
                        <x-input class="block w-full input-number-none" wire:model.defer="pricetype.decimals"
                            type="number" step="1" min="0" max="4"
                            onkeypress="return validarDecimal(event, 1)" />
                        <x-jet-input-error for="pricetype.decimals" />
                    </div>

                    <div class="w-full">
                        <x-label value="Redondeo decimales :" />
                        <div id="parentroundededit" class="relative" x-init="select2Rounded">
                            <x-select class="block w-full" id="roundededit" x-ref="selectrounded">
                                <x-slot name="options">
                                    <option value="0">NO USAR REDONDEO</option>
                                    <option value="1">REDONDEAR DECIMAL (+0.5)</option>
                                    <option value="2">REDONDEAR DECIMAL A ENTERO (+1)</option>
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="pricetype.rounded" />
                    </div>
                </div>

                <div class="">
                    <x-label-check for="default_edit">
                        <x-input wire:model.defer="pricetype.default" name="default" value="1" type="checkbox"
                            id="default_edit" />
                        PREDETERMINADO PARA TIENDA FÍSICA
                    </x-label-check>
                    <x-jet-input-error for="pricetype.default" />
                </div>

                {{-- <div class="">
                    <x-label-check for="web_edit">
                        <x-input wire:model.defer="pricetype.web" name="web" value="1" type="checkbox"
                            id="web_edit" />
                        PREDETERMINADO PARA CLIENTES DE TIENDA WEB SIN INICIAR SESIÓN
                    </x-label-check>
                    <x-jet-input-error for="pricetype.web" />
                </div> --}}

                <div class="">
                    <x-label-check for="edit_defaultlogin">
                        <x-input wire:model.defer="pricetype.defaultlogin" name="defaultlogin" value="1"
                            type="checkbox" id="edit_defaultlogin" />
                        PREDETERMINADO PARA TIENDA WEB
                    </x-label-check>
                    <x-jet-input-error for="pricetype.defaultlogin" />
                </div>

                <div class="block">
                    <x-label-check for="edit_temporal">
                        <x-input x-model="temporal" name="temporal" type="checkbox" id="edit_temporal" />
                        USAR DE MANERA TEMPORAL PARA TIENDA WEB
                    </x-label-check>
                    <x-jet-input-error for="pricetype.temporal" />

                    <div x-show="temporal" class="relative mt-2" x-transition>
                        <div class="w-full grid grid-cols-2 gap-2">
                            <div class="w-full">
                                <x-label value="Fecha inicio :" />
                                <x-input class="w-full" type="date" wire:model.defer="pricetype.startdate" />
                                <x-jet-input-error for="pricetype.startdate" />
                            </div>
                            <div class="w-full">
                                <x-label value="Fecha fin :" />
                                <x-input class="w-full" type="date" wire:model.defer="pricetype.expiredate" />
                                <x-jet-input-error for="pricetype.expiredate" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loaderpricetypes', () => ({
                rounded: @entangle('pricetype.rounded').defer,
                temporal: @entangle('pricetype.temporal').defer,
                confirmDisable(pricetype) {
                    let estado_activo = "{{ \App\Models\Pricetype::ACTIVO }}";
                    let mensaje = pricetype.status == estado_activo ? 'DESACTIVAR' : 'ACTIVAR';
                    swal.fire({
                        title: mensaje + " LISTA DE PRECIO SELECCIONADO \n" + pricetype.name,
                        text: null,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0FB9B9',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.togglestatus(pricetype.id);
                        }
                    })
                }
            }));
        })

        function select2Rounded() {
            this.selectRP = $(this.$refs.selectrounded).select2();
            this.selectRP.val(this.rounded).trigger("change");
            this.selectRP.on("select2:select", (event) => {
                this.rounded = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("rounded", (value) => {
                this.selectRP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectRP.select2().val(this.rounded).trigger('change');
            });
        }
    </script>
</div>

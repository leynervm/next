<div class="">

    @if ($pricetypes->hasPages())
        <div class="w-full py-2">
            {{ $pricetypes->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($pricetypes) > 0)
            @foreach ($pricetypes as $item)
                <x-minicard :title="$item->name" :content="$item->decimals . ' decimales'" :alignFooter="$item->default == 1 || $item->web == 1 ? 'justify-between' : 'justify-end'" size="lg">
                    @if ($item->rounded > 0)
                        @php
                            $stringrounded = $item->rounded == 1 ? '(+0.5)' : '(+1)';
                        @endphp
                        <p class="text-center">
                            <x-span-text :text="'REDONDEAR ' . $stringrounded" type="green"
                                class="leading-3 !tracking-normal inline-block" />
                        </p>
                    @endif

                    <x-slot name="buttons">
                        <div class="inline-flex">
                            @if ($item->default)
                                <x-icon-default />
                            @endif
                            @if ($item->web)
                                <span class="text-next-500 @if ($item->default) absolute left-6 @endif">
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

                        <div class="">
                            @can('admin.administracion.pricetypes.edit')
                                <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                            @endcan

                            @can('admin.administracion.pricetypes.delete')
                                <x-button-delete wire:loading.attr="disabled"
                                    onclick="confirmDelete({{ $item }})" />
                            @endcan
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="2xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar lista precio') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2" x-data="loaderpricetypes">
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
                <div class="w-full">
                    <x-label value="Cantidad decimales :" />
                    <x-input class="block w-full" wire:model.defer="pricetype.decimals" type="number" step="1"
                        min="0" max="4" onkeypress="return validarDecimal(event, 1)" />
                    <x-jet-input-error for="pricetype.decimals" />
                </div>

                <div class="w-full">
                    <x-label value="Redondeo decimales :" />
                    <div id="parentroundededit" class="relative" wire:ignore>
                        <x-select class="block w-full" id="roundededit" x-ref="select">
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

                <div class="">
                    <x-label-check for="default_edit">
                        <x-input wire:model.defer="pricetype.default" name="default" value="1" type="checkbox"
                            id="default_edit" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label-check>
                    <x-jet-input-error for="pricetype.default" />
                </div>

                <div class="">
                    <x-label-check for="web_edit">
                        <x-input wire:model.defer="pricetype.web" name="web" value="1" type="checkbox"
                            id="web_edit" />
                        PREDETERMINADO PARA VENTAS WEB
                    </x-label-check>
                    <x-jet-input-error for="pricetype.web" />
                </div>

                <div class="">
                    <x-label-check for="edit_defaultlogin">
                        <x-input wire:model.defer="pricetype.defaultlogin" name="defaultlogin" value="1"
                            type="checkbox" id="edit_defaultlogin" />
                        PREDETERMINADO PARA VENTAS WEB DESPUES LOGIN
                    </x-label-check>
                    <x-jet-input-error for="pricetype.defaultlogin" />
                </div>

                <div class="block">
                    <x-label-check for="edit_temporal">
                        <x-input wire:model.defer="pricetype.temporal" @change="changeTemporal($event.target)"
                            name="temporal" type="checkbox" id="edit_temporal" />
                        USAR DE MANERA TEMPORAL PARA VENTAS POR INTERNET
                    </x-label-check>
                    <x-jet-input-error for="pricetype.temporal" />

                    <div x-show="show" class="relative mt-2">
                        <div class="w-full flex gap-2 animate__animated animate__fadeInDown">
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
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        function confirmDelete(pricetype) {
            swal.fire({
                title: 'Eliminar lista de precio, ' + pricetype.name,
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(pricetype.id);
                }
            })
        }


        document.addEventListener('alpine:init', () => {
            Alpine.data('loaderpricetypes', () => ({
                rounded: @entangle('pricetype.rounded').defer,
                show: @entangle('pricetype.temporal').defer,

                changeTemporal(target) {
                    this.show = !this.show;
                },
                init() {
                    this.select2Rounded();
                    // this.show = @this.get('pricetype.temporal');
                },
                select2Rounded() {
                    this.selectR = $(this.$refs.select).select2();
                    this.selectR.val(this.rounded).trigger("change");
                    this.selectR.on("select2:select", (event) => {
                        this.rounded = event.target.value;
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                    this.$watch("rounded", (value) => {
                        this.selectR.val(value).trigger("change");
                    });
                },
            }));
        })
    </script>
</div>

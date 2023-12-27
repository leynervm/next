<div>
    @if ($cajas->hasPages())
        <div class="pb-2">
            {{ $cajas->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    @if (count($cajas))
        <div class="w-full relative mt-1" x-data="{ loading: false }" x-init="loadingData">
            @if (count($sucursals) > 1)
                <div class="w-full max-w-xs mb-2">
                    <x-label for="sucursal_id" value="{{ __('Filtrar Sucursal') }} :" />
                    <div x-data="{ searchsucursal: @entangle('searchsucursal') }" x-init="select2SucursalAlpine" id="parentsearchsucursal" wire:ignore>
                        <x-select id="searchsucursal" x-ref="select" data-placeholder="null">
                            <x-slot name="options">
                                @if (count($sucursals))
                                    @foreach ($sucursals as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                    </div>
                </div>
            @endif

            <div class="flex gap-3 flex-wrap justify-start">
                @foreach ($cajas as $item)
                    <x-minicard :title="$item->name" size="lg">

                        <div class="text-center">
                            <p
                                class="bg-fondospancardproduct text-textspancardproduct inline-flex items-center gap-1 text-[10px] p-1 leading-3 rounded">
                                {{ $item->sucursal->name }}
                            </p>
                        </div>

                        <x-slot name="buttons">
                            <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                                wire:click="edit({{ $item->id }})" />
                            {{-- <x-button-delete wire:loading.attr="disabled" wire:target="delete"
                            wire:click="$emit('cajas.confirmDelete', {{ $item }})" /> --}}
                        </x-slot>
                    </x-minicard>
                @endforeach
            </div>
            <div x-show="loading" wire:loading wire:loading.flex class="loading-overlay rounded">
                <x-loading-next />
            </div>
        </div>
    @endif


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar caja') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="Sucursal :" />
                    <x-disabled-text :text="$caja->sucursal->name ?? null" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Nombre caja :" />
                    <x-input class="block w-full" wire:model.defer="caja.name" placeholder="Nombre de caja..." />
                    <x-jet-input-error for="caja.name" />
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
        function loadingData() {
            Livewire.hook('message.sent', () => {
                loading = true;
            });

            Livewire.hook('message.processed', () => {
                loading = false;
            });
        }

        function select2SucursalAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.searchsucursal).trigger("change");
            this.select2.on("select2:select", (event) => {
                    this.searchsucursal = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch("searchsucursal", (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('cajas.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre ' + data.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.cajas.show-cajas', 'delete', data.detail
                            .id);
                    }
                })
            })
        })
    </script>
</div>

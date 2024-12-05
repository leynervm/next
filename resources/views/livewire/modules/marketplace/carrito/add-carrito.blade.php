<div class="w-full flex items-center gap-1 justify-end mt-1 sm:mt-5" x-data="addtocar">
    <div class="flex-1 w-full">
        <button type="button" @click="qty = qty-1" x-bind:disabled="qty == 1"
            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 transition ease-in-out duration-150">-</button>
        <span x-text="qty" class="font-medium text-sm px-2 text-colorlabel inline-block w-8 text-center"></span>
        <button type="button" wire:loading.attr="disabled" @click="qty = qty+1"
            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 transition ease-in-out duration-150">+</button>
    </div>
    <x-button-add-car type="button" wire:loading.attr="disabled"
        class="!rounded-xl !py-1.5 px-2.5 !flex !gap-1 items-center text-xs" @click="add_to_cart({{ $producto->id }})"
        classIcon="!w-6 !h-6">AGREGAR</x-button-add-car>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('addtocar', () => ({
                qty: 1,

                add_to_cart(producto_id) {
                    this.$wire.add_to_cart(producto_id, this.qty).then(result => {})
                }
            }))
        })
    </script>
</div>

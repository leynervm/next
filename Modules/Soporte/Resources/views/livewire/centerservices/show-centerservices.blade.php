<div>

    @if (count($centerservices))
        <div class="pb-2">
            {{ $centerservices->links() }}
        </div>

        <div class="block w-full overflow-x-auto rounded-lg border border-gray-200 shadow-md">
            <table class="w-full border-collapse bg-white text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 font-semibold">
                    <tr>
                        <th scope="col" class="px-3 py-2 flex gap-3">
                            RAZÓN SOCIAL
                            <button class="flex items-center gap-x-2">
                                <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path
                                        d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path
                                        d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                </svg>
                            </button>
                        </th>
                        <th scope="col" class="px-3 py-2">DIRECCIÓN</th>
                        <th scope="col" class="px-3 py-2">MARCA</th>
                        <th scope="col" class="px-3 py-2 text-center">CONDICION ATENCIÓN</th>
                        <th scope="col" class="px-3 py-2">TELEFÓNOS</th>
                        <th scope="col" class="px-3 py-2 text-center">ATENCIONES</th>
                        <th scope="col" class="px-3 py-2 text-center">OPCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">

                    @foreach ($centerservices as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 min-w-[220px]">
                                <a href="#" class="hover:text-blue-600 transition-all ease-out duration-150">{{ $item->document }} - {{ $item->name }}</a>
                                <p class="text-gray-400">jobs@sailboatui.com</p>
                            </td>
                            <td class="px-3 py-2 min-w-[220px]"> {{ $item->direccion }} </td>
                            <td class="px-3 py-2">{{ $item->marca->name }}</td>
                            <td class="px-3 py-2 text-center min-w-[150px]">{{ $item->condition->name }}</td>
                            <td class="px-3 py-2">
                                @if (count($item->telephones))
                                    <div class="flex gap-2">
                                        @foreach ($item->telephones as $phone)
                                            <span
                                                class="inline-flex text-green-500 bg-green-100 items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                                </svg>
                                                <span class="text-[10px]">
                                                    {{ $phone->phone }}
                                                </span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td  class="px-3 py-2 text-center">
                                <x-button class="text-center mx-auto">ATENCIONES</x-button>
                            </td>
                            <td class="px-3 py-2 text-center">
                                <x-button-edit></x-button-edit>
                                <x-button-delete></x-button-delete>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    @endif

    {{-- <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
            <x-slot name="title">
                {{ __('Actualizar atención') }}
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
                    <x-label value="Atención :" />
                    <x-input class="block w-full" wire:model.defer="atencion.name"
                        placeholder="Ingrese nombre de atención..." />
                    <x-jet-input-error for="atencion.name" />

                    <x-label class="mt-2 flex items-center gap-1 cursor-pointer" for="equipamentrequire_edit">
                        <x-input wire:model.defer="atencion.equipamentrequire" type="checkbox"
                            id="equipamentrequire_edit" value="1" />
                        Requiere ingresar equipamiento.
                    </x-label>

                    <x-label class="mt-6" value="Areas responsables" />
                    @if (count($areas))
                        <div class="flex flex-wrap gap-1">
                            @foreach ($areas as $item)
                                <x-label class="flex items-center gap-1 cursor-pointer bg-next-100 rounded-lg p-1"
                                    for="area_edit_{{ $item->id }}">
                                    <x-input wire:model.defer="selectedAreas" name="areas[]" type="checkbox"
                                        id="area_edit_{{ $item->id }}" value="{{ $item->id }}" />
                                    {{ $item->name }}
                                </x-label>
                            @endforeach
                        </div>
                    @endif
                    <x-jet-input-error for="selectedAreas" />

                    <x-label class="mt-6" value="Entornos atención" />
                    @if (count($entornos))
                        <div class="flex flex-wrap gap-1">
                            @foreach ($entornos as $item)
                                <x-label class="flex items-center gap-1 cursor-pointer bg-next-100 rounded-lg p-1"
                                    for="entorno_edit_{{ $item->id }}">
                                    <x-input wire:model.defer="selectedEntornos" name="entornos[]" type="checkbox"
                                        id="entorno_edit_{{ $item->id }}" value="{{ $item->id }}" />
                                    {{ $item->name }}
                                </x-label>
                            @endforeach
                        </div>
                    @endif
                    <x-jet-input-error for="selectedEntornos" />

                    <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                        <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                            wire:target="update">
                            {{ __('ACTUALIZAR') }}
                        </x-button>
                    </div>
                </form>
            </x-slot>
        </x-jet-dialog-modal> --}}

    <script>
        document.addEventListener('livewire:load', function() {

            var toastMixin = Swal.mixin({
                toast: true,
                icon: "success",
                title: "Mensaje",
                position: "top-right",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });

            window.addEventListener('soporte::atenciones.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.detail.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(data.detail.id);
                        Livewire.emit('deleteAtencion', data.detail.id);
                    }
                })
            })

            window.addEventListener('soporte::atenciones.deleted', event => {
                toastMixin.fire({
                    title: 'Eliminado correctamente'
                });
            })
        })
    </script>

</div>

<div class="">

    @if (count($aperturas))
        <div class="pb-2">
            {{ $aperturas->links() }}
        </div>
    @endif

    <div class="block w-full">
        @if (count($aperturas))
            <x-table>
                <thead class="bg-gray-50 text-gray-400 text-xs">
                    <tr>
                        <th scope="col" class="p-2 font-medium text-left">CAJA</th>
                        <th scope="col" class="p-2 font-medium text-center">FECHA APERTURA</th>
                        <th scope="col" class="p-2 font-medium text-center">FECHA CIERRE</th>
                        <th scope="col" class="p-2 font-medium">MONTO APERTURA</th>
                        <th scope="col" class="p-2 font-medium">MONTO CIERRE (EFECTIVO)</th>
                        <th scope="col" class="p-2 font-medium">MONTO CIERRE (TRANSFERENCIAS)</th>
                        <th scope="col" class="p-2 font-medium">USUARIO</th>
                        <th scope="col" class="p-2 font-medium">ESTADO</th>
                        <th scope="col" class="p-2 font-medium">CERRAR CAJA</th>
                        <th scope="col" class="p-2 font-medium">OPCIONES</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                    @if (count($aperturas))
                        @foreach ($aperturas as $item)
                            <tr>
                                <td class="p-2 text-xs">
                                    {{ $item->caja->name }}
                                </td>
                                <td class="p-2 text-center text-xs uppercase">
                                    {{ \Carbon\Carbon::parse($item->startdate)->locale('es')->isoformat('DD MMMM YYYY hh:mm:ss A') }}
                                </td>
                                <td class="p-2 text-center text-xs uppercase">
                                    @if ($item->expiredate)
                                        {{ \Carbon\Carbon::parse($item->expiredate)->locale('es')->isoformat('DD MMMM YYYY hh:mm:ss A') }}
                                    @endif
                                </td>
                                <td class="p-2 text-center text-xs">
                                    {{ $item->startmount }}
                                </td>
                                <td class="p-2 text-center text-xs">
                                    {{ $item->totalcash }}
                                </td>
                                <td class="p-2 text-center text-xs">
                                    {{ $item->totaltransfer }}
                                </td>
                                <td class="p-2 text-center text-xs">
                                    {{ $item->user->name }}
                                </td>
                                <td class="p-2 text-xs text-center">
                                    @if ($item->status == 0)
                                        <span
                                            class="bg-green-100 text-green-600 text-[10px] p-1 rounded-lg inline-flex leading-3">Activo</span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-600 text-[10px] p-1 rounded-lg inline-flex leading-3">Cerrado</span>
                                    @endif
                                </td>
                                <td class="p-2 text-xs text-center">
                                    @if ($item->status == 0)
                                        @if (Auth::user()->id == $item->user_id)
                                            <x-button>CERRAR CAJA</x-button>
                                        @endif
                                    @endif
                                </td>
                                <td class="p-2 text-center text-xs">
                                    <x-button-edit></x-button-edit>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </x-table>
        @endif
    </div>
</div>

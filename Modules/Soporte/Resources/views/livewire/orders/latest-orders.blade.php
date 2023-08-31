<div>
    @if (count($latestOrders))
        <div class="w-full flex flex-wrap gap-2 justify-between">
            @foreach ($latestOrders as $order)
                <div class="w-full p-2 sm:w-80 rounded-lg shadow bg-white text-xs hover:shadow-lg cursor-pointer">
                    <div class="w-full flex flex-wrap gap-1">
                        <span
                            class="inline-flex py-0.5 px-1 shadow bg-gray-200 rounded-lg font-semibold">OT-{{ $order->id }}</span>
                        <span class="inline-flex py-0.5 px-1 shadow rounded-lg font-semibold"
                            style="background: {{ $order->priority->color }}">PRIORIDAD:
                            {{ $order->priority->name }}</span>

                        <span class="inline-flex py-0.5 px-1 shadow bg-gray-200 rounded-lg">
                            {{ $order->atencion->name }}</span>

                        <span class="inline-flex py-0.5 px-1 shadow bg-gray-200 rounded-lg">
                            {{ $order->area->name }}</span>
                        <span class="inline-flex py-0.5 px-1 shadow bg-gray-200 rounded-lg">CONDICIÓN
                            :{{ $order->condition->name }}</span>
                        <span class="inline-flex py-0.5 px-1 shadow bg-gray-200 rounded-lg">ENTORNO:
                            {{ $order->entorno->name }}</span>
                    </div>

                    <h1 class="mt-3 font-semibold underline">Detalles atención</h1>
                    <p class="inline-block mt-1 py-0.5 px-1 shadow bg-gray-200 rounded-lg">{{ $order->detalle }}</p>

                    @if ($order->orderequipo)
                        <h1 class="mt-3 font-semibold underline">Especificaciones del equipo</h1>

                        <div class="w-full gap-1 flex flex-wrap justify-between items-end mt-1">
                            <span class="inline-flex py-0.5 px-1 shadow bg-gray-200 rounded-lg font-semibold">
                                {{ $order->orderequipo->equipo->name }}:
                                {{ $order->orderequipo->marca->name }}
                                {{ $order->orderequipo->modelo }}
                            </span>

                            @if ($order->orderequipo->marca->logo)
                                <div class="w-16 h-12 inline-flex">
                                    <img src="{{ asset('storage/marcas/' . $order->orderequipo->marca->logo) }}"
                                        alt="" class="w-full h-full object-scale-down">
                                </div>
                            @endif
                        </div>
                        <div class="w-full mt-1">
                            <span class="inline-flex py-0.5 px-1 shadow bg-gray-200 rounded-lg">SERIE:
                                {{ $order->orderequipo->serie }}</span>

                            <span class="inline-flex py-0.5 px-1 shadow bg-gray-200 rounded-lg">
                                ESTADO:
                                {{ $order->orderequipo->stateinicial == 1 ? 'OPERATIVO' : 'INOPERATIVO' }}</span>
                        </div>
                        <p class="mt-1 py-0.5 px-1 shadow bg-gray-200 rounded-lg">
                            {{ $order->orderequipo->descripcion }}</p>
                    @endif

                    <div class="w-full flex justify-between items-end mt-3">
                        <div class="w-16 h-16">
                            <img src="https://chart.googleapis.com/chart?cht=qr&chl=http%3A%2F%2Fwww.next.net.pe&chs=180x180&choe=UTF-8&chld=L|2"
                                alt="" class="w-100 h-100 object-scale-down">
                        </div>
                        <x-button-add class="px-2" wire:click="$set('openModalClient', true)">
                            <svg xmlns="http://www.w3.org/2000/svg"class="w-full h-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9" />
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                <rect width="12" height="8" x="6" y="14" />
                            </svg>
                        </x-button-add>
                    </div>

                </div>
            @endforeach
        </div>
    @endif


</div>

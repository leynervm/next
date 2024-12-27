<x-admin-layout>
    @can('admin.almacen.productos.agotados')
        @if (count($productos) > 0)
            <div class="w-full pb-20 ml-auto max-w-lg md:max-h-[calc(100vh-4rem)] overflow-y-auto">
                <div class="text-xs font-medium text-primary py-1">
                    PRODUCTOS AGOTADOS & PRÓXIMOS AGOTAR
                </div>
                @foreach ($productos as $item)
                    <div class="w-full flex p-1 text-[10px] border border-borderminicard gap-2">
                        @php
                            $image = $item->getImageURL();
                        @endphp
                        <div class="w-20 h-20 flex-shrink-0 overflow-hidden">
                            @if ($image)
                                <img class="w-full h-full object-scale-down object-center" src="{{ $image }}" />
                            @else
                                <x-icon-file-upload class="w-full h-full" type="unknown" />
                            @endif
                        </div>
                        <div class="w-full flex-1">
                            @can('admin.almacen.productos.edit')
                                <a href="{{ route('admin.almacen.productos.edit', $item) }}"
                                    class="font-medium inline-block !leading-none text-linktable hover:text-hoverlinktable">
                                    {{ $item->name }}</a>
                            @endcan
                            @cannot('admin.almacen.productos.edit')
                                <p class="font-medium text-colorlabel !leading-none">{{ $item->name }}</p>
                            @endcannot

                            <p class="font-semibold text-colorlabel">MIN. STOCK:
                                {{ decimalOrInteger($item->minstock) }}</p>
                            <div class="w-full flex flex-wrap gap-1 mt-1 text-colorsubtitleform">
                                @foreach ($item->almacens as $almacen)
                                    <div class="p-1 rounded ring-1 ring-colorerror">
                                        <h1 class="text-xs text-colorerror font-medium text-center">
                                            {{ decimalOrInteger($almacen->pivot->cantidad) }}
                                            {{ $item->unit->name }}
                                        </h1>
                                        <p class="text-[9px]">{{ $almacen->name }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div
                class="w-full md:pl-64 flex justify-center items-center sm:justify-end p-1 sm:pr-6 fixed bottom-0 right-0 bg-body">
                {{ $productos->onEachSide(0)->links('pagination::pagination-default') }}
            </div>
        @endif
    @endcan


    {{-- <div x-data="chartData()">
            <div class="w-full max-w-3xl">
                <canvas id="bar-chart-grouped" class="w-full h-auto"></canvas>
            </div>

            <script>
                // function chartData() {
                //     return {
                //         labels: [],
                //         datasets: [],
                //         chart: null,

                //         init() {
                //             this.initChart();
                //         },
                //         initChart() {
                //             this.chart = new Chart(document.getElementById("bar-chart-grouped"), {
                //                 type: 'bar',
                //                 data: {
                //                     labels: this.labels,
                //                     datasets: this.datasets
                //                 },
                //                 options: {
                //                     title: {
                //                         display: true,
                //                         text: 'RESUMEN DIARIO MOVIMIENTOS CAJA'
                //                     }
                //                 }
                //             });

                //             this.loadChartData();
                //         },

                //         loadChartData() {
                //             fetch('/charts/resumen-movimientos')
                //                 .then(response => response.json())
                //                 .then(data => {
                //                     // Asignar los datos al gráfico
                //                     this.labels = data.labels;
                //                     this.datasets = data.datasets;

                //                     // Actualizar el gráfico con los nuevos datos
                //                     this.chart.data.labels = this.labels;
                //                     this.chart.data.datasets = this.datasets;
                //                     this.chart.update();
                //                 })
                //                 .catch(error => console.error('Error al cargar los datos del gráfico:', error));
                //         }
                //     }
                // }
            </script> --}}
    </div>

    @section('scripts')
        <script>
            // new Chart(document.getElementById("bar-chart-grouped"), {
            //     type: 'bar',
            //     data: {
            //         labels: ["CAJA 01", "CAJA 02", "CAJA 02", "CAJA 01"],
            //         datasets: [{
            //             label: "SOLES",
            //             backgroundColor: "#3e95cd",
            //             data: [133, 221, 783, 2478]
            //         }, {
            //             label: "DÓLARES",
            //             backgroundColor: "#8e5ea2",
            //             data: [408, 547, 675, 734]
            //         }]
            //     },
            //     options: {
            //         title: {
            //             display: true,
            //             text: 'RESUMEN DIARIO MOVIMIENTOS CAJA'
            //         }
            //     }
            // });
        </script>
    @endsection

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
</x-admin-layout>

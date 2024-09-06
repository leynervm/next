<x-admin-layout>
    @if (mi_empresa())

        <div class="w-full max-w-md">
            <h1>{{ $chart->options['chart_title'] }}</h1>
            {!! $chart->renderHtml() !!}
        </div>

        {{-- <div class="w-full">
            <h1>{{ $chart1->options['chart_title'] }}</h1>
            {!! $chart1->renderHtml() !!}
        </div> --}}
        <div x-data="chartData()">
            <div class="w-full max-w-3xl">
                <canvas id="bar-chart-grouped" class="w-full h-auto"></canvas>
            </div>

            <script>
                function chartData() {
                    return {
                        labels: [],
                        datasets: [],
                        chart: null,

                        init() {
                            this.initChart();
                        },
                        initChart() {
                            this.chart = new Chart(document.getElementById("bar-chart-grouped"), {
                                type: 'bar',
                                data: {
                                    labels: this.labels,
                                    datasets: this.datasets
                                },
                                options: {
                                    title: {
                                        display: true,
                                        text: 'RESUMEN DIARIO MOVIMIENTOS CAJA'
                                    }
                                }
                            });

                            this.loadChartData();
                        },

                        loadChartData() {
                            fetch('/charts/resumen-movimientos')
                                .then(response => response.json())
                                .then(data => {
                                    // Asignar los datos al gráfico
                                    this.labels = data.labels;
                                    this.datasets = data.datasets;

                                    // Actualizar el gráfico con los nuevos datos
                                    this.chart.data.labels = this.labels;
                                    this.chart.data.datasets = this.datasets;
                                    this.chart.update();
                                })
                                .catch(error => console.error('Error al cargar los datos del gráfico:', error));
                        }
                    }
                }
            </script>
        </div>

        @section('scripts')
            {!! $chart->renderChartJsLibrary() !!}
            {!! $chart->renderJs() !!}

            {!! $chart1->renderChartJsLibrary() !!}
            {!! $chart1->renderJs() !!}


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
    @else
        <livewire:admin.empresas.configuracion-inicial />
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
</x-admin-layout>

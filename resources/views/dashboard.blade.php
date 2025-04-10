<x-admin-layout>
    <div class="w-full flex flex-col lg:flex-row gap-2 sm:gap-5">
        <div class="flex-1 md:max-w-sm">
            <div class="w-full h-[400px] mx-auto rounded-xl" id="movimientoschart"></div>
            {{-- <div class="w-full h-[400px] mx-auto rounded-xl" id="main"></div> --}}
        </div>
        @can('admin.almacen.productos.agotados')
            @if (count($productos) > 0)
                <div class="w-full md:pb-20 md:ml-auto lg:max-w-lg xl:max-h-[calc(100vh-4rem)] overflow-y-auto">
                    <div class="text-xs font-medium text-primary py-1">
                        PRODUCTOS AGOTADOS & PRÓXIMOS AGOTAR
                    </div>
                    @foreach ($productos as $item)
                        @php
                            $image = !empty($item->imagen) ? pathURLProductImage($item->imagen->urlmobile) : null;
                        @endphp
                        <div class="w-full flex p-1 text-[10px] border border-borderminicard gap-2">
                            <div class="w-20 h-20 flex-shrink-0 overflow-hidden">
                                @if (!empty($image))
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
    </div>

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
    {{-- </div> --}}

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

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script> --}}
    <script src="{{ asset('assets/echart/echarts.js') }}"></script>
    <script type="text/javascript">
        // Initialize the echarts instance based on the prepared dom
        //var myChart = echarts.init(document.getElementById('main'), 'light');

        // Specify the configuration items and data for the chart
        // var option = {
        //     color: [
        //         '#0fb9b9',
        //         '#0fb9b9',
        //         '#61a0a8',
        //         '#d48265',
        //         '#91c7ae',
        //         '#749f83',
        //         '#ca8622',
        //         '#bda29a',
        //         '#6e7074',
        //         '#546570',
        //         '#c4ccd3'
        //     ],
        //     title: {
        //         text: 'ECharts Primer ejemplo'
        //     },
        //     tooltip: {},
        //     legend: {
        //         data: ['sales']
        //     },
        //     xAxis: {
        //         data: ['Shirts', 'Cardigans', 'Chiffons', 'Pants', 'Heels', 'Socks']
        //     },
        //     yAxis: {},
        //     series: [{
        //         name: 'sales',
        //         type: 'bar',
        //         data: [5, 20, 36, 10, 10, 20],
        //         label: {
        //             show: true, // Muestra las etiquetas
        //             position: 'top', // Posición de las etiquetas (ejemplo: encima de las barras)
        //             color: '#0fb9b9', // Cambia el color de las etiquetas
        //             fontSize: 14, // Tamaño de fuente
        //             fontWeight: 'medium', // Grosor de la fuente
        //             formatter: ['{c} UND'].join(
        //             '\n'), // Formato del contenido de la etiqueta (por ejemplo, muestra el valor)
        //         }
        //     }]
        // };


        const labelOption = {
            show: true,
            rotate: 90,
            // position: 'top',
            formatter: '[{c} UND]  {name|{a}}',
            fontSize: 11,
            // color: '#FFF',
            fontWeight: 'bold',
            rich: {
                name: {}
            }
        };

        var option = {
            color: [
                '#f50000',
                '#f50000',
                '#f50000',
                '#d48265',
                '#91c7ae',
                '#749f83',
                '#ca8622',
                '#bda29a',
                '#6e7074',
                '#546570',
                '#0fb9b9'
            ],
            title: {
                text: 'Productos proximos agotarse',
                textStyle: {
                    fontSize: 20,
                    color: '#0fb9b9'
                },
            },
            xAxis: [{
                type: 'category',
                data: ['Producto 1', 'Producto 2', 'Producto 3', '2015', '2016']
            }],
            yAxis: [{
                type: 'value'
            }],
            series: [{
                    name: 'Almacen Jaen',
                    type: 'bar',
                    barGap: '5%',
                    label: labelOption,
                    emphasis: {
                        focus: 'series'
                    },
                    data: [2, 0, 1, 2, 3]
                },
                {
                    name: 'Almacen Trujillo',
                    type: 'bar',
                    label: labelOption,
                    emphasis: {
                        focus: 'series'
                    },
                    data: [1, 2, 1, 3, 2]
                }
            ]
        };

        // Display the chart using the configuration items and data just specified.
        //myChart.setOption(option);
    </script>

    <script>
        var myChartMov = echarts.init(document.getElementById('movimientoschart'), 'light');

        document.addEventListener('DOMContentLoaded', () => {
            const headers = {
                // 'Accept': 'application/json',
                // 'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            };

            sendRequest(`{{ route('admin.echarts.typemovements') }}`, headers, {}, response => {
                // $(componentloading).fadeOut();
                console.log(response);
                const typemovements = Object.values(response.typemovements).map(item => item.groupId);

                const labelOption2 = {
                    show: true,
                    // rotate: 90,
                    position: 'top',
                    formatter: '[S/. {c}]',
                    fontSize: 11,
                    // color: '#FFF',
                    fontWeight: 'medium',
                    rich: {
                        name: {}
                    }
                }

                var option2 = {
                    title: {
                        text: 'Resúmen de flujo de caja',
                        left: "center",
                        // top: "center",
                        textStyle: {
                            fontSize: 22,
                            color: '#0fb9b9'
                        },
                        subtext: '',
                    },
                    color: [
                        '#0fb9b9',
                        '#f50000',
                        '#61a0a8',
                        '#d48265',
                        '#91c7ae',
                        '#749f83',
                        '#ca8622',
                        '#bda29a',
                        '#6e7074',
                        '#546570',
                        '#c4ccd3'
                    ],
                    grid: {
                        top: "25%"
                    },
                    xAxis: {
                        data: typemovements
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                        // formatter: function (params) {
                        //   console.log(params);
                        //   var tar = params[0];
                        //   return tar.name + '<br/>' + tar.seriesName + ' : ' + tar.value;
                        // }
                    },
                    yAxis: {},
                    dataGroupId: '',
                    animationDurationUpdate: 500,
                    series: {
                        type: 'bar',
                        id: 'sales',
                        colorBy: 'data',
                        data: response.typemovements,
                        // data: [{
                        //         value: 5,
                        //         groupId: 'animals'
                        //     },
                        //     {
                        //         value: 2,
                        //         groupId: 'fruits'
                        //     },
                        // ],
                        universalTransition: {
                            enabled: true,
                            divideShape: 'clone'
                        },
                        label: labelOption2,

                    },
                    graphic: [{
                        type: 'text',
                        left: 20,
                        top: 20,
                        style: {
                            text: '',
                            backgroundColor: '',
                            padding: 0,
                            borderRadius: 0
                        },
                    }]
                };

                const drilldownData = response.typemovements.map(item => ({
                    dataGroupId: item.groupId,
                    data: item.data,
                }));

                // const drilldownData = [{
                //         dataGroupId: 'animals',
                //         data: [
                //             ['Cats', 4],
                //             ['Dogs', 2],
                //             ['Cows', 1],
                //             ['Sheep', 2],
                //             ['Pigs', 1]
                //         ]
                //     },
                //     {
                //         dataGroupId: 'fruits',
                //         data: [
                //             ['Apples', 4],
                //             ['Oranges', 2]
                //         ]
                //     }
                // ];
                // console.log(drilldownData);

                myChartMov.on('click', function(event) {
                    if (event.data) {
                        var subData = drilldownData.find(function(data) {
                            return data.dataGroupId === event.data.groupId;
                        });
                        if (!subData) {
                            return;
                        }
                        myChartMov.setOption({
                            title: {
                                text: 'Resúmen de flujo de caja',
                                left: "center",
                                textStyle: {
                                    fontSize: 22,
                                    color: '#0fb9b9'
                                },
                                subtext: 'Métodos de pago',
                                subtextStyle: {
                                    fontSize: 16,
                                    // color: '#0fb9b9'
                                },
                            },
                            color: [
                                '#ff8a41',
                                '#c810db',
                                '#107edb',
                                '#09bb2f',
                                '#bebebe',
                                '#536c5c',
                                '#ca8622',
                                '#bda29a',
                                '#6e7074',
                                '#546570',
                                '#c4ccd3'
                            ],
                            xAxis: {
                                data: subData.data.map(function(item) {
                                    return item[0];
                                })
                            },
                            series: {
                                type: 'bar',
                                id: 'sales',
                                // colorBy: 'series',
                                dataGroupId: subData.dataGroupId,
                                data: subData.data.map(function(item) {
                                    return item[1];
                                }),
                                universalTransition: {
                                    enabled: true,
                                    divideShape: 'clone'
                                },
                            },
                            graphic: [{
                                type: 'text',
                                left: 20,
                                top: 20,
                                style: {
                                    text: 'Regresar',
                                    fill: '#fff',
                                    fontSize: 12,
                                    backgroundColor: '#0fb9b9',
                                    padding: [8, 10],
                                    borderRadius: 8
                                },
                                onclick: function() {
                                    myChartMov.setOption(option2);
                                }
                            }]
                        });
                    }
                });
                myChartMov.setOption(option2);

            }, (errorResponse) => {
                // console.log(errorResponse);
                swal.fire({
                    title: errorResponse.mensaje,
                    text: null,
                    icon: 'error',
                    confirmButtonColor: '#0FB9B9',
                    confirmButtonText: 'Cerrar',
                })
            });
        })
    </script>
</x-admin-layout>

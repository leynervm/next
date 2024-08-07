<x-admin-layout>
    @if (mi_empresa())

        <div class="w-full max-w-md">

            <h1>{{ $chart->options['chart_title'] }}</h1>

            {!! $chart->renderHtml() !!}

        </div>

        @section('scripts')
            {!! $chart->renderChartJsLibrary() !!}
            {!! $chart->renderJs() !!}
        @endsection
    @else
        <livewire:admin.empresas.configuracion-inicial />
    @endif
</x-admin-layout>

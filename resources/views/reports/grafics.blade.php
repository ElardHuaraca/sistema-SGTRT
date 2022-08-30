@extends('app')
@section('title', 'Consumo de Recursos')
@section('content')
    <div class="content-wrapper">
        {{-- <!-- Content Header (Page header) --> --}}
        <section class="content-header text-center">
            <h1 class="h1-titulo">
                {{ $name }}
            </h1>
        </section>
        <section class="content">
            <div class="box" id="box-consumo">
                <div
                    class="d-flex justify-content-lg-space-evenly align-items-center flex-wrap justify-content-md-evenly py-3 px-2 border-top border-secondary">
                    <div class="d-inline-flex px-md-3 py-md-2 px-lg-0 col-xl-4 col-lg-5 col-12">
                        {{-- <!-- Fecha inicio--> --}}
                        <h6 id="h6-1" class="py-2 m-0 pe-2 col-lg-4 col-md-3 col-2"><b>Fecha Inicio:</b></h6>
                        <p class="form-control text-center">{{ str_replace('-', '/', $date_start) }}</p>
                    </div>
                    <div class="d-inline-flex px-md-3 py-md-2 px-lg-0 col-xl-4 col-lg-5 col-12">
                        {{-- <!-- Fecha fin--> --}}
                        <h6 id="h6-fin-1" class="py-2 m-0 pe-2 col-lg-4 col-md-3 col-2"><b>Fecha Fin:</b></h6>
                        <p class="form-control text-center">{{ str_replace('-', '/', $date_end) }}</p>
                    </div>
                </div>
                <div
                    class="d-flex justify-content-lg-start align-items-center flex-md-wrap justify-content-md-evenly py-1 px-2 border-top border-secondary py-sm-1 py-3">
                    <div class="px-lg-1 col-lg-2 col-md-3 col-3">
                        <a class="btn btn-secondary" role="button" href="{{ url()->previous() }}">Volver</a>
                    </div>
                    <div class="px-lg-1 h-100 col-lg-3 col-md-3 col-5">
                        <a class="btn btn-secondary" role="button"
                            href="{{ route('reports.grafic.export.resource_history', [str_replace('/', '-', $date_start), str_replace('/', '-', $date_end), $idserver]) }}">Generar
                            Reporte</a>
                    </div>
                    <div class="px-md-3 py-md-2 col-3">
                        <select class="form-select border-info" aria-label="Selecciona" id="picker-resource">
                            <option value="CPU" selected>CPU</option>
                            <option value="RAM">RAM</option>
                            <option value="DISCO">Disco</option>
                        </select>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="chart-grafic col-lg-12">
                        <canvas id="chart-grafic" height="100" class="px-3"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
<script>
    var server = @json($server);
</script>
@push('scripts')
    <script src="{{ asset('js/report/resource_it.js') }}"></script>
@endpush

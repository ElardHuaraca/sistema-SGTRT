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
                    class="d-flex justify-content-lg-space-evenly align-items-center flex-md-wrap justify-content-md-evenly py-3 px-2 border-top border-secondary">
                    <div class="d-inline-flex px-md-3 py-md-2  px-lg-0">
                        {{-- <!-- Fecha inicio--> --}}
                        <h6 id="h6-1" class="py-2 m-0 pe-2"><b>Fecha Inicio:</b></h6>
                        <input class="p-lg-1 date-green" type="date">
                    </div>
                    <div class="d-inline-flex px-md-3 py-md-2  px-lg-0 ">
                        {{-- <!-- Fecha fin--> --}}
                        <h6 id="h6-fin-1" class="py-2 m-0 pe-2"><b>Fecha Fin:</b></h6>
                        <input class="p-lg-1 date-green" type="date">
                    </div>
                </div>
                <div
                    class="d-flex justify-content-lg-start align-items-center flex-md-wrap justify-content-md-evenly py-1 px-2 border-top border-secondary">
                    <div class="px-lg-1">
                        <a class="btn btn-secondary" role="button" href="{{ url()->previous() }}">Volver</a>
                    </div>
                    <div class="px-lg-1 h-100">
                        <button class="btn btn-secondary" role="button">Generar Reporte</button>
                    </div>
                    <div class="px-md-3 py-md-2">
                        <select class="form-select border-info" aria-label="Selecciona" id="picker-resource">
                            <option value="CPU" selected>CPU</option>
                            <option value="RAM">RAM</option>
                            <option value="DISCO">Disco</option>
                        </select>
                    </div>
                </div>
                <div class="panel-body vh-50">
                    <div class="chart-grafic col-lg-12 vh-50">
                        <canvas id="chart-grafic" class="px-3" height="100"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
<script>
    var grafic_default = @json($grafic);
</script>
@push('scripts')
    <script src="{{ asset('js/report/resource_it.js') }}"></script>
@endpush

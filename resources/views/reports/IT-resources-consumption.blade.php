@extends('app')
@section('title', 'Consumo de Recursos')
@push('scripts')
    <script src="{{ asset('js/report/resource_it.js') }}"></script>
@endpush
@section('content')
    <div class="content-wrapper">
        {{-- <!-- Content Header (Page header) --> --}}
        <section class="content-header text-center">
            <h1 class="h1-titulo">
                REPORTE - CONSUMO RECURSOS TI
            </h1>
        </section>
        <section class="content">
            <div class="box" id="box-consumo">
                <div class="d-flex justify-content-md-between align-items-center px-lg-5 px-md-4 py-3">
                    {{-- <!-- Buscador cliente --> --}}
                    <div class="col-lg-3 col-md-4 d-inline-flex">
                        <h6 id="h6-cliente" class="py-2 m-0 pe-2">Cliente:</h6>
                        <input id="input-buscar-cliente"
                            class="form-control form-control-sm ml-5 w-30 rounded-3 rounded-pill" type="text"
                            placeholder="  Buscar por cliente" aria-label="Search">
                    </div>
                    <div class="col-lg-4 col-md-6 d-inline-flex">
                        {{-- <!-- Buscador Hostname --> --}}
                        <h6 id="h6-buscador-hostname" class="py-2 m-0 pe-2">Hostname/VMware:</h6>
                        <input id="input-buscar-hostname" class="form-control form-control-sm ml-5 w-30 rounded-pill"
                            type="text" placeholder="  Buscar por VMware" aria-label="Search">
                    </div>
                </div>
                <div
                    class="d-flex justify-content-lg-between align-items-center flex-md-wrap justify-content-md-evenly py-3 px-2 border-top border-secondary">
                    <div class="px-md-3 px-lg-0 px-xl-3">
                        <button
                            class="btn btn-secondary disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                            id="btn-generate-report">Generar reporte</button>
                    </div>
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
                    <div class="px-md-3 px-lg-0 px-xl-3">
                        <button class="btn btn-primary" id="btn-consult">Consultar</button>
                    </div>
                </div>
                <div class="px-3 pt-2 pb-3">
                    <table class="table table-striped responsive" id="table-resources-it">
                        <thead>
                            <tr>
                                <th scope="col">Nº</th>
                                <th scope="col">Activo</th>
                                <th scope="col">ALP</th>
                                <th scope="col">Proyecto</th>
                                <th scope="col">Servidor</th>
                                <th scope="col">CPU</th>
                                <th scope="col">Memoria</th>
                                <th scope="col">Disco</th>
                                <th scope="col">Servicio</th>
                                <th scope="col">Grafica</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servers as $server)
                                <tr>
                                    <th scope="row">{{ $loop->index + 1 }}</th>
                                    <td>{{ $server->active }}</td>
                                    <td>{{ $server->idproject }}</td>
                                    <td>{{ $server->project_name }}</td>
                                    <td>{{ $server->name }}</td>
                                    <?php $resources = json_decode($server->resources); ?>
                                    <td>{{ isset($resources->CPU) ? $resources->CPU : 0 }}</td>
                                    <td>{{ isset($resources->RAM) ? $resources->RAM : 0 }}</td>
                                    <?php $SSD = isset($resources->SSD) ? $resources->SSD : 0;
                                    $HDD = isset($resources->HDD) ? $resources->HDD : 0; ?>
                                    <td>{{ $SSD + $HDD }}</td>
                                    <td>{{ $server->sow_name }}</td>
                                    <td>
                                        <a class="btn btn-info" role="button"
                                            href="{{ route('reports.grafic', $server->idserver) }}">
                                            <i class="fa-solid fa-chart-simple"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    @include('response/status')
@endsection

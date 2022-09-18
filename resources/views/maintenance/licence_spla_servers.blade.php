@extends('app')
@section('title', 'Servidores Lic. SPLA')
@section('content')
    <div class="content-wrapper">
        <section class="content-header text-center">
            <h1 class="h1-titulo">
                SERVIDORES - LICENCIAS SPLA
            </h1>
        </section>
        <section class="content">
            <div class="box" id="box-consumo">
                <div class="px-3 pt-2 pb-3">
                    <table class="table table-striped responsive" id="table-resources-it">
                        <thead>
                            <tr>
                                <th scope="col" class="col-1">ALP</th>
                                <th scope="col" class="col-1">Proyecto</th>
                                <th scope="col" class="col-1">VM</th>
                                <th scope="col" class="col-1">CPU</th>
                                <th scope="col" class="col-1">Codigo de Licencia</th>
                                <th scope="col" class="col-1">Tipo</th>
                                <th scope="col" class="col-1">Lic. Requeridas</th>
                                <th scope="col" class="col-1">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servers as $server)
                                <tr>
                                    @if ($server['CPU'] != 0)
                                    @endif
                                    <td>{{ $server['idproject'] }}</td>
                                    <td>{{ $server['project_name'] }}</td>
                                    <td>{{ $server['server_name'] }}</td>
                                    <td>{{ $server['CPU'] }}</td>
                                    <td>{{ $server['license_code'] }}</td>
                                    <td>{{ $server['license_type'] }}</td>
                                    <td>{{ $server['license_req'] }}</td>
                                    <td>$ {{ $server['license_cost'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection

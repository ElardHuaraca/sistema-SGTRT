@extends('app')
@section('title', 'Tarifario TI')
@section('content')
    <div class="content-wrapper">
        {{-- <!-- Content Header (Page header) --> --}}
        <section class="content-header text-center">
            <h1 class="h1-titulo">
                REPORTE - TARIFARIO TI "{{ $project_name }}"
            </h1>
        </section>
        <section class="content">
            <div class="box" id="box-consumo">
                <div
                    class="d-flex flex-sm-row flex-column justify-content-lg-evenly align-items-center flex-md-wrap justify-content-md-evenly py-3 px-2 border-top border-secondary col-xl-12">
                    <div class="d-inline-flex  px-lg-1 col-lg-2 col-md-3 col-3">
                        <a class="btn btn-secondary" role="button" href="{{ url()->previous() }}">Volver</a>
                    </div>
                    <div class="d-inline-flex  px-md-3 py-md-2 px-lg-0 col-xl-3 col-lg-3 col-12 pt-sm-0 pt-2">
                        {{-- <!-- Fecha inicio--> --}}
                        <h6 id="h6-1" class="col-md-4 col-3 py-2 m-0 pe-2">
                            <b>Fecha Inicio:</b>
                        </h6>
                        <p class="form-control text-center">{{ $date_start }}</p>
                    </div>
                    <div class="d-inline-flex px-md-3 py-md-2 px-lg-0 col-xl-3 col-lg-3 col-12 pt-sm-0 pt-2">
                        {{-- <!-- Fecha fin--> --}}
                        <h6 id="h6-fin-1" class="col-md-4 col-3 py-2 m-0 pe-2">
                            <b>Fecha Fin:</b>
                        </h6>
                        <p class="form-control text-center">{{ $date_end }}</p>
                    </div>
                </div>

                <div class="px-3 pt-2 pb-3">
                    <table class="table table-striped responsive" id="table-resources-it">
                        <thead>
                            <tr>
                                <th scope="col" class="col-1">Servidor</th>
                                <th scope="col" class="col-1">CPU</th>
                                <th scope="col" class="col-1">Disco</th>
                                <th scope="col" class="col-1">RAM</th>
                                <th scope="col" class="col-1">Lic. SPLA</th>
                                <th scope="col" class="col-1">Lic. Cloud</th>
                                <th scope="col" class="col-1">Backup</th>
                                <th scope="col" class="col-1">MO</th>
                                <th scope="col" class="col-1">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($costs as $cost)
                                <tr>
                                    <td>{{ $cost->server_name }}</td>
                                    <td>S/. {{ number_format($cost->CPU * $exchangeRates_footer->value, 2) }}</td>
                                    <td>S/. {{ number_format($cost->DISK * $exchangeRates_footer->value, 2) }}</td>
                                    <td>S/. {{ number_format($cost->RAM * $exchangeRates_footer->value, 2) }} </td>
                                    <td>S/. {{ number_format($cost->cost_splas * $exchangeRates_footer->value, 2) }}</td>
                                    <td>S/. {{ number_format($cost->lic_cloud * $exchangeRates_footer->value, 2) }}</td>
                                    <td>S/. {{ number_format($cost->backup * $exchangeRates_footer->value, 2) }}</td>
                                    <td>S/. {{ number_format($cost->mo * $exchangeRates_footer->value, 2) }}</td>
                                    <td>S/. {{ number_format($cost->cost_total * $exchangeRates_footer->value, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="w-100 pb-3">
                    <div class="text-center">
                        <a role="button"
                            href="{{ route('generate.report.it_tariff', [str_replace('/', '-', $date_start), str_replace('/', '-', $date_end), $idproject]) }}"
                            class="btn btn-success disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                            id="btn-generate-report">Generar reporte</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

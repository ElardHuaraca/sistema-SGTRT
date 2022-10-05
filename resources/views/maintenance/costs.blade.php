@extends('app')
@section('title', 'Costo Mantenimiento')
@section('content')
    <div class="content-wrapper">
        {{-- <!-- Content Header (Page header) --> --}}
        <section class="content-header text-center">
            <h1 class="h1-titulo">
                MANTENIMIENTO - COSTOS
            </h1>
        </section>
        <section class="content">
            <div class="box" id="box-consumo">
                <div
                    class="d-flex justify-content-lg-start justify-content-center flex-lg-nowrap flex-wrap flex-column flex-lg-row px-lg-4 px-md-4 py-3 px-2">
                    <button
                        class="btn btn-success me-sm-4 disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                        type="button" data-bs-toggle="modal" data-bs-target="#modalCreateCost" id="btn-create-cost">Agregar
                        Costo</button>
                </div>
                <div
                    class="d-flex justify-content-between flex-column flex-lg-row flex-md-nowrap flex-wrap px-lg-4 px-md-4 px-2 py-3 border-top border-secondary">
                    {{-- <!-- Buscador cliente --> --}}
                    <div
                        class="d-flex align-items-center flex-md-row justify-content-md-start justify-content-center flex-md-nowrap flex-wrap col-auto col-md-6 col-lg-5 py-md-3">
                        <input
                            class="form-control-sm col-12 col-lg-8 col-md-12 pb-md-0 pb-3 me-md-3 disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                            type="file" id="fileUnique"
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        <button
                            class="btn btn-success col-12 col-xl-4 col-lg-6 col-md-6 mb-md-0 mb-3 disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                            type="button" id="btn-import-csv">Cargar Datos</button>
                    </div>
                    <div class="d-flex align-items-center col-auto">
                        {{-- getTime now --}}
                        <input class="form-control me-3 text-indent-1 px-3" type="text" id="date_selected"
                            placeholder="Seleccione una Fecha" onkeydown="return false" value="{{ $date_start }}">
                        <button class="btn btn-success col-4" type="button" id="btn-consult-date">Consultar</button>
                    </div>

                </div>
                <div class="px-3 pt-2 pb-3">
                    <table class="table table-striped responsive" id="table-resources-it">
                        <thead>
                            <tr>
                                <th scope="col" class="col-1">Nº</th>
                                <th scope="col">ALP</th>
                                <th scope="col">Proyecto</th>
                                <th scope="col">Costo Mensual 4wall</th>
                                <th scope="col">Costo Nexus</th>
                                <th scope="col">Costo HP DC Care</th>
                                <th scope="col" class="col-1">Total Dolares</th>
                                <th scope="col" class="col-1">Total Soles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <th scope="row">{{ $loop->index + 1 }}</th>
                                    <td> {{ $project->idproject }}</td>
                                    <td> {{ $project->name }}</td>
                                    <td>
                                        <a href="{{ route('fourwall.details', $project->idproject) }}">
                                            $
                                            {{ $project->costfourwalls == 0 ? number_format(0, 2) : number_format($project->costfourwalls, 2) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('nexus.details', $project->idproject) }}">
                                            $
                                            {{ $project->costnexus == 0 ? number_format(0, 2) : number_format($project->costnexus, 2) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('hp.details', $project->idproject) }}">
                                            $
                                            {{ $project->costhp == 0 ? number_format(0, 2) : number_format($project->costhp, 2) }}
                                        </a>
                                    </td>
                                    <td>$
                                        {{ number_format($project->costfourwalls + $project->costnexus + $project->costhp, 2) }}
                                    </td>
                                    <?php $sum = $project->costfourwalls + $project->costnexus + $project->costhp; ?>
                                    <td> S/.
                                        {{ $sum == 0 ? number_format(0, 2) : number_format($sum * $exchangeRates_footer->value, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="modalCreateCost" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="content-mod">
                <div class="modal-header bg-primary-custom">
                    <h5 class="modal-title text-white text-center w-100" id="staticBackdropLabel">Añadir Fourwalls</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form_costs" autocomplete="off">
                        <div class="input-group mb-3 recomendations">
                            <div class="input-group-prepend col-4 ">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    Seleccione el tipo de costo
                                </span>
                            </div>
                            <select class="form-control col-6 mx-3" name="cost_type" required>
                                <option value="fourwall" selected>4walls</option>
                                <option value="nexus">Nexus</option>
                                <option value="hp">HP DC Care</option>
                            </select>
                        </div>
                        <div class="input-group mb-3 recomendations">
                            <div class="input-group-prepend col-4 ">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-code pe-2"></i>
                                    Codigo ALP
                                </span>
                            </div>
                            <input type="text" class="form-control col-6" placeholder="Codigo ALP"
                                aria-label="Codigo ALP" aria-describedby="basic-addon1" name="codigo_alp"
                                style="z-index: 102" maxlength="6" required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-diagram-project pe-2"></i>
                                    <span id="first_text">Equipo 4wall</span>
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Nombre del equipo 4wall"
                                aria-label="Equipo 4wall" aria-describedby="basic-addon1" name="equipment_fourwall"
                                required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-barcode pe-2"></i>
                                    <span id="second_text">Serie 4wall</span>
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder=" Serie 4wall"
                                aria-label=" Serie 4wall" aria-describedby="basic-addon1" name="serie_fourwall" required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-regular fa-money-bill-1 pe-2"></i>
                                    <span id="third_text">Costo 4wall</span>
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Costo 4wall"
                                aria-label="Costo 4wall" aria-describedby="basic-addon1" name="cost_fourwall" required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-regular fa-calendar-days pe-2"></i>
                                    Fecha de inicio
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Seleccione una fecha"
                                onkeydown="return false" aria-label="Seleccione una fecha"
                                aria-describedby="basic-addon1" name="date_start" id="date_start_fourwall" required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-regular fa-calendar-days pe-2"></i>
                                    Fecha fin
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Seleccione una fecha"
                                onkeydown="return false" aria-label="Seleccione una fecha"
                                aria-describedby="basic-addon1" name="date_end" id="date_end_fourwall">
                        </div>
                        <button class="d-none" id="btn-sumbit-cost" type="submit"></button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="btn-close">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="save_cost">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    @csrf
    @include('response.status')
@endsection
<script>
    var projects = @json($projects);
    var tchange = @json($exchangeRates_footer->value);
    const exchange = {{ number_format($exchangeRates_footer->value, 2) }}
</script>
@push('scripts')
    <script src="{{ asset('js/maintenance/cost.js') }}"></script>
@endpush

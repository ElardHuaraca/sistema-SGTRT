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
                <div class="d-flex px-lg-4 px-md-4 py-3">
                    {{-- <!-- Buscador cliente --> --}}
                    <button
                        class="btn btn-success me-4 disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                        type="button" data-bs-toggle="modal" data-bs-target=" #modalCreateFourwall"
                        id="btn-create-fourwall">Agregar costo 4Wall</button>
                    <button
                        class="btn btn-success me-4 disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                        type="button" data-bs-toggle="modal" data-bs-target=" #modalCreateNexus"
                        id="btn-create-nexus">Agregar costo Nexus</button>
                    <button
                        class="btn btn-success me-4 disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                        type="button" data-bs-toggle="modal" data-bs-target=" #modalCreateHp" id="btn-create-hp">Agregar
                        costo HP</button>
                </div>
                <div class="d-flex justify-content-between px-lg-4 px-md-4 py-3 border-top border-secondary">
                    {{-- <!-- Buscador cliente --> --}}
                    <div class="d-flex align-items-center col-auto col-md-6 col-lg-5">
                        <input
                            class="form-control me-3 disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                            type="file" id="fileUnique"
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        <button
                            class="btn btn-success col-4 disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                            type="button" data-bs-toggle="modal" data-bs-target="#modalAddNexus"
                            id="btn-create-nexus">Cargar Datos</button>
                    </div>
                    <div class="d-flex align-items-center col-auto">
                        {{-- getTime now --}}
                        <input class="form-control me-3 text-indent-1 px-3" type="text" id="date_selected"
                            placeholder="Seleccione una Fecha" onkeydown="return false">
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
                                            {{ $project->costofourwalls == 0 ? number_format(0, 2) : number_format($project->costofourwalls, 2) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('nexus.details', $project->idproject) }}">
                                            $
                                            {{ $project->costonexus == 0 ? number_format(0, 2) : number_format($project->costonexus, 2) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('hp.details', $project->idproject) }}">
                                            $
                                            {{ $project->costohp == 0 ? number_format(0, 2) : number_format($project->costohp, 2) }}
                                        </a>
                                    </td>
                                    <td>$
                                        {{ number_format($project->costofourwalls + $project->costonexus + $project->costohp, 2) }}
                                    </td>
                                    <?php $sum = $project->costofourwalls + $project->costonexus + $project->costohp; ?>
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
    <div class="modal fade" id="modalCreateFourwall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="content-mod">
                <div class="modal-header bg-primary-custom">
                    <h5 class="modal-title text-white modal-title-3" id="staticBackdropLabel">Añadir Fourwalls</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-create-fourwall" autocomplete="off">
                        <div class="input-group mb-3 recomendations">
                            <div class="input-group-prepend col-4 ">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-code pe-2"></i>
                                    Codigo ALP
                                </span>
                            </div>
                            <input type="text" class="form-control col-6" placeholder="Codigo ALP"
                                aria-label="Codigo ALP" aria-describedby="basic-addon1" name="codigo_alp"
                                style="z-index: 102" maxlength="6">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-diagram-project pe-2"></i>
                                    Equipo 4wall
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Nombre del equipo 4wall"
                                aria-label="Equipo 4wall" aria-describedby="basic-addon1" name="equipment_fourwall">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-barcode pe-2"></i>
                                    Serie 4wall
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder=" Serie 4wall"
                                aria-label=" Serie 4wall" aria-describedby="basic-addon1" name="serie_fourwall">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-regular fa-money-bill-1 pe-2"></i>
                                    Costo 4wall
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Costo 4wall"
                                aria-label="Costo 4wall" aria-describedby="basic-addon1" name="cost_fourwall">
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
                                aria-describedby="basic-addon1" name="date_start" id="date_start_fourwall">
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
                        <button class="d-none" id="btn-sumbit-fourwall" type="submit"></button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="btn-close">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-update-create-fourwall">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCreateNexus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="content-mod">
                <div class="modal-header bg-primary-custom">
                    <h5 class="modal-title text-white modal-title-3" id="staticBackdropLabel">Añadir Nexus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-create-nexus">
                        <div class="input-group mb-3 recomendations">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-code pe-2"></i>
                                    Codigo ALP
                                </span>
                            </div>
                            <input type="text" class="form-control col-6" placeholder="Codigo ALP"
                                aria-label="Codigo ALP" aria-describedby="basic-addon1" name="codigo_alp"
                                style="z-index: 102">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-diagram-project pe-2"></i>
                                    Punto de red
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Punto de red nexus"
                                aria-label="Punto de red nexus" aria-describedby="basic-addon1" name="point_red_nexus">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-regular fa-money-bill-1 pe-2"></i>
                                    Costo Nexus
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Costo Nexus"
                                aria-label="Costo Nexus" aria-describedby="basic-addon1" name="cost_nexus">
                        </div>
                        <button class="d-none" id="btn-sumbit-nexus" type="submit"></button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="btn-close">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-update-create-nexus">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCreateHp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="content-mod">
                <div class="modal-header bg-primary-custom">
                    <h5 class="modal-title text-white modal-title-4" id="staticBackdropLabel">Añadir Hp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-create-hp" autocomplete="off">
                        <div class="input-group mb-3 recomendations">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-code pe-2"></i>
                                    Codigo ALP
                                </span>
                            </div>
                            <input type="text" class="form-control col-6" placeholder="Codigo ALP"
                                aria-label="Codigo ALP" aria-describedby="basic-addon1" name="codigo_alp"
                                style="z-index: 102">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-diagram-project pe-2"></i>
                                    Equipo HP
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Nombre del equipo HP"
                                aria-label="Equipo HP" aria-describedby="basic-addon1" name="equip_hp">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-barcode pe-2"></i>
                                    Serie HP
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Serie HP"
                                aria-label="Serie HP" aria-describedby="basic-addon1" name="serie_hp">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-regular fa-money-bill-1 pe-2"></i>
                                    Costo HP
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Costo HP"
                                aria-label="Costo HP" aria-describedby="basic-addon1" name="cost_hp">
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
                                aria-describedby="basic-addon1" name="date_start" id="date_start_hp">
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
                                aria-describedby="basic-addon1" name="date_end" id="date_end_hp">
                        </div>
                        <button class="d-none" id="btn-sumbit-hp" type="submit"></button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="btn-close">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-update-create-hp">Guardar</button>
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
</script>
@push('scripts')
    <script src="{{ asset('js/maintenance/cost.js') }}"></script>
@endpush

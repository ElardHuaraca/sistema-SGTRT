@extends('app')
@section('title', 'Detalle Nexus')
@section('content')
    <div class="content-wrapper">
        {{-- <!-- Content Header (Page header) --> --}}
        <section class="content-header text-center">
            <h1 class="h1-titulo">
                MANTENIMIENTO - NEXUS
            </h1>
        </section>
        <section class="content">
            <div class="box" id="box-consumo">
                <div class="px-3 pt-2 pb-3">
                    <table class="table table-striped responsive" id="table-resources-it">
                        <thead>
                            <tr>
                                <th scope="col" class="col-1">Nº</th>
                                <th scope="col" class="col-2">ALP</th>
                                <th scope="col" class="col-2">Proyecto</th>
                                <th scope="col" class="col-2">Punto de red</th>
                                <th scope="col" class="col-2">Serie</th>
                                <th scope="col" class="col-1">Costo</th>
                                <th scope="col" class="col-1">Editar</th>
                                <th scope="col" class="col-1">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nexus as $nexu)
                                <tr>
                                    <th scope="row">{{ $loop->index + 1 }}</th>
                                    <td> {{ $nexu->idproject }}</td>
                                    <td> {{ $nexu->name }}</td>
                                    <td> {{ $nexu->network_point }}</td>
                                    <td>{{ $nexu->serie }}</td>
                                    <td> $ {{ $nexu->cost }}</td>
                                    <td>
                                        <a data-bs-toggle="modal" href="#modalUpdateNexus"
                                            class="btn btn-warning btn-sm {{ $nexu->is_deleted ? 'disabled' : '' }}"
                                            value="{{ $nexu->idnexus }}">
                                            <i class="fas fa-edit"></i>
                                            <span>Editar</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a value="{{ $nexu->idnexus }}"
                                            class="btn btn-danger btn-sm btn-delete-nexus {{ $nexu->is_deleted ? 'disabled' : '' }}">
                                            <i class="fas fa-trash"></i>
                                            <span>Eliminar</span>
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
    <div class="modal fade" id="modalUpdateNexus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="content-mod">
                <div class="modal-header bg-primary-custom">
                    <h5 class="modal-title text-white text-center w-100" id="staticBackdropLabel">Editar Nexus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-update-nexus">
                        <div class="input-group mb-3 recomendations">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-code pe-2"></i>
                                    Codigo ALP
                                </span>
                            </div>
                            <input type="text" class="form-control col-6" placeholder="Codigo ALP"
                                aria-label="Codigo ALP" aria-describedby="basic-addon1" name="codigo_alp"
                                style="z-index: 102" disabled>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-diagram-project pe-2"></i>
                                    Punto de red
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Punto de red nexus"
                                aria-label="Punto de red nexus" aria-describedby="basic-addon1" name="point_red_nexus"
                                required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-regular fa-money-bill-1 pe-2"></i>
                                    Costo Nexus
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Costo Nexus"
                                aria-label="Costo Nexus" aria-describedby="basic-addon1" name="cost_nexus" required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-barcode pe-2"></i>
                                    Serie Nexus
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Serie HP" aria-label="Serie HP"
                                aria-describedby="basic-addon1" name="serie_nexus" required>
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
                                aria-describedby="basic-addon1" name="date_start" id="date_start_hp" required>
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
                        <button class="d-none" id="btn-sumbit-nexus" type="submit"></button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="btn-close">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-update-nexus">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    @csrf
    @include('response.status')
@endsection
<script>
    var nexus = @json($nexus);
</script>
@push('scripts')
    <script src="{{ asset('js/maintenance/cost_additionals.js') }}"></script>
@endpush

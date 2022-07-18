@extends('app')
@section('title', 'Detalle Fourwalls')
@section('content')
    <div class="content-wrapper">
        {{-- <!-- Content Header (Page header) --> --}}
        <section class="content-header text-center">
            <h1 class="h1-titulo">
                MANTENIMIENTO - 4 WALLS
            </h1>
        </section>
        <section class="content">
            <div class="box" id="box-consumo">
                <div class="px-3 pt-2 pb-3">
                    <table class="table table-striped responsive" id="table-resources-it">
                        <thead>
                            <tr>
                                <th scope="col">Nº</th>
                                <th scope="col" class="col-1">ALP</th>
                                <th scope="col" class="col-1">Proyecto</th>
                                <th scope="col" class="col-1">Equipo</th>
                                <th scope="col" class="col-2">Serie</th>
                                <th scope="col" class="col-1">Costo de Equipo</th>
                                <th scope="col" class="col-2">Fecha de Inicio de Contrato</th>
                                <th scope="col" class="col-2">Fecha de Fin de Contrato</th>
                                <th scope="col" class="col-1">Editar</th>
                                <th scope="col" class="col-1">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fourwalls as $fourwall)
                                <tr>
                                    <th scope="row">{{ $loop->index + 1 }}</th>
                                    <td> {{ $fourwall->idproject }}</td>
                                    <td> {{ $fourwall->name }}</td>
                                    <td> {{ $fourwall->equipment }}</td>
                                    <td> {{ $fourwall->serie }}</td>
                                    <td> $ {{ $fourwall->cost }}</td>
                                    <td> {{ date('d/m/Y', strtotime($fourwall->date_start)) }}</td>
                                    <td>
                                        {{ $fourwall->date_end === null ? 'N.E.' : date('d/m/Y', strtotime($fourwall->date_end)) }}
                                    </td>
                                    <td>
                                        <a data-bs-toggle="modal" href="#modalUpdateFourwall"
                                            class="btn btn-warning btn-sm {{ $fourwall->is_deleted ? 'disabled' : '' }}"
                                            value="{{ $fourwall->idfourwall }}">
                                            <i class="fas fa-edit"></i>
                                            <span>Editar</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a value="{{ $fourwall->idfourwall }}"
                                            class="btn btn-danger btn-sm btn-delete-fourwall {{ $fourwall->is_deleted ? 'disabled' : '' }}">
                                            <i class="fas fa-trash"></i>
                                            <span>{{ $fourwall->is_deleted ? 'Eliminado' : 'Eliminar' }}</span>
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
    <div class="modal fade" id="modalUpdateFourwall" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="content-mod">
                <div class="modal-header bg-primary-custom">
                    <h5 class="modal-title text-white modal-title-3" id="staticBackdropLabel">Añadir Fourwalls</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-update-fourwall" autocomplete="off">
                        <div class="input-group mb-3 recomendations">
                            <div class="input-group-prepend col-4 ">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-code pe-2"></i>
                                    Codigo ALP
                                </span>
                            </div>
                            <input type="text" class="form-control col-6" placeholder="Codigo ALP"
                                aria-label="Codigo ALP" aria-describedby="basic-addon1" name="codigo_alp"
                                style="z-index: 102" maxlength="6" disabled>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-diagram-project pe-2"></i>
                                    Equipo 4wall
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
                                    Serie 4wall
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder=" Serie 4wall"
                                aria-label=" Serie 4wall" aria-describedby="basic-addon1" name="serie_fourwall" required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-regular fa-money-bill-1 pe-2"></i>
                                    Costo 4wall
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
                        <button class="d-none" id="btn-sumbit-fourwall" type="submit"></button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="btn-close">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-update-fourwall">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    @csrf
    @include('response.status')
@endsection
<script>
    var fourwalls = @json($fourwalls);
</script>
@push('scripts')
    <script src="{{ asset('js/maintenance/cost_additionals.js') }}"></script>
@endpush

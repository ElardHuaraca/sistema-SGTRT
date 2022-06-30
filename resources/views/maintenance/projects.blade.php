@extends('app')
@section('title', 'Proyectos')
@section('content')
    <div class="content-wrapper">
        {{-- <!-- Content Header (Page header) --> --}}
        <section class="content-header text-center">
            <h1 class="h1-titulo">
                MANTENIMIENTO - PROYECTOS
            </h1>
        </section>
        <section class="content">
            <div class="box" id="box-consumo">
                <div class="d-flex align-items-center px-lg-3 px-md-4 py-3">
                    {{-- <!-- Buscador cliente --> --}}
                    <div class="col-lg-4 col-md-4 d-inline-flex">
                        <button
                            class="btn btn-info disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                            type="button" data-bs-toggle="modal" data-bs-target="#modalEditProject"
                            id="btn-create-project">Agregar nuevo Proyecto</button>
                    </div>
                </div>
                <div class="px-3 pt-2 pb-3 border-top border-secondary">
                    <table class="table table-striped responsive" id="table-resources-it">
                        <thead>
                            <tr>
                                <th scope="col" class="col-1">Nº</th>
                                <th scope="col">ALP</th>
                                <th scope="col">Nombre del proyecto</th>
                                <th scope="col" class="col-1">Editar</th>
                                <th scope="col" class="col-1">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <th scope="row">{{ $loop->index + 1 }}</th>
                                    <td>{{ $project->idproject }}</td>
                                    <td>{{ $project->name }}</td>
                                    <td>
                                        <button
                                            class="btn btn-warning disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                                            id="btn-edit-project" data-bs-toggle="modal" data-bs-target="#modalEditProject"
                                            value="{{ $project->idproject }}">Editar</button>
                                    </td>
                                    <td>
                                        @if ($project->is_deleted)
                                            <button
                                                class="btn btn-danger btn-update-project disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                                                value="{{ $project->idproject }}">Inactivo</button>
                                        @else
                                            <button
                                                class="btn btn-success btn-update-project disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                                                value="{{ $project->idproject }}">Activo</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="modalEditProject" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="content-mod">
                <div class="modal-header bg-primary-custom">
                    <h5 class="modal-title text-white" id="staticBackdropLabel">Editar el tipo de cambio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-update-project" autocomplete="off">
                        @csrf
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-code pe-3"></i>
                                    Codigo ALP
                                </span>
                            </div>
                            <input type="text" class="form-control col-6" placeholder="Codigo ALP"
                                aria-label="Codigo ALP" aria-describedby="basic-addon1" name="codigo_alp" maxlength="6" />
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend col-4">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-diagram-project pe-3"></i>
                                    Proyecto
                                </span>
                            </div>
                            <input type="text" class="form-control col-8" placeholder="Proyecto" aria-label="Proyecto"
                                aria-describedby="basic-addon1" name="proyecto" />
                        </div>
                        <button class="d-none" id="btn-sumbit-project" type="submit"></button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-update-create-project">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <!-- Modal --> --}}
    @include('response.status')
@endsection
<script>
    var projects = @json($projects);
</script>
@push('scripts')
    <script src="{{ asset('js/maintenance/project.js') }}"></script>
@endpush

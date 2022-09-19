@extends('app')
@section('title', 'Licencias SPLA')
@section('content')
    <div class="content-wrapper">
        <section class="content-header text-center">
            <h1 class="h1-titulo">
                LICENSIAS SPLA
            </h1>
        </section>
        <section class="content">
            <div class="box" id="box-consumo">
                <div class="d-flex align-items-center px-lg-3 px-md-4 py-3">
                    <div class="col-lg-4 col-md-4 d-inline-flex">
                        <button class="btn btn-info disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                            type="button" data-bs-toggle="modal" data-bs-target="#modalCreateEditLicenceSpla"
                            id="btn-update-create-project">Agregar nueva Licencia</button>
                    </div>
                </div>
                <div class="px-3 pt-2 pb-3 border-top border-secondary">
                    <table class="table table-striped responsive" id="table-resources-it">
                        <thead>
                            <tr>
                                <th scope="col" class="col-1">Nº</th>
                                <th scope="col" class="col-3">Codigo de licencia</th>
                                <th scope="col" class="col-3">Nombre de licencia</th>
                                <th scope="col" class="col-1">Tipo</th>
                                <th scope="col" class="col-1">Costo</th>
                                <th scope="col" class="col-1">Editar</th>
                                <th scope="col" class="col-1">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($licences as $licence)
                                <tr>
                                    <th scope="row">{{ $loop->index + 1 }}</th>
                                    <td>{{ $licence->code }}</td>
                                    <td>{{ $licence->name }}</td>
                                    <td>{{ $licence->type }}</td>
                                    <td>$ {{ number_format($licence->cost, 2) }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-edit-licence-spla" data-bs-toggle="modal"
                                            data-bs-target="#modalCreateEditLicenceSpla"
                                            value="{{ $licence->idspla }}">Editar</button>
                                    </td>
                                    <td>
                                        @if ($licence->is_deleted)
                                            <button
                                                class="btn btn-danger btn-status-licence-spla disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                                                value="{{ $licence->idspla }}">Inactivo</button>
                                        @else
                                            <button
                                                class="btn btn-success btn-status-licence-spla disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                                                value="{{ $licence->idspla }}">Activo</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <div class="modal fade" id="modalCreateEditLicenceSpla" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" id="content-mod">
                    <div class="modal-header bg-primary-custom text-center">
                        <h5 class="modal-title text-white w-100" id="staticBackdropLabel">Añádir Licencia SPLA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form-update-create-licence_spla" autocomplete="off">
                            @csrf
                            <input class="d-none" value="">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend col-sm-3 d-none d-sm-block">
                                    <span class="input-group-text h-100" id="basic-addon1">
                                        <i class="fa-solid fa-barcode pe-3"></i>
                                        Codigo
                                    </span>
                                </div>
                                <input type="text" class="form-control col-8" placeholder="Codigo de licencia"
                                    aria-label="Licence code spla" aria-describedby="basic-addon1" name="code" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend col-sm-3 d-none d-sm-block">
                                    <span class="input-group-text h-100" id="basic-addon1">
                                        <i class="fa-solid fa-font pe-3"></i>
                                        Nombre
                                    </span>
                                </div>
                                <input type="text" class="form-control col-8" placeholder="Nombre"
                                    aria-label="Licence name spla" aria-describedby="basic-addon1" name="name" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend col-sm-3 d-none d-sm-block">
                                    <span class="input-group-text h-100" id="basic-addon1">
                                        <i class="fa-solid fa-dollar-sign pe-3"></i>
                                        Costo
                                    </span>
                                </div>
                                <input type="text" class="form-control col-8" placeholder="Costo de licencia"
                                    aria-label="Licence cost spla" aria-describedby="basic-addon1" name="cost" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend col-sm-3 d-none d-sm-block">
                                    <span class="input-group-text h-100" id="basic-addon1">
                                        <i class="fa-solid fa-list pe-3"></i>
                                        Tipo
                                    </span>
                                </div>
                                <select class="form-select form-select-sm py-2 col-8" aria-label="Licence type spla"
                                    name="type" required>
                                    <option value="" selected>Selecciona un tipo de licencia</option>
                                    <option value="SO">SO</option>
                                    <option value="Office">Office</option>
                                    <option value="SQL Server">SQL Server</option>
                                    <option value="Remote Desktop">Remote Desktop</option>
                                    <option value="SQL Server 2">SQL Server 2</option>
                                </select>
                            </div>
                            <button class="d-none" id="btn-sumbit-licence_spla" type="submit"></button>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            id="btn-close">Cerrar</button>
                        <button type="button"
                            class="btn btn-primary disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                            id="btn-update-create-licence_spla">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('response.status')
@endsection
<script>
    var licences = @json($licences);
</script>
@push('scripts')
    <script src="{{ asset('js/maintenance/licence_spla.js') }}"></script>
@endpush

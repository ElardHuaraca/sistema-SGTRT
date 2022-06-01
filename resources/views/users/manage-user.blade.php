@extends('app')
@section('title', 'Gestionar Usuario')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user-manage.css') }}">
@endpush
@section('content')
    <div class="content-wrapper">
        {{-- <!-- Content Header (Page header) --> --}}
        <section class="content-header text-center">
            <h1 class="h1-titulo">
                Gestionar Usuarios
            </h1>
        </section>
        <section class="content">
            <div class="box" id="box-consumo">
                <div class="d-flex align-items-center px-lg-5 px-md-4 py-3">
                    {{-- <!-- Buscador cliente --> --}}
                    <div class="col-lg-4 col-md-4 d-inline-flex">
                        <button
                            class="btn btn-info disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                            type="button" data-bs-toggle="modal" data-bs-target=" #modalEditUser"
                            id="btn-create-user">Agregar Usuario</button>
                    </div>
                </div>
                <div class="px-3 pt-2 pb-3 border-top border-secondary">
                    <table class="table table-striped responsive" id="table-resources-it">
                        <thead>
                            <tr>
                                <th scope="col" class="col-1">Nº</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Rol</th>
                                <th scope="col" class="col-1">Estado</th>
                                <th scope="col" class="col-1">Editar</th>
                                <th scope="col" class="col-1">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{ $loop->index + 1 }}</th>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        @if ($user->state)
                                            <span class="d-none">Activo</span>
                                            <button type="button"
                                                class="btn btn-success fs-6 state-user-active disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                                                value="{{ $user->iduser }}">Activo</button>
                                        @elseif (!$user->state)
                                            <span class="d-none">Inactivo</span>
                                            <button type="button"
                                                class="btn btn-danger fs-6 state-user-inactive disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                                                value="{{ $user->iduser }}">Inactivo</button>
                                        @endif
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-warning disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                                            id="btn-edit-user" data-bs-toggle="modal" data-bs-target="#modalEditUser"
                                            value="{{ $user->iduser }}">Editar</button>
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-danger btn-delete-user disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                                            data-bs-toggle="modal" data-bs-target="#modal-succes-confirmation"
                                            value="{{ $user->iduser }}">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="modalEditUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="content-mod">
                <div class="modal-header bg-primary-custom">
                    <h5 class="modal-title text-white" id="staticBackdropLabel">Editar el tipo de cambio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-update-user" autocomplete="off">
                        @csrf
                        <input class="d-none" value="">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-address-card"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" placeholder="Nombres" aria-label="Nombres"
                                aria-describedby="basic-addon1" name="name">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-address-card"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" placeholder="Apellidos" aria-label="Apellidos"
                                aria-describedby="basic-addon1" name="lastname">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>
                            </div>
                            <input type="email" class="form-control" id="input-email" placeholder="Email" name="email"
                                aria-errormessage="Correo no valido">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-phone"></i>
                                </span>
                            </div>
                            <input type="tel" class="form-control" placeholder="Numero" aria-label="Numero"
                                pattern="[0-9]{3}-[0-9]{3}-[0-9]{3}" aria-describedby="basic-addon1" name="phone"
                                maxlength="11">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                            </div>
                            <input type="password" id="input-password" class="form-control" placeholder="Contraseña"
                                autocomplete="new-password" name="password">
                            <i class="fa-regular fa-eye-slash not-show-password"></i>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text h-100" id="basic-addon1">
                                    <i class="fa-solid fa-people-group"></i>
                                </span>
                            </div>
                            <select class="form-select form-select-sm py-2" id="select-rol" aria-label="form-select-rol"
                                name="rol" required>
                                <option value="" selected>Selecciona un rol</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Analista">Analista</option>
                                <option value="Visitante">Visitante</option>
                            </select>
                        </div>
                        <button class="d-none" id="btn-sumbit-user" type="submit"></button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-update-create-user">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    @include('response/status')
@endsection
<script>
    var users = @json($users)
</script>
@push('scripts')
    <script src="{{ asset('js/user/user-manage.js') }}"></script>
@endpush

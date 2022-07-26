@extends('app')
@section('title', 'Resumen de Servidores')
@section('content')
    <div class="content-wrapper">
        {{-- <!-- Content Header (Page header) --> --}}
        <section class="content-header text-center">
            <h1 class="h1-titulo">
                REPORTE - RESUMEN DE SERVIDORES
            </h1>
        </section>
        <section class="content">
            <div class="box" id="box-consumo">
                <div
                    class="d-flex flex-column flex-md-row justify-content-md-between align-items-center px-lg-5 px-md-4 py-3">
                    {{-- <!-- Buscador cliente --> --}}
                    <div class="col-lg-4 col-md-4 d-inline-flex pb-2 pb-sm-0">
                        <h6 id="h6-cliente" class="py-2 m-0 pe-2">Cliente:</h6>
                        <input id="input-buscar-cliente"
                            class="form-control form-control-sm ml-5 w-30 rounded-3 rounded-pill" type="text"
                            placeholder="  Buscar por cliente" aria-label="Search">
                    </div>
                    <div class="col-lg-5 col-md-5 d-inline-flex pb-2 pb-sm-0">
                        {{-- <!-- Buscador Hostname --> --}}
                        <h6 id="h6-buscador-hostname" class="py-2 m-0 pe-2">Hostname/VMware:</h6>
                        <input id="input-buscar-hostname" class="form-control form-control-sm ml-5 w-30 rounded-pill"
                            type="text" placeholder="  Buscar por VMware" aria-label="Search">
                    </div>
                </div>
                <div class="px-3 pt-2 pb-3">
                    <table class="table table-striped responsive" id="table-resources-it">
                        <thead>
                            <tr>
                                <th scope="col" class="col-1">Nº</th>
                                <th scope="col" class="col-3">Activo</th>
                                <th scope="col" class="col-1">ALP</th>
                                <th scope="col" class="col-2">Proyecto</th>
                                <th scope="col" class="col-2">Servidor</th>
                                <th scope="col" class="col-2">SOW</th>
                                <th scope="col" class="col-1">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servers as $server)
                                <tr>
                                    <th scope="row">{{ $loop->index + 1 }}</th>
                                    <td>{{ $server->active }}</td>
                                    <td>{{ $server->alp }}</td>
                                    <td>{{ $server->project_name }}</td>
                                    <td>{{ $server->server_name }}</td>
                                    <td>{{ $server->version === null ? 'N.A.' : $server->version . ' ' . $server->sow_name }}
                                    </td>
                                    <td>
                                        <a data-bs-toggle="modal" href="#modalEditServer" role="button"
                                            value="{{ $server->idserver }}">
                                            Ver Detalle
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
    <div class="modal fade" id="modalEditServer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content" id="content-mod">
                <div class="modal-header bg-primary-custom text-center">
                    <h5 class="modal-title text-white w-100" id="staticBackdropLabel">
                        <span id="server_title" class="fs-3">Server Name</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update_server" autocomplete="off">
                        <div class="row">
                            <div class="col-12 col-sm-6 px-4">
                                <div class="row py-1 my-2 border rounded">
                                    <div class="col-7">Nombre de Maquina</div>
                                    <div class="col-5">
                                        : <span id="machine_name">NAME 1</span>
                                    </div>
                                </div>
                                <div class="row py-1 my-2 border rounded">
                                    <div class="col-7">Hostname</div>
                                    <div class="col-5">
                                        : <span id="hostname">NAME 2</span>
                                    </div>
                                </div>
                                <div class="row py-1 my-2 border rounded">
                                    <div class="col-7">CPU</div>
                                    <div class="col-5">
                                        : <span id="cpu">CPU INFO</span>
                                    </div>
                                </div>
                                <div class="row py-1 my-2 border rounded">
                                    <div class="col-7">RAM</div>
                                    <div class="col-5">
                                        : <span id="ram">RAM INFO</span>
                                    </div>
                                </div>
                                <div class="row py-1 my-2 border rounded">
                                    <div class="col-7">Disco</div>
                                    <div class="col-5">
                                        : <span id="disk">DISK INFO</span>
                                    </div>
                                </div>
                                <div class="row py-1 my-2 border rounded">
                                    <div class="col-7">Servicio</div>
                                    <div class="col-5">
                                        : <span id="service">SERVICE INFO</span>
                                    </div>
                                </div>
                                <div class="row py-1 my-2 align-items-center">
                                    <div class="col-12 col-sm-4">SOW :</div>
                                    <div class="col-12 col-sm-4 recomendations">
                                        <div class="input-group input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-magnifying-glass pe-2"></i>
                                                <input class="border border-dark rounded" type="search"
                                                    placeholder="6.4.1 SOW - BRONCE" value="" name="sow"
                                                    style="padding-left: .5rem">
                                                <input type="hidden" name="sow_id" value="">
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="row py-1 my-2 border border-dark rounded">
                                    <div class="form-check form-switch d-flex justify-content-center align-items-center">
                                        <input class="form-check-input me-3 py-2" style="transform: scale(1.2)"
                                            type="checkbox" id="switchBackup" name="backup">
                                        <label class="form-check-label py-2" for="switchBackup">¿Desea asignar
                                            servicio de Backup?</label>
                                    </div>
                                </div>
                                <div class="row py-1 my-2 border border-dark rounded">
                                    <div class="form-check form-switch d-flex justify-content-center align-items-center">
                                        <input class="custom-control-input form-check-input  me-3 py-2"
                                            style="transform: scale(1.2)" type="checkbox" id="switchAditional"
                                            name="additional_service">
                                        <label class="custom-control-label form-check-label py-2" for="switchAditional">
                                            ¿Desea asignar servicio Adicionales?</label>
                                    </div>
                                </div>
                                <div class="row py-1 my-2 border border-dark rounded">
                                    <div class="form-check form-switch d-flex justify-content-center align-items-center">
                                        <input class="custom-control-input form-check-input me-3 py-2 col-6" disabled
                                            style="transform: scale(1.2)" type="checkbox" id="switchLicenseWindows"
                                            name="windows_license">
                                        <label class="custom-control-label form-check-label py-2 col-6"
                                            for="switchLicenseWindows">
                                            Licencia Windows
                                        </label>
                                    </div>
                                    <div class="form-check form-switch d-flex justify-content-center align-items-center">
                                        <input class="custom-control-input form-check-input me-3 py-2 col-6" disabled
                                            style="transform: scale(1.2)" type="checkbox" id="switchAntivirus"
                                            name="antivirus">
                                        <label class="custom-control-label form-check-label py-2 col-6"
                                            for="switchAntivirus">
                                            Antivirus
                                        </label>
                                    </div>
                                    <div class="form-check form-switch d-flex justify-content-center align-items-center">
                                        <input class="custom-control-input form-check-input me-3 py-2 col-6" disabled
                                            style="transform: scale(1.2)" type="checkbox" id="switchVCPU"
                                            name="vcpu">
                                        <label class="custom-control-label form-check-label py-2 col-6" for="switchVCPU">
                                            Vcpu
                                        </label>
                                    </div>
                                    <div class="form-check form-switch d-flex justify-content-center align-items-center">
                                        <input class="custom-control-input form-check-input me-3 py-2 col-6" disabled
                                            style="transform: scale(1.2)" type="checkbox" id="switchLicenseLinux"
                                            name="linux_license">
                                        <label class="custom-control-label form-check-label py-2 col-6"
                                            for="switchLicenseLinux">
                                            Licencia Linux
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 px-4">
                                <div class="row py-1 my-2 border border-dark rounded">
                                    <div class="form-check form-switch d-flex justify-content-center align-items-center">
                                        <input class="custom-control-input form-check-input  me-3 py-2"
                                            style="transform: scale(1.2)" type="checkbox" id="switchLicenseServer"
                                            name="additional_spla_service">
                                        <label class="custom-control-label form-check-label py-2"
                                            for="switchLicenseServer">
                                            ¿Desea asignar Licencias al Servidor?
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <span><b>Licencia SPLA</b></span>
                                    </div>
                                    <div class="col-12">
                                        <fieldset class="border border-dark p-2">
                                            <legend class="w-auto float-none p-1 fs-5">Sistema Operativo</legend>
                                            <div class="row col-12 pb-2 align-items-center">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Tipo :</label>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <select class="form-control" name="SO" disabled>
                                                        <option value="" selected>Seleccionar</option>
                                                        @foreach ($licenses as $license)
                                                            @if ($license->type == 'SO')
                                                                <option value="{{ $license->idspla }}">
                                                                    {{ $license->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row col-12 align-items-center">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">% reducido :</label>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <input type="text" class="form-control" name="reduction_so"
                                                        disabled value="0">
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-12">
                                        <fieldset class="border border-dark p-2">
                                            <legend class="w-auto float-none p-1 fs-5">SQL Server</legend>
                                            <div class="row col-12 pb-2 align-items-center">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Tipo :</label>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <select class="form-control" name="SQL Server" disabled>
                                                        <option value="" selected>Seleccionar</option>
                                                        @foreach ($licenses as $license)
                                                            @if ($license->type == 'SQL Server')
                                                                <option value="{{ $license->idspla }}">
                                                                    {{ $license->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row col-12 align-items-center">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">% reducido :</label>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <input type="text" class="form-control" name="reduction_sql"
                                                        disabled value="0">
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-12">
                                        <fieldset class="border border-dark p-2">
                                            <legend class="w-auto float-none p-1 fs-5">Remote Desktop</legend>
                                            <div class="row col-12 pb-2 align-items-center">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Tipo :</label>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <select class="form-control" name="Remote Desktop" disabled>
                                                        <option value="" selected>Seleccionar</option>
                                                        @foreach ($licenses as $license)
                                                            @if ($license->type == 'Remote Desktop')
                                                                <option value="{{ $license->idspla }}">
                                                                    {{ $license->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row col-12 align-items-center">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">% reducido :</label>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <input type="text" class="form-control" name="reduction_remote"
                                                        disabled value="0">
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-12">
                                        <fieldset class="border border-dark p-2">
                                            <legend class="w-auto float-none p-1 fs-5">Office</legend>
                                            <div class="row col-12 pb-2 align-items-center">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Tipo :</label>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <select class="form-control" name="Office" disabled>
                                                        <option value="" selected>Seleccionar</option>
                                                        @foreach ($licenses as $license)
                                                            @if ($license->type == 'Office')
                                                                <option value="{{ $license->idspla }}">
                                                                    {{ $license->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row col-12 align-items-center">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">% reducido :</label>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <input type="text" class="form-control" name="reduction_office"
                                                        disabled value="0">
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="submit_form" class="visually-hidden"></button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close">
                        Cerrar
                    </button>
                    <button type="button"
                        class="btn btn-primary disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                        id="btn-update-create-server">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @csrf
    @include('response.status')
@endsection
<script>
    let sows = @json($sows);
    let licenses = @json($licenses);
    let servers = @json($servers);
    let resources = @json($resources);
    let assign_services = @json($assign_services);
    let assign_splas = @json($assign_splas);
</script>
@push('scripts')
    <script src="{{ asset('js/report/server_summary.js') }}"></script>
@endpush

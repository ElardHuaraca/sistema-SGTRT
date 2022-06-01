@extends('app') @section('title', 'SOW') @section('content')
<div class="content-wrapper">
    <section class="content-header text-center">
        <h1 class="h1-titulo">
            SOWS
        </h1>
    </section>
    <section class="content">
        <div class="box" id="box-consumo">
            <div class="d-flex align-items-center px-lg-3 px-md-4 py-3">
                <div class="col-lg-4 col-md-4 d-inline-flex">
                    <button
                        class="btn btn-info disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                        type="button" data-bs-toggle="modal" data-bs-target="#modalCreateEditSow"
                        id="btn-update-create-project">Agregar nuevo SOW</button>
                </div>
            </div>
            <div class="px-3 pt-2 pb-3 border-top border-secondary">
                <table class="table table-striped responsive" id="table-resources-it">
                    <thead>
                        <tr>
                            <th scope="col" class="col-1">Nº</th>
                            <th scope="col" class="col-1">Version</th>
                            <th scope="col" class="col-2">Nombre</th>
                            <th scope="col" class="col-3">Fecha creación</th>
                            <th scope="col" class="col-3">Última modificación</th>
                            <th scope="col" class="col-1">Editar</th>
                            <th scope="col" class="col-1">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sows as $sow)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $sow->version }}</td>
                                <td>{{ $sow->name . ' ' . $sow->type . ' ' . $sow->version }}</td>
                                <td>{{ $sow->created_at }}</td>
                                <td>{{ $sow->updated_at }}</td>
                                <td>
                                    <button class="btn btn-warning" id="btn-edit-sow" data-bs-toggle="modal"
                                        data-bs-target="#modalCreateEditSow" value="{{ $sow->idsow }}">Editar</button>
                                </td>
                                <td>
                                    @if ($sow->is_deleted)
                                        <button
                                            class="btn btn-danger btn-status-sow disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                                            value="{{ $sow->idsow }}">Inactivo</button>
                                    @else
                                        <button
                                            class="btn btn-success btn-status-sow disabled {{ Auth::user()->role == 'Visitante' ? '' : 'remove-disable' }}"
                                            value="{{ $sow->idsow }}">Activo</button>
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
<div class="modal fade" id="modalCreateEditSow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" id="content-mod">
            <div class="modal-header bg-primary-custom text-center">
                <h5 class="modal-title text-white w-100" id="staticBackdropLabel">Registrar SOW</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card text-center">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="sow-list">
                            <li class="nav-item">
                                <a href="#bronce" class="nav-link active">BRONCE</a>
                            </li>
                            <li class="nav-item">
                                <a href="#silver" class="nav-link disabled">SILVER</a>
                            </li>
                            <li class="nav-item">
                                <a href="#gold" class="nav-link disabled">GOLD</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body border-start border-end border-bottom border-1">
                    <div class="tab-content">
                        <div class="tab-pane active" id="bronce" role="tabpanel">
                            <form id="form-bronce">
                                <table class="table table-bordered border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="bg-black-custom col-9 align-middle">
                                                <span>Modelo Cloud - Precio por VM</span>
                                            </th>
                                            <th scope="col" class="bg-light col-2">Costo Mensual Unidad Base
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Cantidad de VM</td>
                                            <td> </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Cantidad de vCPU</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_cpu">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">RAM (GB)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_ram">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Disco (GB)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_hdd_mechanical">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">MO CLOUD + SW GENESYS</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_mo_clo_sw_ge">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">MO COT (Operaciones + Monitoreo)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_mo_cot">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">COT Licencia Monitoreo (Nagios)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_cot_monitoring">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="bg-black-custom col-9 align-middle">
                                                <span>Adicionales</span>
                                            </th>
                                            <th scope="col" class="bg-light col-2">Costo Mensual Unidad Base
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Antivirus</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_antivirus">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Licencia Windows por CPU</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_win_license_cpu">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Licencia Windows por RAM</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_win_license_ram">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Licencia Linux por VMs</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_linux_license">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="bg-black-custom col-9 align-middle">
                                                <span>Servicio de Backups</span>
                                            </th>
                                            <th scope="col" class="bg-light col-2">Costo Mensual Req. Cliente
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Backup de la Base de Datos</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="cost_backup_db">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="bg-black-custom col-9 align-middle">
                                                <span>Verion de sow</span>
                                            </th>
                                            <th scope="col" class="bg-light col-2 position-relative">
                                                <input type="text" placeholder="Version" id="version_sow"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    name="version">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="bg-black-custom col-9 align-middle">
                                                <span>Nombre de sow</span>
                                            </th>
                                            <th scope="col" class="bg-light col-2 position-relative">
                                                <input type="text" placeholder="Nombre" id="name_sow"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    name="name">
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                                <input class="d-none" type="submit" id="btn-submit-bronce">
                            </form>
                        </div>
                        <div class="tab-pane" id="silver" role="tabpanel" aria-labelledby="history-tab">
                            <form id="form-silver">
                                <table class="table table-bordered border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="bg-black-custom col-9 align-middle">
                                                <span>Modelo Cloud - Precio por VM</span>
                                            </th>
                                            <th scope="col" class="bg-light col-2 text-center">Unidades (1GB)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Cantidad de VM</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Cantidad de vCPU</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_cpu">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">RAM (GB)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_ram">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Disco SAS (GB)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_hdd_mechanical">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Disco SSD (GB)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_hdd_solid">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">MO CLOUD + SW GENESYS + Ceccom</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_mo_clo_sw_ge">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">MO COT (Operaciones + Monitoreo)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_mo_cot">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">COT Licencia Monitoreo (CA)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_cot_monitoring">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Licencia Hypervisor VSPP</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_license_vssp">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="bg-black-custom col-9 align-middle">
                                                <span>Adicionales</span>
                                            </th>
                                            <th scope="col" class="bg-light col-2">Costo Mensual Unidad Base
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Antivirus</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_antivirus">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Licencia Windows por CPU</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_win_license_cpu">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Licencia Windows por RAM</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_win_license_ram">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Licencia Linux por VMs</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_linux_license">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="bg-black-custom col-9 align-middle">
                                                <span>Servicio de Backups</span>
                                            </th>
                                            <th scope="col" class="bg-light col-2">Costo Mensual Req. Cliente
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Backup de la Base de Datos</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="cost_backup_db">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <input class="d-none" type="submit" id="btn-submit-silver">
                            </form>
                        </div>
                        <div class="tab-pane" id="gold" role="tabpanel" aria-labelledby="history-tab">
                            <form id="form-gold">
                                <table class="table table-bordered border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="bg-black-custom col-9 align-middle">
                                                <span>Modelo Cloud - Precio por VM</span>
                                            </th>
                                            <th scope="col" class="bg-light col-2 text-center">Unidades (1GB)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Cantidad de VM</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Cantidad de vCPU</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_cpu">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">RAM (GB)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_ram">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Disco SAS (GB)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_hdd_mechanical">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Disco SSD (GB)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_hdd_solid">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">MO CLOUD + SW GENESYS</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_mo_clo_sw_ge">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">MO COT (Operaciones + Monitoreo)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_mo_cot">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">COT Licencia Monitoreo (CA)</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_cot_monitoring">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Licencia Hypervisor VSPP</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_license_vssp">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Licencia Hypervisor SRM VSPP</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_license_vssp_srm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-blue-custom fw-bold">Costo por Enlace</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1 pe-2"
                                                    placeholder="Precio" name="cost_link">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="bg-black-custom col-9 align-middle">
                                                <span>Adicionales</span>
                                            </th>
                                            <th scope="col" class="bg-light col-2">Costo Mensual Unidad Base
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Antivirus</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_antivirus">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Licencia Windows por CPU</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_win_license_cpu">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Licencia Windows por RAM</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_win_license_ram">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-green-custom">Licencia Linux por VMs</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="add_cost_linux_license">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="bg-black-custom col-9 align-middle">
                                                <span>Servicio de Backups</span>
                                            </th>
                                            <th scope="col" class="bg-light col-2">Costo Mensual Req. Cliente
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Backup de la Base de Datos</td>
                                            <td class="bd-light position-relative">
                                                <i class="fa-solid fa-dollar-sign position-absolute start-0 py-1 ps-2"
                                                    style="z-index: 1000"></i>
                                                <input type="text"
                                                    class="h-100 w-100 position-absolute top-0 start-0 text-end pb-1"
                                                    placeholder="Precio" name="cost_backup_db">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <input class="d-none" type="submit" id="btn-submit-gold">
                            </form>
                        </div>
                        @csrf
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btn-next">Siguiente</button>
            </div>
        </div>
    </div>
</div>
@include('response.status') @endsection
<script>
    var sows = @json($sows);
</script>
@push('scripts')
<script src="{{ asset('js/maintenance/sow.js') }}"></script>
@endpush()

<div>
    <button id="btn-succes-loading" data-bs-toggle="modal" data-bs-target="#modal-succes-loading" style="display: none">
    </button>
    <div class="modal fade" id="modal-succes-loading" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content h-50" id="content-mod">
                <div class="modal-header bg-white">
                    <button type="button" class="btn-close btn-close-loading" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="progress mt-3 d-none" id="progress-bar">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 5%">5%</div>
                    </div>
                </div>
                <div class="d-none">
                    <button type="button" data-bs-dismiss="modal" id="btn-close-loading">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <button id="btn-succes" data-bs-toggle="modal" data-bs-target="#modal-succes" style="display: none">
    </button>
    <div class="modal fade" id="modal-succes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content h-50" id="content-mod">
                <div class="modal-header justify-content-center">
                    <div class="icon-box">
                        <i class="fa-solid fa-check" style="font-size: 3.5rem"></i>
                    </div>
                </div>
                <div class="modal-body text-center py-5">
                    <h3>Cambio realizado con exito</h3>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="btn-close-succes">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <button id="btn-succes-error" data-bs-toggle="modal" data-bs-target="#modal-succes-error" style="display: none">
    </button>
    <div class="modal fade" id="modal-succes-error" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content h-50" id="content-mod">
                <div class="modal-header justify-content-center">
                    <div class="icon-box">
                        <i class="fa-solid fa-x" style="font-size: 3.5rem"></i>
                    </div>
                </div>
                <div class="modal-body text-center py-5">
                    <h4 id="error_body">Error al realizar el cambio</h4>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="btn-close-error">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <button id="btn-succes-confirmation" data-bs-toggle="modal" data-bs-target="#modal-succes-confirmation"
        style="display: none">
    </button>
    <div class="modal fade" id="modal-succes-confirmation" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content h-50 panel-warning" id="content-mod">
                <div class="modal-body text-center py-5">
                    <h4 id="title_action_perform">¿Esta seguro que desea realizar esta accion?</h4>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal"
                        id="btn-close">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="delete-data">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>

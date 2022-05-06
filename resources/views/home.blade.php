@extends('app')
@section('title', 'Inicio')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
@endpush
@section('content')
    {{-- <!-- Content Wrapper. Contains page content --> --}}
    <div class="content-wrapper">
        {{-- <!-- Content Header (Page header) --> --}}
        <section class="content-header text-center">

            <h1 class="h1-titulo">
                SGTRT
            </h1>

        </section>
        <section class="content me-n-2">

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title" id="TC-INICIO">Tipo de cambio actual:
                        <b><i id="valor-cambio">{{ number_format($tChange->valor, 2) }}</i></b>
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-bs-toggle="collapse"
                            data-bs-target="#editarCambio" aria-expanded="true" aria-controls="editarCambio">
                            <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="show" id="editarCambio">
                    <div class="box-body p-3">
                        <button class="btn btn-warning btnEditarCambio" idCambio="{{ $tChange->idtipo }}"
                            data-bs-toggle="modal" data-bs-target=" #modalEditarCambio"></i>
                            <b>Editar el tipo decambio</b>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="modalEditarCambio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="content-mod">
                <div class="modal-header bg-primary-custom">
                    <h5 class="modal-title text-white" id="staticBackdropLabel">Editar el tipo de cambio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-update-tchange">
                        @csrf
                        <div class="input-container">
                            <span class="input-group-addon py-1">
                                <i class="fa fa-regular fa-money-bill-1 icon"></i>
                            </span>
                            <input class="input-field newtChange" type="number"
                                value="{{ number_format($tChange->valor, 2) }}" name="valor" step="0.05">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="updatetChange">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    @include('response/status')
@endsection

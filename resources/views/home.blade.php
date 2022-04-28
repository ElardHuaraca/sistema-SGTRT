@extends('app')
@section('title', 'Inicio')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header text-center">

            <h1 class="h1-titulo">
                SGTRT
            </h1>

        </section>
    </div>
@endsection

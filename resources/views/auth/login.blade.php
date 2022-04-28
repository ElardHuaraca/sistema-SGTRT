@extends('app')
@section('title', 'Login')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush
@section('content')
    <div class="image_backgrund"></div>
    <div class="container content">
        <div class="row h-100 align-items-center justify-content-center">
            <div
                class="col col-xl-3 col-lg-4 col-md-5 col-sm-3 text-center py-4 px-5 shadow bg-white rounded border border-light mb-10">
                <p class="h4"><strong>SGRT</strong></p>
                <form method="POST" action="login">
                    @csrf
                    <div class="mb-3 inner-addon right-addon">
                        <i class="fa fa-user"></i>
                        <input type="text" class="form-control" name="usuario" id="exampleInputEmail1"
                            placeholder="Usuario" required>
                    </div>
                    <div class="mb-3 inner-addon right-addon">
                        <i class="fa fa-lock"></i>
                        <input type="password" class="form-control" name="password" id="exampleInputPassword1"
                            placeholder="Contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-login">Ingresar</button>
                </form>
                @if ($errors->has('user'))
                    <div class="col-xl-12 bg-danger mt-2 px-1 d-flex align-items-center error-login justify-content-center">
                        <span class="text-white">{{ $errors->first('user') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

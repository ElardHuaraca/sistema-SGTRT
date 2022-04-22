@extends('app')
@section('title', 'Login')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="https://kit.fontawesome.com/48fe976184.js" crossorigin="anonymous"></script>
@endpush
@section('content')
    <div class="image_backgrund"></div>
    <div class="container content">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-lg-3 col-md-5 col-sm-3 text-center py-4 px-5 shadow bg-white rounded border border-light mb-10">
                <p class="h4"><strong>SGRT</strong></p>
                <form method="POST" action="{{ route('login.authenticate') }}">
                    @csrf
                    @if ($errors->has('user'))
                        <span class="text-danger">{{ $errors->first('user') }}</span>
                    @endif
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
            </div>
        </div>
    </div>
@endsection

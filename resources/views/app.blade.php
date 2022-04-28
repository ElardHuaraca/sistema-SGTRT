<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/icono.ico') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @auth
        <link rel="stylesheet" href="{{ asset('css/navigation.css') }}">
    @endauth
    <script src="https://kit.fontawesome.com/48fe976184.js" crossorigin="anonymous"></script>
    @stack('styles')
</head>

<body>
    @auth
        <header class="d-lg-block">
            <nav class="collapse d-lg-block vw-10 sidebar collapse navigation sidebar-mini fixed main-sidebar">
                <div class="container-fluid py-1 logo" style="background: rgb(240,238,238)">
                    <a href="{{ route('home') }}" class="navbar-brand">
                        <img src="{{ asset('/images/logo-largo.png') }}" alt="logo_canvia" class="logo"
                            loading="eager" />
                    </a>
                </div>
                <div class="container-fluid py-1 logo-mini" style="background: rgb(240,238,238)">
                    <a href="{{ route('home') }}" class="navbar-brand">
                        <img src="{{ asset('/images/logo-mini.png') }}" alt="logo_canvia" class="logo-mini"
                            loading="lazy" />
                    </a>
                </div>
                <div class="sidebar-menu d-block" data-widget="tree">
                    @if (Auth::user()->rol == 'Administrador' || Auth::user()->rol == 'Analista')
                        <div class="list-group list-group-flush border-bottom border-white">
                            <a class="list-group-item list-group-item-action collapsed bg-transparent py-2 text-white-navigation inner-addon"
                                href="/">
                                <i class="fas fa-solid fa-house me-3"></i>
                                <span>Inicio</span>
                            </a>
                        </div>
                        <div class="list-group list-group-flush border-bottom border-white btn-nav-accordion">
                            <a class="list-group-item list-group-item-action collapsed bg-transparent py-2 text-white-navigation inner-addon right-addon"
                                data-bs-toggle="collapse" href="#collapse_first" aria-expanded="false"
                                aria-controls="collapse_first">
                                <i class="fas fa-solid fa-file-lines me-3"></i><span>Reportes</span>
                                <i class="fa fa-solid fa-angle-down text-white-navigation"></i>
                            </a>

                            <ul id="collapse_first" class="collapse list-group list-group-flush treeview-menu">
                                <li class="list-group-item py-1 bg-transparent">
                                    <a href="" class="text-reset">
                                        <i class="fa-solid fa-share"></i>
                                        <span>Consumo Recursos TI</span>
                                    </a>
                                </li>
                                <li class="list-group-item py-1 bg-transparent">
                                    <a href="" class="text-reset">
                                        <i class="fa-solid fa-share"></i>
                                        <span>Tarifario TI</span>
                                    </a>
                                </li>
                                <li class="list-group-item py-1 bg-transparent">
                                    <a href="" class="text-reset">
                                        <i class="fa-solid fa-share"></i>
                                        <span>Resumen de Servidores</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                    @if (Auth::user()->rol == 'Administrador')
                        <div class="list-group list-group-flush inner-addon right-addon border-bottom border-white">
                            <a class="list-group-item list-group-item-action collapsed bg-transparent py-2 text-white-navigation"
                                data-bs-toggle="collapse" href="#collapse_second" aria-expanded="false"
                                aria-controls="collapse_second">
                                <i class="fas fa-solid fa-users me-3"></i><span>Usuarios</span>
                                <i class="fa fa-solid fa-angle-down text-white-navigation"></i>
                            </a>
                            <ul id="collapse_second" class="collapse list-group list-group-flush treeview-menu">
                                <li class="list-group-item py-1 bg-transparent">
                                    <a href="" class="text-reset">
                                        <i class="fa-solid fa-share"></i>
                                        <span>Gestionar Usuario</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="list-group list-group-flush inner-addon right-addon border-bottom border-white">
                            <a class="list-group-item list-group-item-action collapsed bg-transparent py-2 text-white-navigation"
                                data-bs-toggle="collapse" href="#collapse_three" aria-expanded="false"
                                aria-controls="collapse_three">
                                <i class="fas fa-solid fa-gears me-3"></i><span>Mantenimiento</span>
                                <i class="fa fa-solid fa-angle-down text-white-navigation"></i>
                            </a>
                            <ul id="collapse_three" class="collapse list-group list-group-flush treeview-menu">
                                <li class="list-group-item py-1 bg-transparent treeview">
                                    <a href="" class="text-reset">
                                        <i class="fa-solid fa-share"></i>
                                        <span>SOW</span>
                                    </a>
                                </li>
                                <li class="list-group-item py-1 bg-transparent">
                                    <a href="" class="text-reset">
                                        <i class="fa-solid fa-share"></i>
                                        <span>Proyectos</span>
                                    </a>
                                </li>
                                <li class="list-group-item py-1 bg-transparent">
                                    <a href="" class="text-reset">
                                        <i class="fa-solid fa-share"></i>
                                        <span>Costo Mantenimiento</span>
                                    </a>
                                </li>
                                <li class="list-group-item py-1 bg-transparent">
                                    <a href="" class="text-reset">
                                        <i class="fa-solid fa-share"></i>
                                        <span>Licencia SPLA</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </nav>
            <nav class="navbar navbar-expand-lg navbar-light navigation-2">
                <a href="#" class="sidebar-toggle ms-15" role="button" data-bs-toggle="push-menu">
                    <i class="fa-solid fa-bars text-white d-none d-sm-none d-lg-block"></i>
                </a>
                <div class="container-fluid h-100 px-0">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <ul class="navbar-nav ms-auto d-flex flex-row h-100 ">
                        <li class="nav-item dropdown h-100 pe-2">
                            <a class="dropdown-toggle d-flex align-items-center text-white-navigation h-100"
                                data-bs-toggle="dropdown" role="button" aria-expanded="false" id="options-profile ">
                                <img src="{{ asset('images/user-anonimo.png') }}"
                                    class="rounded-circle mx-2 bg-transparent" height="30" width="30" alt="profile_image">
                                <span class="d-none d-sm-block">{{ Auth::user()->nombres ?: 'username' }}
                                    {{ Auth::user()->apellidos ?: 'last-name' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="options-profile">
                                <li><a href="{{ route('logout') }}" class="dropdown-item">Cerrar Sesion</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
    @endauth
    <div class="container main">
        @yield('content')
    </div>
</body>
<script src="{{ asset('js/app.js') }}"></script>
@auth
    <script src="{{ asset('js/navigation.js') }}"></script>
@endauth

</html>

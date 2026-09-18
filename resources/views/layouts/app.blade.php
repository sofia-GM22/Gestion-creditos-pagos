<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('titulo', 'Sistema de Gestión de Créditos')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/app-custom.css') }}"
    >
</head>

<body class="app-body">

    <nav class="navbar navbar-expand-lg navbar-dark app-navbar mb-4">
        <div class="container">

            <a class="navbar-brand" href="{{ route('home') }}">
                Sistema de Gestión de Créditos
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Mostrar navegación"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">

                <ul class="navbar-nav me-auto">

                    @if (auth()->user()->esCliente())

                        {{-- MENÚ DEL CLIENTE --}}

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                                href="{{ route('home') }}"
                            >
                                Inicio
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('creditos.mios') ? 'active' : '' }}"
                                href="{{ route('creditos.mios') }}"
                            >
                                Mis Créditos
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('pagos.mios') ? 'active' : '' }}"
                                href="{{ route('pagos.mios') }}"
                            >
                                Mis Pagos
                            </a>
                        </li>

                    @else

                        {{-- MENÚ ADMINISTRADOR / EMPLEADO --}}

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                                href="{{ route('home') }}"
                            >
                                Inicio
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('clientes.index', 'clientes.create', 'clientes.show', 'clientes.edit') ? 'active' : '' }}"
                                href="{{ route('clientes.index') }}"
                            >
                                Clientes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('clientes.por-estado-credito') ? 'active' : '' }}"
                                href="{{ route('clientes.por-estado-credito') }}"
                            >
                                Clientes por estado
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('creditos.*') ? 'active' : '' }}"
                                href="{{ route('creditos.index') }}"
                            >
                                Créditos
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ request()->routeIs('pagos.*') ? 'active' : '' }}"
                                href="{{ route('pagos.index') }}"
                            >
                                Pagos
                            </a>
                        </li>

                    @endif

                </ul>

                {{-- USUARIO + CERRAR SESIÓN --}}

                <ul class="navbar-nav align-items-center">

                    <li class="nav-item">
                        <span class="navbar-text text-light me-3 app-user">
                            {{ auth()->user()->username }}
                            ({{ auth()->user()->role->nombre }})
                        </span>
                    </li>

                    <li class="nav-item">
                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-outline-light btn-sm"
                            >
                                Cerrar sesión
                            </button>
                        </form>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    {{-- CONTENIDO PRINCIPAL --}}

    <div class="container pb-5 app-main">

        {{-- Mensaje de éxito --}}
        @if (session('exito'))
            <div class="alert alert-success">
                {{ session('exito') }}
            </div>
        @endif

        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>
</html>
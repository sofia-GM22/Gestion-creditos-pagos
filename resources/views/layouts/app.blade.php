<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        @yield('titulo', 'Sistema de Gestión de Créditos')
    </title>

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

<nav class="navbar navbar-expand-lg navbar-dark app-navbar">

    <div class="container">

        <a
            class="navbar-brand"
            href="{{ route('home') }}"
        >
            Sistema de Gestión de Créditos
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#appNavbar"
            aria-controls="appNavbar"
            aria-expanded="false"
            aria-label="Mostrar navegación"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div
            class="collapse navbar-collapse"
            id="appNavbar"
        >

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a
                        href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                    >
                        Inicio
                    </a>
                </li>

                @if (auth()->user()->esCliente())

                    <li class="nav-item">
                        <a
                            href="{{ route('creditos.mios') }}"
                            class="nav-link {{ request()->routeIs('creditos.mios') ? 'active' : '' }}"
                        >
                            Mis Créditos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            href="{{ route('pagos.mios') }}"
                            class="nav-link {{ request()->routeIs('pagos.mios') ? 'active' : '' }}"
                        >
                            Mis Pagos
                        </a>
                    </li>

                @else

                    <li class="nav-item">
                        <a
                            href="{{ route('clientes.index') }}"
                            class="nav-link {{ request()->routeIs('clientes.*') && !request()->routeIs('clientes.por-estado-credito') ? 'active' : '' }}"
                        >
                            Clientes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            href="{{ route('clientes.por-estado-credito') }}"
                            class="nav-link {{ request()->routeIs('clientes.por-estado-credito') ? 'active' : '' }}"
                        >
                            Clientes por estado
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            href="{{ route('creditos.index') }}"
                            class="nav-link {{ request()->routeIs('creditos.*') ? 'active' : '' }}"
                        >
                            Créditos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            href="{{ route('pagos.index') }}"
                            class="nav-link {{ request()->routeIs('pagos.*') ? 'active' : '' }}"
                        >
                            Pagos
                        </a>
                    </li>

                    @if (auth()->user()->esAdministrador())

                        <li class="nav-item">
                            <a
                                href="{{ route('empleados.index') }}"
                                class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}"
                            >
                                Empleados
                            </a>
                        </li>

                    @endif

                @endif

            </ul>

            <ul class="navbar-nav align-items-center gap-lg-2">

                <li class="nav-item">
                    <span class="app-user">
                        {{ auth()->user()->username }}
                        ({{ auth()->user()->role->nombre }})
                    </span>
                </li>

                <li class="nav-item">

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="m-0"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-sm btn-outline-light"
                        >
                            Cerrar sesión
                        </button>
                    </form>

                </li>

            </ul>

        </div>

    </div>

</nav>

<main class="container app-main">

    @if (session('success'))

        <div class="alert alert-success app-flash">
            {{ session('success') }}
        </div>

    @endif

    @yield('content')

</main>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('[data-dui]').forEach(function (input) {

        input.addEventListener('input', function () {

            let value = this.value.replace(/\D/g, '').slice(0, 9);

            if (value.length > 8) {
                value = value.slice(0, 8) + '-' + value.slice(8);
            }

            this.value = value;
        });

    });


    document.querySelectorAll('[data-telefono-digits]').forEach(function (input) {

        input.addEventListener('input', function () {

            this.value = this.value
                .replace(/\D/g, '')
                .slice(0, 8);

        });

    });


    document.querySelectorAll('[data-cliente-form]').forEach(function (form) {

        form.addEventListener('submit', function (event) {

            let valido = true;

            const dui = form.querySelector('[data-dui]');
            const telefono = form.querySelector('[data-telefono-digits]');

            if (dui) {

                const duiError = form.querySelector('[data-dui-error]');

                if (!/^\d{8}-\d$/.test(dui.value)) {

                    valido = false;

                    dui.classList.add('is-invalid');

                    if (duiError) {
                        duiError.textContent =
                            'El DUI debe tener 9 dígitos y formato 12345678-9.';
                    }

                } else {

                    dui.classList.remove('is-invalid');

                    if (duiError) {
                        duiError.textContent = '';
                    }

                }
            }


            if (telefono) {

                const telefonoError =
                    form.querySelector('[data-telefono-error]');

                if (!/^\d{8}$/.test(telefono.value)) {

                    valido = false;

                    telefono.classList.add('is-invalid');

                    if (telefonoError) {
                        telefonoError.textContent =
                            'El teléfono debe contener exactamente 8 dígitos.';
                    }

                } else {

                    telefono.classList.remove('is-invalid');

                    if (telefonoError) {
                        telefonoError.textContent = '';
                    }

                }
            }


            if (!valido) {
                event.preventDefault();
            }

        });

    });

});


window.addEventListener('pageshow', function (event) {

    if (event.persisted) {
        window.location.reload();
    }

});
</script>

</body>
</html>
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

<body class="app-body"><nav class="navbar navbar-expand-lg navbar-dark app-navbar">

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

    <main class="container pb-5 app-main">

        {{-- Mensaje de éxito --}}

        @if (session('exito'))
            <div
                class="alert alert-success app-flash"
                role="alert"
            >
                {{ session('exito') }}
            </div>
        @endif

        @yield('content')

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /*
             * ============================
             * FORMATO DEL DUI
             * ============================
             */

            document.querySelectorAll('[data-dui]').forEach((input) => {

                const formatearDui = () => {

                    const valor = input.value
                        .replace(/\D/g, '')
                        .slice(0, 9);

                    if (valor.length <= 8) {
                        input.value = valor;
                        return;
                    }

                    input.value =
                        valor.slice(0, 8) + '-' + valor.slice(8);
                };

                input.addEventListener(
                    'input',
                    formatearDui
                );

                formatearDui();
            });


            /*
             * ============================
             * SOLO NÚMEROS EN TELÉFONO
             * ============================
             */

            document
                .querySelectorAll('[data-telefono-digits]')
                .forEach((input) => {

                    const soloNumeros = () => {

                        input.value = input.value
                            .replace(/\D/g, '');
                    };

                    input.addEventListener(
                        'input',
                        soloNumeros
                    );

                    soloNumeros();
                });


            /*
             * ============================
             * VALIDACIÓN DEL CLIENTE
             * ============================
             */

            document
                .querySelectorAll('[data-cliente-form]')
                .forEach((form) => {

                    form.querySelectorAll('input')
                        .forEach((input) => {

                            input.addEventListener(
                                'input',
                                () => limpiarError(input)
                            );

                        });


                    form.addEventListener(
                        'submit',
                        (event) => {

                            let formularioValido = true;
                            let primerError = null;

                            limpiarErrores(form);


                            const nombres =
                                form.querySelector(
                                    '[name="nombres"]'
                                );

                            const apellidos =
                                form.querySelector(
                                    '[name="apellidos"]'
                                );

                            const dui =
                                form.querySelector(
                                    '[name="documento_identidad"]'
                                );

                            const telefono =
                                form.querySelector(
                                    '[name="telefono"]'
                                );

                            const correo =
                                form.querySelector(
                                    '[name="correo"]'
                                );


                            /*
                             * NOMBRES
                             */

                            if (!nombres.value.trim()) {

                                mostrarError(
                                    nombres,
                                    'El nombre del cliente es obligatorio.'
                                );

                                formularioValido = false;
                                primerError ??= nombres;
                            }


                            /*
                             * APELLIDOS
                             */

                            if (!apellidos.value.trim()) {

                                mostrarError(
                                    apellidos,
                                    'El apellido del cliente es obligatorio.'
                                );

                                formularioValido = false;
                                primerError ??= apellidos;
                            }


                            /*
                             * DUI
                             */

                            if (!/^\d{8}-\d$/.test(dui.value)) {

                                mostrarError(
                                    dui,
                                    'El DUI debe tener el formato 12345678-9.'
                                );

                                formularioValido = false;
                                primerError ??= dui;
                            }


                            /*
                             * TELÉFONO
                             */

                            if (
    telefono.value.trim() &&
    !/^\d{8}$/.test(telefono.value)
) {
    mostrarError(
        telefono,
        'El teléfono debe tener exactamente 8 dígitos.'
    );

    formularioValido = false;
    primerError ??= telefono;
}


                            /*
                             * CORREO
                             */

                            if (
                                correo.value.trim() &&
                                !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo.value)
                            ) {

                                mostrarError(
                                    correo,
                                    'Ingresa un correo electrónico válido.'
                                );

                                formularioValido = false;
                                primerError ??= correo;
                            }


                            /*
                             * SI HAY ERRORES
                             * NO SE ENVÍA EL FORMULARIO.
                             */

                            if (!formularioValido) {

                                event.preventDefault();

                                if (primerError) {
                                    primerError.focus();
                                }
                            }

                        }
                    );

                });


            /*
             * ============================
             * MOSTRAR ERROR
             * ============================
             */

            function mostrarError(input, mensaje) {

                input.classList.add('is-invalid');

                let feedback =
                    input.parentElement.querySelector(
                        '.client-field-error'
                    );

                if (!feedback) {

                    feedback =
                        document.createElement('div');

                    feedback.className =
                        'invalid-feedback d-block client-field-error';

                    input.insertAdjacentElement(
                        'afterend',
                        feedback
                    );
                }

                feedback.textContent = mensaje;
            }


            /*
             * ============================
             * LIMPIAR ERROR DE UN CAMPO
             * ============================
             */

            function limpiarError(input) {

                input.classList.remove(
                    'is-invalid'
                );

                input.parentElement
                    .querySelectorAll('.invalid-feedback')
                    .forEach((feedback) => {
                        feedback.remove();
                    });

                input.parentElement
                    .querySelectorAll('.client-field-error')
                    .forEach((feedback) => {
                        feedback.remove();
                    });
            }


            /*
             * ============================
             * LIMPIAR ERRORES DEL FORMULARIO
             * ============================
             */

            function limpiarErrores(form) {

                form.querySelectorAll(
                    '.invalid-feedback'
                ).forEach((feedback) => {
                    feedback.remove();
                });

                form.querySelectorAll(
                    '.client-field-error'
                ).forEach((feedback) => {
                    feedback.remove();
                });

                form.querySelectorAll(
                    '.is-invalid'
                ).forEach((input) => {
                    input.classList.remove(
                        'is-invalid'
                    );
                });
            }




            /*
 * ==========================================
 * EVITAR RESTAURAR PÁGINAS PRIVADAS DESDE
 * EL HISTORIAL DEL NAVEGADOR
 * ==========================================
 */

window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        window.location.reload();
    }
});
        });
    </script>

</body>
</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Iniciar sesión</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="{{ asset('css/app-custom.css') }}"
        rel="stylesheet"
    >
</head>

<body class="login-body">

    <main class="login-page">

        <div class="login-card">

            <div class="login-header">

                <div class="login-icon">
                    $
                </div>

                <span class="section-kicker">
                    Acceso al sistema
                </span>

                <h1>
                    Sistema de Gestión de Créditos
                </h1>

                <p>
                    Ingresa tus credenciales para continuar.
                </p>

            </div>

            @if ($errors->any())

                <div class="alert alert-danger login-alert">

                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach

                </div>

            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="mb-3">

                    <label
                        for="username"
                        class="form-label"
                    >
                        Usuario
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        class="form-control"
                        autocomplete="username"
                        autofocus
                        required
                    >

                </div>

                <div class="mb-4">

                    <label
                        for="password"
                        class="form-label"
                    >
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        autocomplete="current-password"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100 login-button"
                >
                    Ingresar
                </button>

            </form>

        </div>

        <p class="login-footer">
            Sistema de Gestión de Créditos
        </p>

    </main>

</body>

</html>
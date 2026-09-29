{{--
    Sección "Acceso al sistema" compartida por clientes/create y clientes/edit.
    - En create no existe la variable $cliente.
    - En edit se recibe $cliente (con su relación usuario).
--}}
@php
    $usuarioAcceso = isset($cliente) ? $cliente->usuario : null;
    $esEdicion = isset($cliente);
    $tieneAcceso = $usuarioAcceso !== null;
    $usuarioObligatorio = ! $esEdicion || $tieneAcceso;
@endphp

<hr class="my-4">

<h2 class="h5 mb-1">Acceso al sistema</h2>

@if (! $esEdicion)
    <p class="text-muted small mb-3">
        Con este usuario y contraseña el cliente podrá iniciar sesión para consultar sus créditos y registrar pagos.
    </p>
@elseif ($tieneAcceso)
    <p class="text-muted small mb-3">
        Puedes cambiar el usuario o asignar una nueva contraseña al cliente.
    </p>
@else
    <div class="alert alert-warning small">
        Este cliente aún no tiene acceso al sistema. Si deseas habilitarlo, completa el usuario y la contraseña;
        si no, deja ambos campos vacíos.
    </div>
@endif

<div class="row g-4">
    <div class="col-md-6">
        <label for="username" class="form-label">Nombre de usuario</label>
        <input
            id="username"
            type="text"
            name="username"
            value="{{ old('username', $usuarioAcceso?->username) }}"
            class="form-control @error('username') is-invalid @enderror"
            maxlength="50"
            autocomplete="off"
            @if ($usuarioObligatorio) required @endif
        >
        @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">
            {{ $tieneAcceso ? 'Nueva contraseña' : 'Contraseña' }}
        </label>

        <div class="input-group has-validation">
            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                minlength="8"
                autocomplete="new-password"
                @if (! $esEdicion) required @endif
            >
            <button
                type="button"
                class="btn btn-outline-secondary"
                id="toggle-password"
                aria-controls="password"
            >
                Mostrar
            </button>

            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-text">
            @if ($tieneAcceso)
                Déjala vacía para conservar la contraseña actual. Si la cambias, avísale al cliente.
            @else
                Debe tener al menos 8 caracteres.
            @endif
        </div>
    </div>
</div>

<script>
    (function () {
        var boton = document.getElementById('toggle-password');
        var campo = document.getElementById('password');

        if (!boton || !campo) {
            return;
        }

        boton.addEventListener('click', function () {
            var oculto = campo.type === 'password';
            campo.type = oculto ? 'text' : 'password';
            boton.textContent = oculto ? 'Ocultar' : 'Mostrar';
        });
    })();
</script>
